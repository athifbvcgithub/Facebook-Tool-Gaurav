<?php

use App\Http\Controllers\ProfileController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdsController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\AdSetController;
use App\Http\Controllers\AdLauncherController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\FacebookController;

use App\Http\Controllers\Auth\RegisteredUserController;


Route::get('/', function () {
    return redirect('/login');
});

// Protected routes (logged in)
Route::middleware('auth')->group(function () {
    
    // Logout
    Route::post('/logout', [Auth\RegisteredUserController::class, 'logout'])->name('logout');

    // Confirm Password
    Route::get('/confirm-password', [Auth\RegisteredUserController::class, 'showConfirmPassword'])->name('password.confirm');
    Route::post('/confirm-password', [Auth\RegisteredUserController::class, 'confirmPassword']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    
    // Change Password
    Route::get('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Your existing routes
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Stats
    Route::get('/stats', function () {
        return view('dashboard.stats.index');
    })->name('stats');

    // Benchmarks
    Route::get('/benchmarks', function () {
        return view('dashboard.benchmarks.index');
    })->name('benchmarks');

    // Notifications
    Route::get('/notifications', function () {
        return view('dashboard.notifications.index');
    })->name('notifications');
    
    // Ads Routes
    Route::prefix('dashboard/ads')->group(function () {
        Route::get('/', [AdsController::class, 'index'])->name('ads.index');
        Route::get('/launcher', [AdsController::class, 'launcher'])->name('ads.launcher');
        Route::get('/creatives', [AdsController::class, 'creatives'])->name('ads.creatives');
        Route::get('/launcher-presets', [AdsController::class, 'launcherPresets'])->name('ads.launcher-presets');
        Route::get('/country-presets', [AdsController::class, 'countryPresets'])->name('ads.country-presets');
    });

    Route::prefix('ad-launcher')->name('ad-launcher.')->group(function () {
        Route::get('/', [AdLauncherController::class, 'index'])->name('index');
        Route::get('/create', [AdLauncherController::class, 'create'])->name('create');
        Route::post('/store', [AdLauncherController::class, 'store'])->name('store');
    });

    // Campaigns
    Route::resource('campaigns', CampaignController::class);
    
    // AdSets
    Route::resource('adsets', AdSetController::class);
    
    // Settings Routes (Auth Required)
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/connections', [SettingsController::class, 'connections'])->name('connections');
        Route::get('/ad-accounts', [SettingsController::class, 'adAccounts'])->name('ad-accounts');
        Route::view('/connections/add', 'settings.add-new-connection')->name('connections.add');
        // New route for specific Facebook account's ad accounts
        Route::get('/facebook-account/{id}/ad-accounts', [SettingsController::class, 'facebookAccountAdAccounts'])
        ->name('facebook-ad-accounts');
    });

    Route::get('/oauth/facebook', [FacebookController::class, 'handleCallback'])->name('oauth.facebook');
    Route::get('/facebook/redirect', [FacebookController::class, 'redirectToFacebook'])->name('facebook.redirect');
    Route::get('/oauth/facebook', [FacebookController::class, 'handleCallback'])->name('facebook.callback');
    Route::get('/facebook/accounts', [FacebookController::class, 'listAccounts'])->name('facebook.accounts');
    Route::post('/facebook/disconnect/{id}', [FacebookController::class, 'disconnectAccount'])->name('facebook.disconnect');

});

//Testing purpose
Route::get('/debug-fb-config', function () {
    $config = [
        'app_id' => config('services.facebook.app_id'),
        'app_secret' => config('services.facebook.app_secret') ? 'SET (length: ' . strlen(config('services.facebook.app_secret')) . ')' : 'NOT SET',
        'access_token' => config('services.facebook.access_token') ? 'SET (length: ' . strlen(config('services.facebook.access_token')) . ')' : 'NOT SET',
    ];
    
    return response()->json([
        'config' => $config,
        'services_config_exists' => config('services.facebook') !== null,
    ]);
});

Route::get('/test-adset-minimal', function () {
    try {
        $fbService = new \App\Services\FacebookAdsService();
        $fbService->setAdAccount('act_1083199717260755');

        // ✅ Create Ad Set–Level Budget Campaign (no daily_budget here)
        $campaign = $fbService->createCampaign([
            'name' => 'Test Campaign ' . now()->format('His'),
            'objective' => 'OUTCOME_SALES',
            'status' => 'PAUSED',
            'buying_type' => 'AUCTION',
            'special_ad_categories' => ['CREDIT'], // valid: CREDIT, HOUSING, EMPLOYMENT, or NONE
            'is_adset_budget_sharing_enabled' => false, // ✅ tells Meta it's an adset-level budget campaign
        ]);

        if (!$campaign['success']) {
            return response()->json([
                'error' => 'Campaign failed',
                'details' => $campaign,
            ], 400);
        }

        // ✅ Now create Ad Set with its own daily budget
        $adset = $fbService->createAdSet($campaign['campaign_id'], [
            'name' => 'Test AdSet ' . now()->format('His'),
            'campaign_id' => $campaign['campaign_id'],
            'status' => 'PAUSED',
            'daily_budget' => 9000, // in cents — so 90 INR = 9000
            'billing_event' => 'IMPRESSIONS',
            'optimization_goal' => 'PURCHASE',
            'targeting' => [
                'geo_locations' => ['countries' => ['IN']],
                'age_min' => 18,
                'age_max' => 65,
            ],
            'start_time' => now()->addHours(2)->toIso8601String(),
        ]);

        return response()->json([
            'campaign' => $campaign,
            'adset' => $adset
        ], $adset['success'] ? 200 : 400);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => explode("\n", $e->getTraceAsString())
        ], 500);
    }
});


require __DIR__.'/auth.php';
