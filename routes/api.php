<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdLauncherController;

Route::prefix('ad-launcher')->group(function () {
    Route::get('/ad-accounts', [AdLauncherController::class, 'getAdAccountsByProvider']);
    Route::get('/pixels', [AdLauncherController::class, 'getPixelsByAdAccount']);
});