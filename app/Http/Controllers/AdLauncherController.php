<?php

namespace App\Http\Controllers;

use App\Models\Preset;
use App\Models\AdAccount;
use App\Models\FacebookPage;
use App\Models\FacebookPixel;
use App\Models\AdLauncher;
use App\Models\Campaign;
use App\Models\Adset;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\FacebookAdsService;
use Illuminate\Support\Facades\Log;

class AdLauncherController extends Controller
{
    protected $fbService;

    public function __construct(FacebookAdsService $fbService)
    {
        $this->fbService = $fbService;
    }

    /**
     * Display ad launcher list
     */
    public function index()
    {
        $adLaunchers = AdLauncher::latest()->get();
        
        return view('ad-launcher.index', compact('adLaunchers'));
    }

    /**
     * Show multi-step form
     */
    public function create()
    { 
        // Get all required data for dropdowns
        $presets = $this->getPresets();
        $providers = $this->getProviders();
        $adAccounts = $this->getAdAccounts();
        $pages = $this->getFacebookPages();
        $pixels = $this->getFacebookPixels();
        $countryPresets = $this->getCountryPresets();
        $campaignObjectives = $this->getCampaignObjectives();
        $specialAdCategories = $this->getSpecialAdCategories();
        
        return view('ad-launcher.create', compact(
            'presets',
            'providers',
            'adAccounts',
            'pages',
            'pixels',
            'countryPresets',
            'campaignObjectives',
            'specialAdCategories'
        ));
    }

    /**
     * Get presets for dropdown
     */
    private function getPresets()
    {
        return Preset::active()
            ->pluck('name', 'id')
            ->toArray();
    }

    /**
     * Get providers for dropdown
     */
    private function getProviders()
    {
        return [
            'facebook' => 'Facebook Ads',
            //'google' => 'Google Ads',
            //'linkedin' => 'LinkedIn Ads',
        ];
    }

    /**
     * Get ad accounts for dropdown (initially empty, will be loaded via AJAX)
     */
    private function getAdAccounts()
    {
        try {
            // Get current user's Facebook ad accounts
            $adAccounts = DB::table('facebook_ad_accounts as faa')
                ->join('facebook_accounts as fa', 'faa.facebook_account_id', '=', 'fa.id')
                ->where('fa.user_id', auth()->id())
                ->where('fa.status', 'active')
                ->where('faa.is_active', true)
                ->select(
                    'faa.ad_account_id as id',
                    'faa.account_name as name',
                    'faa.currency',
                    'fa.name as facebook_account_name'
                )
                ->get()
                ->map(function($account) {
                    return [
                        'id' => $account->id,
                        'name' => $account->name . ' (' . $account->facebook_account_name . ')',
                        'currency' => $account->currency,
                    ];
                })
                ->toArray();
                
            return $adAccounts;
            
        } catch (\Exception $e) {
            \Log::error('Error fetching ad accounts: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get Facebook pages for dropdown
     */
    private function getFacebookPages()
    {
        return FacebookPage::active()
            ->pluck('page_name', 'page_id')
            ->toArray();
    }

    /**
     * Get Facebook pixels for dropdown
     */
    private function getFacebookPixels()
    {
        return FacebookPixel::active()
            ->pluck('name', 'pixel_id')
            ->toArray();
    }

    /**
     * Get country presets for dropdown
     */
    private function getCountryPresets()
    {
        return [
            'IN' => 'India',
            'US' => 'United States',
            'GB' => 'United Kingdom',
            'CA' => 'Canada',
            'AU' => 'Australia',
            'AE' => 'United Arab Emirates',
            'SG' => 'Singapore',
        ];
    }

    /**
     * Get campaign objectives
     */
    private function getCampaignObjectives()
    {
        return [
            'OUTCOME_SALES' => 'Sales',
            //'OUTCOME_TRAFFIC' => 'Traffic',
            //'OUTCOME_ENGAGEMENT' => 'Engagement',
            'OUTCOME_LEADS' => 'Leads',
            //'OUTCOME_AWARENESS' => 'Awareness',
            //'OUTCOME_APP_PROMOTION' => 'App Promotion',
        ];
    }

    /**
     * Get special ad categories
     */
    private function getSpecialAdCategories()
    {
        return [
            //'NONE' => 'None',
            'CREDIT' => 'Credit',
            'EMPLOYMENT' => 'Employment',
            'HOUSING' => 'Housing',
            //'SOCIAL_ISSUES' => 'Social Issues, Elections or Politics',
        ];
    }

    /**
     * API endpoint to get ad accounts by provider
     */
    public function getAdAccountsByProvider(Request $request)
    {
        $provider = $request->input('provider');
        
        if (!$provider) {
            return response()->json([]);
        }

        $adAccounts = AdAccount::active()
            ->byProvider($provider)
            ->get(['account_id as id', 'name'])
            ->toArray();

        return response()->json($adAccounts);
    }

    /**
     * API endpoint to get pixels by ad account
     */
    public function getPixelsByAdAccount(Request $request)
    {
        $adAccountId = $request->input('ad_account_id');
        
        if (!$adAccountId) {
            return response()->json([]);
        }

        $pixels = FacebookPixel::active()
            ->byAdAccount($adAccountId)
            ->get(['pixel_id as id', 'name'])
            ->toArray();

        return response()->json($pixels);
    }

    /**
     * Store ad launcher
     */
    public function store(Request $request)
    { 
        //dd($request->all());
        // Log raw request
        Log::info('=== RAW REQUEST DATA ===', $request->all());

        // Validate the request
        $validated = $request->validate([
            'preset' => 'nullable|exists:presets,id',
            'provider' => 'required|string',
            'ad_account' => 'required|string',
            'campaign_name' => 'required|string|max:255',
            'campaign_objective' => 'required|string',
            'special_ad_categories' => 'nullable|string',
            //'campaign_budget' => 'required|numeric|min:1',
            'adset_name' => 'required|string|max:255',
            'performance_goal' => 'nullable|string',
            'pixel' => 'required|string',
            'adset_budget' => 'required|numeric|min:1',
            'cost_goal' => 'nullable|numeric',
            'bid_strategy' => 'nullable|string',
            'billing_event' => 'required|string',
            'start_date' => 'nullable|date',
            'timezone' => 'nullable|string',
            'page' => 'required|string',
            'country_preset' => 'nullable|string',
            'ad_name' => 'required|string|max:255',
            'ad_format' => 'required|string',
            'primary_text' => 'required|string',
            'headline' => 'required|string|max:40',
            'description' => 'nullable|string|max:30',
            'media' => 'required|file|mimes:jpg,jpeg,png,mp4|max:102400',
            'cta_button' => 'required|string',
            'website_url' => 'required|url',
            'display_link' => 'nullable|string',
            'url_parameters' => 'nullable|string',
            'pixel_event' => 'required|string',
            'status' => 'required|in:active,paused',
        ]);

        try {
            DB::beginTransaction();

            // Handle media upload
            $mediaPath = null;
            $mediaType = 'image';
            
            if ($request->hasFile('media')) {
                $file = $request->file('media');
                $mediaPath = $file->store('ad-creatives', 'public');
                $mediaType = str_starts_with($file->getMimeType(), 'video/') ? 'video' : 'image';
            }

            // Set Facebook Ad Account
            $this->fbService->setAdAccount($validated['ad_account']);

            // CRITICAL FIX: Match performance goal with campaign objective
            $performanceGoal = $this->matchPerformanceGoal(
                $validated['campaign_objective'], 
                $validated['performance_goal']
            );

            Log::info('Matched Performance Goal', [
                'objective' => $validated['campaign_objective'],
                'requested_goal' => $validated['performance_goal'],
                'final_goal' => $performanceGoal
            ]);

            $specialAdCategories = $validated['special_ad_categories'] ?? 'NONE';
            if ($specialAdCategories === 'NONE' || empty($specialAdCategories)) {
                $specialAdCategories = [];
            } else {
                $specialAdCategories = [$specialAdCategories];
            }

            // ====================
            // STEP 1: Create Campaign
            // ====================
            $fbCampaign = $this->fbService->createCampaign([
                'name' => $validated['campaign_name'],
                'objective' => $validated['campaign_objective'],
                'status' => $validated['status'],
                //'special_ad_categories' => [], // Always empty for now
                //'special_ad_categories' => $this->normalizeSpecialAdCategories($validated['special_ad_categories'] ?? null),
                'special_ad_categories' => $specialAdCategories,
                'buying_type' => "AUCTION",
                'is_adset_budget_sharing_enabled' => false
                //'daily_budget' => (int) ($validated['campaign_budget'] * 100),
            ]);

            if (!$fbCampaign['success']) {
                throw new \Exception('Facebook Campaign creation failed: ' . $fbCampaign['error']);
            }

            $campaign = Campaign::create([
                'provider' => $validated['provider'],
                'ad_account_id' => $validated['ad_account'],
                'preset_id' => $validated['preset'] ?? null,
                'campaign_id' => $fbCampaign['campaign_id'],
                'name' => $validated['campaign_name'],
                'objective' => $validated['campaign_objective'],
                //'special_ad_categories' => [],
                'special_ad_categories' => $request->special_ad_categories ? [strtoupper($request->special_ad_categories)] : [], // or [] if none
                'daily_budget' => null,
                'status' => 'PAUSED',
                'buying_type' => 'AUCTION',
            ]);

            Log::info('Facebook Campaign Created', ['campaign_id' => $fbCampaign['campaign_id']]);

            // ====================
            // STEP 2: Create AdSet
            // ====================
            $fbAdset = $this->fbService->createAdSet($fbCampaign['campaign_id'], [
                'name' => $validated['adset_name'],
                'daily_budget' => (int) ($validated['adset_budget'] * 100),
                'cost_per_result_goal' => isset($validated['cost_goal']) && $validated['cost_goal'] > 0
                    ? (int) ($validated['cost_goal'] * 100)
                    : null,
                'performance_goal' => $performanceGoal, // Fixed goal
                'billing_event' => strtoupper($validated['billing_event']), // Uppercase
                'pixel_id' => $validated['pixel'],
                'country_preset' => $validated['country_preset'] ?? 'IN',
                'start_time' => isset($validated['start_date']) && !empty($validated['start_date'])
                    ? \Carbon\Carbon::parse($validated['start_date'])
                    : now()->addHours(2),
            ]);

            if (!$fbAdset['success']) {
                throw new \Exception('Facebook AdSet creation failed: ' . $fbAdset['error']);
            }

            $adset = Adset::create([
                'campaign_id' => $campaign->id,
                'facebook_campaign_id' => $campaign->campaign_id,
                'adset_id' => $fbAdset['adset_id'],
                'name' => $validated['adset_name'],
                'daily_budget' => $validated['adset_budget'] * 100,
                'cost_per_result_goal' => isset($validated['cost_goal']) && $validated['cost_goal'] > 0
                    ? $validated['cost_goal'] * 100
                    : null,
                'performance_goal' => $performanceGoal,
                'billing_event' => strtoupper($validated['billing_event']),
                'bid_strategy' => null,
                'page_id' => $validated['page'],
                'pixel_id' => $validated['pixel'],
                'country_preset' => $validated['country_preset'],
                'start_time' => $validated['start_date'] ?? now(),
                'timezone' => $validated['timezone'] ?? 'UTC',
                'status' => 'PAUSED',
            ]);

            Log::info('Facebook AdSet Created', ['adset_id' => $fbAdset['adset_id']]);

            // ====================
            // STEP 3: Create Ad Creative
            // ====================
            $fbCreative = $this->fbService->createAdCreative([
                'name' => $validated['ad_name'],
                'page_id' => $validated['page'],
                'primary_text' => $validated['primary_text'],
                'headline' => $validated['headline'],
                'description' => $validated['description'],
                'media_path' => $mediaPath,
                'media_type' => $mediaType,
                'cta_button' => strtoupper($validated['cta_button']),
                'website_url' => $validated['website_url'],
                'url_parameters' => $validated['url_parameters'],
            ]);

            if (!$fbCreative['success']) {
                throw new \Exception('Facebook Ad Creative creation failed: ' . $fbCreative['error']);
            }

            // ====================
            // STEP 4: Create Ad
            // ====================
            $fbAd = $this->fbService->createAd(
                $fbAdset['adset_id'],
                $fbCreative['creative_id'],
                [
                    'name' => $validated['ad_name'],
                    //'pixel_id' => $validated['pixel'],
                    //'pixel_event' => $validated['pixel_event'],
                ]
            );

            if (!$fbAd['success']) {
                throw new \Exception('Facebook Ad creation failed: ' . $fbAd['error']);
            }

            $ad = Ad::create([
                'adset_id' => $adset->id,
                'facebook_adset_id' => $adset->adset_id,
                'ad_id' => $fbAd['ad_id'],
                'name' => $validated['ad_name'],
                'format' => $validated['ad_format'],
                'primary_text' => $validated['primary_text'],
                'headline' => $validated['headline'],
                'description' => $validated['description'],
                'media_path' => $mediaPath,
                'cta_button' => $validated['cta_button'],
                'website_url' => $validated['website_url'],
                'display_link' => $validated['display_link'],
                'url_parameters' => $validated['url_parameters'],
                'pixel_event' => $validated['pixel_event'],
                'status' => 'PAUSED',
            ]);

            AdLauncher::create([
                'name' => $validated['campaign_name'],
                'campaign_names' => $validated['campaign_name'],
                'provider' => $validated['provider'],
                'query' => $validated['headline'] ?? null,
                'ad_account' => $validated['ad_account'],
                'language' => 'en',
                'country' => $validated['country_preset'] ?? 'IN',
                'status' => 'paused',
            ]);

            DB::commit();

            return redirect()
                ->route('ad-launcher.create')
                ->with('success', 'Ad campaign created successfully on Facebook! Campaign ID: ' . $fbCampaign['campaign_id']);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Ad Creation Failed', [
                'error' => $e->getMessage(),
            ]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error creating ad campaign: ' . $e->getMessage());
        }
    }


    private function normalizeSpecialAdCategories($value)
    {
        if (empty($value) || strtolower($value) === 'none') {
            return [];
        }

        // Normalize comma-separated strings or single string input
        if (is_string($value)) {
            $value = explode(',', $value);
        }

        $allowed = ['CREDIT', 'EMPLOYMENT', 'HOUSING'];
        return array_values(array_intersect($allowed, array_map('strtoupper', $value)));
    }



    /**
     * Match performance goal with campaign objective
     */
    protected function matchPerformanceGoal($objective, $requestedGoal)
    {
        $validGoals = [
            'OUTCOME_LEADS' => ['LEAD_GENERATION', 'LANDING_PAGE_VIEWS', 'OFFSITE_CONVERSIONS'],
            'OUTCOME_SALES' => ['OFFSITE_CONVERSIONS', 'VALUE', 'LANDING_PAGE_VIEWS'],
            'OUTCOME_TRAFFIC' => ['LINK_CLICKS', 'LANDING_PAGE_VIEWS', 'IMPRESSIONS'],
            'OUTCOME_ENGAGEMENT' => ['POST_ENGAGEMENT', 'PAGE_LIKES'],
            'OUTCOME_AWARENESS' => ['REACH', 'IMPRESSIONS'],
        ];

        $objective = strtoupper($objective);
        $requestedGoal = strtoupper($requestedGoal);

        // If invalid goal for objective, use default
        if (isset($validGoals[$objective]) && !in_array($requestedGoal, $validGoals[$objective])) {
            $defaults = [
                'OUTCOME_LEADS' => 'LEAD_GENERATION',
                'OUTCOME_SALES' => 'OFFSITE_CONVERSIONS',
                'OUTCOME_TRAFFIC' => 'LINK_CLICKS',
            ];
            
            return $defaults[$objective] ?? $requestedGoal;
        }

        return $requestedGoal;
    }
}