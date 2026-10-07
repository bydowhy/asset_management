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
        $ninetyDaysAgo = Carbon::now()->subDays(90);

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

        // Equipment Attention (90d) — HANYA failure setelah instalasi terakhir asset aktif
        $equipmentAttention = Equipment::select('equipment.id', 'equipment.tag', 'equipment.name')
            ->selectRaw('COUNT(failures.id) as failure_count')
            ->join('equipment_assets', function ($join) {
                $join->on('equipment_assets.equipment_id', '=', 'equipment.id')
                    ->whereNull('equipment_assets.removed_at'); // hanya assignment aktif
            })
            ->join('assets', 'assets.id', '=', 'equipment_assets.asset_id')
            ->join('failures', function ($join) use ($ninetyDaysAgo) {
                $join->on('failures.asset_id', '=', 'assets.id')
                    ->where('failures.failure_date', '>=', $ninetyDaysAgo)
                    // KUNCI: failure harus terjadi setelah asset ini dipasang di equipment ini
                    ->whereColumn('failures.failure_date', '>=', 'equipment_assets.installed_at');
            })
            ->groupBy('equipment.id', 'equipment.tag', 'equipment.name')
            ->havingRaw('COUNT(failures.id) > 0')
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