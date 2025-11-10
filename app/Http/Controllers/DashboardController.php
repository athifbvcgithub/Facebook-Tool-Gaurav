<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $stats = [
            'totalCampaigns' => $this->getTotalCampaigns(),
            'activeAds' => $this->getActiveAds(),
            'totalSpend' => $this->getTotalSpend(),
            'totalImpressions' => $this->getTotalImpressions(),
            'recentActivity' => $this->getRecentActivity(),
        ];

        return view('dashboard.index', $stats);
    }

    private function getTotalCampaigns()
    {
        try {
            return DB::table('campaigns')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getActiveAds()
    {
        try {
            return DB::table('ads')
                ->where('status', 'ACTIVE')
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getTotalSpend()
    {
        try {
            return DB::table('campaigns')
                ->sum('spend') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getTotalImpressions()
    {
        try {
            return DB::table('ads')
                ->sum('impressions') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getRecentActivity()
    {
        try {
            // Get recent campaigns and adsets
            $campaigns = DB::table('campaigns')
                ->select('id', 'name', 'status', 'created_at')
                ->selectRaw("'Campaign' as type")
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            $adsets = DB::table('adsets')
                ->select('id', 'name', 'status', 'created_at')
                ->selectRaw("'AdSet' as type")
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Merge and sort
            return $campaigns->merge($adsets)
                ->sortByDesc('created_at')
                ->take(10)
                ->map(function ($item) {
                    $item->created_at = \Carbon\Carbon::parse($item->created_at);
                    return $item;
                });

        } catch (\Exception $e) {
            return collect([]);
        }
    }
}