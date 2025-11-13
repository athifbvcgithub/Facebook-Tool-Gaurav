<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FacebookAccount;
use App\Models\FacebookPage;
use App\Models\AdAccount;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    /**
     * Show connections page
     */
    public function connections()
    {
        $facebookAccounts = FacebookAccount::where('user_id', auth()->id())
            ->where('status', 'active')
            ->withCount('adAccounts')
            ->get();
        
        $connections = $facebookAccounts->map(function($account) {
            return [
                'id' => $account->id,  // Important: Facebook account ID
                'name' => $account->name,
                'email' => $account->email,
                'profile_picture' => $account->profile_picture,
                'facebook_user_id' => $account->facebook_user_id,
                'ad_accounts_count' => $account->ad_accounts_count
            ];
        });
        
        return view('settings.connections', compact('connections'));
    }


    /**
     * Show ad accounts page - ONLY ACTIVE ACCOUNTS
     */
    public function adAccounts()
    {
        // Get all Facebook accounts with ONLY ACTIVE ad accounts
        $facebookAccounts = FacebookAccount::where('user_id', auth()->id())
            ->where('status', 'active')
            ->with(['adAccounts' => function($query) {
                // Filter for only active ad accounts
                $query->where('is_active', true)
                      ->where('account_status', 1); // Account status 1 = active
            }])
            ->get();
        
        // Flatten only active ad accounts for table view
        $adAccounts = [];
        foreach($facebookAccounts as $fbAccount) {
            foreach($fbAccount->adAccounts as $adAccount) {
                // Double check for active status
                if($adAccount->is_active && $adAccount->account_status == 1) {
                    $adAccounts[] = [
                        'facebook_account' => [
                            'name' => $fbAccount->name,
                            'profile_picture' => $fbAccount->profile_picture
                        ],
                        'id' => $adAccount->id,
                        'ad_account_id' => $adAccount->ad_account_id,
                        'account_name' => $adAccount->account_name,
                        'currency' => $adAccount->currency,
                        'timezone_name' => $adAccount->timezone_name,
                        'balance' => $adAccount->balance,
                        'is_active' => $adAccount->is_active,
                        'account_status' => $adAccount->account_status,
                        'created_at' => $adAccount->created_at
                    ];
                }
            }
        }
        
        return view('settings.ad-accounts', compact('adAccounts'));
    }

    /**
 * Show ad accounts for a specific Facebook account
 */
    public function facebookAccountAdAccounts($id)
    {
        // Get the specific Facebook account with its ad accounts
        $facebookAccount = FacebookAccount::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->with(['adAccounts'])
            ->firstOrFail();
        
        // Format ad accounts for table view
        $adAccounts = [];
        foreach($facebookAccount->adAccounts as $adAccount) {
            $adAccounts[] = [
                'facebook_account' => [
                    'name' => $facebookAccount->name,
                    'profile_picture' => $facebookAccount->profile_picture
                ],
                'id' => $adAccount->id,
                'ad_account_id' => $adAccount->ad_account_id,
                'account_name' => $adAccount->account_name,
                'currency' => $adAccount->currency,
                'timezone_name' => $adAccount->timezone_name,
                'balance' => $adAccount->balance,
                'is_active' => $adAccount->is_active,
                'account_status' => $adAccount->account_status,
                'created_at' => $adAccount->created_at
            ];
        }
        
        // Pass Facebook account info for display
        $accountInfo = [
            'name' => $facebookAccount->name,
            'facebook_user_id' => $facebookAccount->facebook_user_id,
            'profile_picture' => $facebookAccount->profile_picture
        ];
        
        return view('settings.facebook-ad-accounts', compact('adAccounts', 'accountInfo'));
    }
}