<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\FacebookAccount;
use App\Models\FacebookPage;
use App\Models\FacebookPixel;
use App\Models\AdAccount;
use Illuminate\Support\Facades\DB;


class FacebookController extends Controller
{

    /**
     * Redirect to Facebook OAuth
     */
    public function redirectToFacebook()
    {
        // Generate random state for CSRF protection
        $state = bin2hex(random_bytes(32));
        
        // Store state in session
        Session::put('facebook_oauth_state', $state);
        session(['facebook_oauth_state' => $state]);
        
        $params = [
            'access_type' => 'offline',
            'client_id' => config('services.facebook.client_id'),
            'redirect_uri' => config('services.facebook.redirect'),
            'response_type' => 'code',
            'scope' => 'ads_read ads_management pages_manage_ads pages_read_engagement pages_show_list',
            'state' => $state
        ];
        
        // Use specific API version
        $url = 'https://www.facebook.com/v3.2/dialog/oauth?' . http_build_query($params);
        
        return redirect($url);
    }

    /**
     * Handle Facebook Callback - Jab user "Continue as Athif" ya "Log in to another account" click kare
     */
    public function handleCallback(Request $request)
    {
        // Log everything for debugging
        Log::info('=== FACEBOOK CALLBACK STARTED ===');
        Log::info('All Request Data: ' . json_encode($request->all()));
        Log::info('Auth User ID: ' . auth()->id());
        
        try {
            // Check for Facebook errors
            if ($request->has('error')) {
                Log::error('Facebook Error: ' . $request->get('error'));
                Log::error('Error Description: ' . $request->get('error_description', 'No description'));
                return redirect('/dashboard')->with('error', 'Facebook authorization was denied');
            }
            
            // Get authorization code
            $code = $request->get('code');
            if (!$code) {
                Log::error('No authorization code received');
                return redirect('/dashboard')->with('error', 'No authorization code received from Facebook');
            }
            
            Log::info('Authorization code received: ' . substr($code, 0, 30) . '...');
            
            // Exchange code for access token
            $tokenResponse = Http::timeout(30)->get('https://graph.facebook.com/v3.2/oauth/access_token', [
                'client_id' => config('services.facebook.client_id'),
                'client_secret' => config('services.facebook.client_secret'),
                'redirect_uri' => config('services.facebook.redirect'),
                'code' => $code
            ]);
            
            Log::info('Token Response Status: ' . $tokenResponse->status());
            
            if (!$tokenResponse->successful()) {
                Log::error('Token Response Error: ' . $tokenResponse->body());
                return redirect('/dashboard')->with('error', 'Failed to get access token from Facebook');
            }
            
            $tokenData = $tokenResponse->json();
            Log::info('Token Data: ' . json_encode($tokenData));
            
            $accessToken = $tokenData['access_token'] ?? null;
            
            if (!$accessToken) {
                Log::error('No access token in response');
                return redirect('/dashboard')->with('error', 'No access token received');
            }
            
            // Get user info
            $userResponse = Http::timeout(30)->get('https://graph.facebook.com/v3.2/me', [
                'fields' => 'id,name,email,picture.width(200).height(200)',
                'access_token' => $accessToken
            ]);
            
            Log::info('User Response Status: ' . $userResponse->status());
            
            if (!$userResponse->successful()) {
                Log::error('User Response Error: ' . $userResponse->body());
                return redirect('/dashboard')->with('error', 'Failed to get user info from Facebook');
            }
            
            $userData = $userResponse->json();
            Log::info('Facebook User Data: ' . json_encode($userData));
            
            // Check authentication
            if (!auth()->check()) {
                Log::error('User not authenticated');
                return redirect('/login')->with('error', 'Please login first');
            }
            
            $userId = auth()->id();
            Log::info('Saving data for User ID: ' . $userId);
            
            // Save Facebook Account
            try {
                $facebookAccount = FacebookAccount::updateOrCreate(
                    ['facebook_user_id' => $userData['id']],
                    [
                        'user_id' => $userId,
                        'name' => $userData['name'],
                        'email' => $userData['email'] ?? null,
                        'profile_picture' => $userData['picture']['data']['url'] ?? null,
                        'access_token' => encrypt($accessToken),
                        'token_expires_at' => now()->addDays(60),
                        'status' => 'active'
                    ]
                );
                
                Log::info('Facebook Account Saved with ID: ' . $facebookAccount->id);
                
            } catch (\Exception $dbError) {
                Log::error('Database Save Error: ' . $dbError->getMessage());
                Log::error('Stack Trace: ' . $dbError->getTraceAsString());
                
                // Try direct insert for debugging
                try {
                    $testInsert = DB::table('facebook_accounts')->insert([
                        'user_id' => $userId,
                        'facebook_user_id' => $userData['id'],
                        'name' => $userData['name'],
                        'email' => $userData['email'] ?? null,
                        'profile_picture' => $userData['picture']['data']['url'] ?? null,
                        'access_token' => encrypt($accessToken),
                        'token_expires_at' => now()->addDays(60),
                        'status' => 'active',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    
                    Log::info('Direct DB Insert Result: ' . ($testInsert ? 'Success' : 'Failed'));
                    
                } catch (\Exception $e2) {
                    Log::error('Direct Insert Error: ' . $e2->getMessage());
                }
                
                return redirect('/dashboard')->with('error', 'Database error: ' . $dbError->getMessage());
            }
            
            // Get Ad Accounts
            try {
                $adAccountsResponse = Http::timeout(30)->get('https://graph.facebook.com/v3.2/me/adaccounts', [
                    'fields' => 'id,name,account_status,currency,timezone_name',
                    'limit' => 100,
                    'access_token' => $accessToken
                ]);
                
                if ($adAccountsResponse->successful()) {
                    $adAccounts = $adAccountsResponse->json()['data'] ?? [];
                    Log::info('Found ' . count($adAccounts) . ' ad accounts');
                    
                    // Save ad accounts - use correct table name
                    foreach ($adAccounts as $adAccount) {
                        DB::table('facebook_ad_accounts')->updateOrInsert(
                            ['ad_account_id' => $adAccount['id']],
                            [
                                'facebook_account_id' => $facebookAccount->id,
                                'account_name' => $adAccount['name'] ?? 'Unnamed',
                                'currency' => $adAccount['currency'] ?? 'USD',
                                'timezone_name' => $adAccount['timezone_name'] ?? null,
                                'account_status' => $adAccount['account_status'] ?? 0,
                                'is_active' => ($adAccount['account_status'] ?? 0) == 1,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]
                        );
                    }
                }
            } catch (\Exception $e) {
                Log::error('Ad Accounts Error: ' . $e->getMessage());
            }
            
            // Get Pages
            try {
                $pagesResponse = Http::timeout(30)->get('https://graph.facebook.com/v3.2/me/accounts', [
                    'fields' => 'id,name,access_token,category,fan_count',
                    'limit' => 100,
                    'access_token' => $accessToken
                ]);
                
                if ($pagesResponse->successful()) {
                    $pages = $pagesResponse->json()['data'] ?? [];
                    Log::info('Found ' . count($pages) . ' pages');
                    
                    // Save pages
                    foreach ($pages as $page) {
                        DB::table('facebook_pages')->updateOrInsert(
                            ['page_id' => $page['id']],
                            [
                                'facebook_account_id' => $facebookAccount->id,
                                'page_name' => $page['name'],
                                'page_access_token' => encrypt($page['access_token']),
                                'category' => $page['category'] ?? null,
                                'followers_count' => $page['fan_count'] ?? 0,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]
                        );
                    }
                }
            } catch (\Exception $e) {
                Log::error('Pages Error: ' . $e->getMessage());
            }
            
            Log::info('=== FACEBOOK CALLBACK COMPLETED SUCCESSFULLY ===');
            
            return redirect('/dashboard')->with('success', 'Facebook account connected successfully!');
            
        } catch (\Exception $e) {
            Log::error('=== FACEBOOK CALLBACK ERROR ===');
            Log::error('Main Exception: ' . $e->getMessage());
            Log::error('Stack Trace: ' . $e->getTraceAsString());
            
            return redirect('/dashboard')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    /* public function handleCallback(Request $request)
    {
        $code = $request->get('code');
        
        if (!$code) {
            return redirect('/')
                ->with('error', 'Facebook authorization failed');
        }

        try {
            // Exchange code for access token - all values from .env
            $response = Http::get('https://graph.facebook.com/'.config('services.facebook.version').'/oauth/access_token', [
                'client_id' => config('services.facebook.app_id'),
                'redirect_uri' => config('services.facebook.redirect'),
                'client_secret' => config('services.facebook.app_secret'),
                'code' => $code,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $accessToken = $data['access_token'];
                
                // Store token in session or database
                session(['facebook_access_token' => $accessToken]);
                
                return redirect('/dashboard')
                    ->with('success', 'Facebook connected successfully');
            }
            
            return redirect('/')
                ->with('error', 'Failed to get access token from Facebook');
                
        } catch (\Exception $e) {
            Log::error('Facebook OAuth Error: ' . $e->getMessage());
            
            return redirect('/')
                ->with('error', 'An error occurred during Facebook authorization');
        }
    } */

    /**
     * Exchange code for access token
     */
    

}
