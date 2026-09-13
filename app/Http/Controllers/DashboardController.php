<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Equipment;
use App\Models\Failure;
use App\Models\Photo;
use App\Models\Document;
use Carbon\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        // Stat cards
        $totalEquipment = Equipment::count();
        $totalAssets = Asset::count();
        $activeAssets = Asset::where('status', 'active')->count();
        $recentFailures = Failure::where('failure_date', '>=', $thirtyDaysAgo)->count();

        // Asset status breakdown
        $assetStatus = Asset::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Recent failures (5 terakhir)
        $latestFailures = Failure::with('asset')
            ->orderBy('failure_date', 'desc')
            ->limit(5)
            ->get();

        // Equipment attention (failure terbanyak 90 hari terakhir)
        $ninetyDaysAgo = Carbon::now()->subDays(90);
        $equipmentAttention = Failure::selectRaw('assets.id, assets.asset_code, assets.asset_type_id, count(*) as failure_count')
            ->join('assets', 'failures.asset_id', '=', 'assets.id')
            ->where('failure_date', '>=', $ninetyDaysAgo)
            ->groupBy('assets.id', 'assets.asset_code', 'assets.asset_type_id')
            ->orderByDesc('failure_count')
            ->limit(5)
            ->get();

        // Recent media
        $latestDocuments = Document::with('type')->orderBy('id', 'desc')->limit(3)->get();
        $latestPhotos = Photo::orderBy('taken_at', 'desc')->limit(3)->get();

        return Inertia::render('Dashboard', [
            'stats' => [
                'total_equipment' => $totalEquipment,
                'total_assets' => $totalAssets,
                'active_assets' => $activeAssets,
                'recent_failures' => $recentFailures,
            ],
            'assetStatus' => $assetStatus,
            'latestFailures' => $latestFailures,
            'equipmentAttention' => $equipmentAttention,
            'latestDocuments' => $latestDocuments,
            'latestPhotos' => $latestPhotos,
        ]);
    }
}