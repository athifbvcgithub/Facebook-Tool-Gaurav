<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Adset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AdSetController extends Controller
{
    /**
     * Show the form for creating a new adset.
     */
    public function create()
    {
        // Fetch campaigns for dropdown
        $campaigns = Campaign::whereNotNull('id')
            ->orderBy('created_at', 'desc')
            ->get(['campaign_id', 'name']);
        
        return view('adsets.create', compact('campaigns'));
    }

    /**
     * Store a newly created adset.
     */
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'campaign_id' => 'required|string',
            'daily_budget' => 'required|integer|min:0',
            'billing_event' => 'required|string',
            'optimization_goal' => 'required|string',
            'bid_amount' => 'required|numeric|min:0',
            'targeting' => 'required|string',
            'age_min' => 'required|integer|min:13|max:65',
            'age_max' => 'required|integer|min:13|max:65',
            'genders' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|in:ACTIVE,PAUSED',
        ], [
            // Custom error messages
            'end_time.after' => 'End time must be after start time.',
            'start_time.required' => 'Start time is required.',
            'end_time.required' => 'End time is required.',
            'start_time.date' => 'Please enter a valid start time.',
            'end_time.date' => 'Please enter a valid end time.',
        ]);

        try {
            // Process genders - Model automatically casts to array
            $gendersArray = ($validated['genders'] === 'both') 
                ? [Adset::GENDER_MALE, Adset::GENDER_FEMALE] 
                : [intval($validated['genders'])];

            // Create adset using Model
            $adset = Adset::create([
                'name' => $validated['name'],
                'campaign_id' => $validated['campaign_id'],
                'daily_budget' => $validated['daily_budget'],
                'billing_event' => $validated['billing_event'],
                'optimization_goal' => $validated['optimization_goal'],
                'bid_amount' => $validated['bid_amount'],
                'targeting' => $validated['targeting'],
                'age_min' => $validated['age_min'],
                'age_max' => $validated['age_max'],
                'genders' => $gendersArray, // Model automatically converts to JSON
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'status' => $validated['status'],
            ]);

            // Call Facebook API
            /* $fbApiUrl = config('services.facebook.api_url') . '/' . config('services.facebook.ad_account_id') . '/adsets';

            $response = Http::post($fbApiUrl, [
                'name' => $validated['name'],
                'campaign_id' => $validated['campaign_id'],
                'daily_budget' => $validated['daily_budget'],
                'billing_event' => $validated['billing_event'],
                'optimization_goal' => $validated['optimization_goal'],
                'bid_amount' => $validated['bid_amount'],
                'targeting' => [
                    'geo_locations' => ['cities' => [['name' => $validated['targeting']]]],
                    'age_min' => $validated['age_min'],
                    'age_max' => $validated['age_max'],
                    'genders' => $gendersArray,
                ],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'status' => $validated['status'],
                'access_token' => config('services.facebook.access_token'),
            ]);

            $fbData = $response->json();

            // Update adset with Facebook ID using Model
            if (isset($fbData['id'])) {
                $adset->update([
                    'adset_id' => $fbData['id'],
                ]);
            } */

            return redirect()
                ->route('adsets.create')
                ->with('success', '✅ AdSet created successfully! Facebook ID: ' . ($fbData['id'] ?? 'N/A'));

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Error: ' . $e->getMessage());
        }
    }
}