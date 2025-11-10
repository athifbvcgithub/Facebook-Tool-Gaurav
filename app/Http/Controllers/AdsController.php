<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdsController extends Controller
{
    public function index()
    {
        // Fetch ads from database (if table exists)
        // For now, returning empty collection
        $ads = collect([]);
        
        // If you have ads table:
        // $ads = DB::table('ads')->orderBy('created_at', 'desc')->paginate(20);

        return view('dashboard.ads.index', compact('ads'));
    }

    public function launcher()
    {
        return view('dashboard.ads.launcher');
    }

    public function creatives()
    {
        return view('dashboard.ads.creatives');
    }

    public function launcherPresets()
    {
        return view('dashboard.ads.launcher-presets');
    }

    public function countryPresets()
    {
        return view('dashboard.ads.country-presets');
    }
}