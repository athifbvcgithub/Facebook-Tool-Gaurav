<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CampaignController extends Controller
{
    /**
     * Display a listing of campaigns.
     */
    public function index()
    {
        return view('campaigns.index');
    }

    /**
     * Show the form for creating a new campaign.
     */
    public function create()
    {
        return view('campaigns.create');
    }

    /**
     * Store a newly created campaign.
     */
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'objective' => 'required|string',
            'status' => 'required|in:ACTIVE,PAUSED',
            'buying_type' => 'required|in:AUCTION,RESERVED',
            'is_adset_budget_sharing_enabled' => 'required|boolean',
            'daily_budget' => 'required_if:is_adset_budget_sharing_enabled,1|nullable|numeric|min:0',
            'special_ad_categories' => 'nullable|array',
            'special_ad_categories.*' => 'in:CREDIT,HOUSING,EMPLOYMENT',
        ]);

        try {
            // Prepare special_ad_categories
            $specialAdCategories = $validated['special_ad_categories'] ?? [];

            // Create campaign in database using Model
            $campaign = Campaign::create([
                'name' => $validated['name'],
                'objective' => $validated['objective'],
                'status' => $validated['status'],
                'buying_type' => $validated['buying_type'],
                'is_adset_budget_sharing_enabled' => $validated['is_adset_budget_sharing_enabled'],
                'special_ad_categories' => $specialAdCategories,
                'daily_budget' => ($validated['is_adset_budget_sharing_enabled'] == 1 && !empty($validated['daily_budget'])) 
                    ? $validated['daily_budget'] 
                    : null,
            ]);

            // Prepare Facebook API data
            $fbApiData = [
                'name' => $validated['name'],
                'objective' => $validated['objective'],
                'status' => $validated['status'],
                'special_ad_categories' => json_encode($specialAdCategories),
                'buying_type' => $validated['buying_type'],
                'is_adset_budget_sharing_enabled' => $validated['is_adset_budget_sharing_enabled'],
                'access_token' => config('services.facebook.access_token'),
            ];

            // Add daily_budget to Facebook API if budget sharing is enabled
            if ($validated['is_adset_budget_sharing_enabled'] == 1 && !empty($validated['daily_budget'])) {
                $fbApiData['daily_budget'] = (int)($validated['daily_budget'] * 100);
            }

            // Call Facebook API
            /* $fbApiUrl = config('services.facebook.api_url') . '/' . config('services.facebook.ad_account_id') . '/campaigns';
            $response = Http::post($fbApiUrl, $fbApiData);
            $fbData = $response->json();

            // Update campaign with Facebook ID
            if (isset($fbData['id'])) {
                $campaign->update([
                    'campaign_id' => $fbData['id'],
                ]);
            } */

            return redirect()
                ->route('campaigns.create')
                ->with('success', '✅ Campaign created successfully! Facebook ID: ' . ($fbData['id'] ?? 'N/A'));

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified campaign.
     */
    public function show($id)
    {
        $campaign = Campaign::findOrFail($id);
        
        return view('campaigns.show', compact('campaign'));
    }

    /**
     * Show the form for editing the specified campaign.
     */
    public function edit($id)
    {
        $campaign = Campaign::findOrFail($id);
        
        return view('campaigns.edit', compact('campaign'));
    }

    /**
     * Update the specified campaign.
     */
    public function update(Request $request, $id)
    {
        // To be implemented
    }

    /**
     * Remove the specified campaign.
     */
    public function destroy($id)
    {
        // To be implemented
    }
}