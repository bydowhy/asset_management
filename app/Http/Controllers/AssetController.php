<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssetRequest;
use App\Models\Asset;
use App\Models\AssetType;
use App\Models\Location;
use App\Models\RelationshipType;
use App\Services\AssetRelationshipService;
use App\Services\AssetService;
use App\Services\AssetSpecificationService;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AssetController extends Controller
{
    public function __construct(
        protected AssetService $assetService,
        protected AssetRelationshipService $relationshipService,
        protected AssetSpecificationService $specService,
        protected AuditLogService $audit,
    ) {}

    public function index(Request $request)
    {
        $assets = $this->assetService->paginate($request);
        $assetTypes = AssetType::orderBy('name')->get(['id', 'name', 'code']);
        $manufacturers = Asset::query()
            ->whereNotNull('manufacturer')
            ->distinct()
            ->orderBy('manufacturer')
            ->pluck('manufacturer');

        return Inertia::render('Assets/Index', [
            'assets' => $assets,
            'assetTypes' => $assetTypes,
            'manufacturers' => $manufacturers,
            'locations' => $this->flattenedLocationsWithAssetCounts(),
            'filters' => $request->only(['asset_type_id', 'status', 'manufacturer', 'search', 'location_id']),
        ]);
    }

    /**
     * Bangun daftar lokasi flat dengan indentasi + count asset yang
     * sedang terpasang di equipment pada lokasi tersebut (termasuk turunannya).
     */
    private function flattenedLocationsWithAssetCounts(): array
    {
        $all = Location::orderBy('name')->get();

        // Hitung asset yang SEDANG terpasang per lokasi (langsung)
        $directCounts = DB::table('equipment_assets')
            ->join('equipment', 'equipment.id', '=', 'equipment_assets.equipment_id')
            ->whereNull('equipment_assets.removed_at')
            ->selectRaw('equipment.location_id, count(*) as c')
            ->groupBy('equipment.location_id')
            ->pluck('c', 'location_id')
            ->toArray();

        $byParent = $all->groupBy('parent_id');
        $subtreeCounts = [];

        $computeSubtree = function ($node) use (&$computeSubtree, $byParent, $directCounts, &$subtreeCounts) {
            if (isset($subtreeCounts[$node->id])) {
                return $subtreeCounts[$node->id];
            }

            $total = $directCounts[$node->id] ?? 0;
            foreach ($byParent->get($node->id, collect()) as $child) {
                $total += $computeSubtree($child);
            }

            $subtreeCounts[$node->id] = $total;
            return $total;
        };

        foreach ($all as $node) {
            $computeSubtree($node);
        }

        $result = [];
        $walk = function ($parentId, $depth) use (&$walk, &$result, $byParent, $subtreeCounts) {
            foreach ($byParent->get($parentId, collect()) as $node) {
                if (($subtreeCounts[$node->id] ?? 0) === 0) {
                    continue;
                }

                $prefix = str_repeat('— ', $depth);
                $result[] = [
                    'id' => $node->id,
                    'label' => $prefix . $node->name . ' (' . $node->code . ')',
                    'count' => $subtreeCounts[$node->id],
                ];

                $walk($node->id, $depth + 1);
            }
        };

        $walk(null, 0);

        return $result;
    }

    public function show(Asset $asset)
    {
        $asset = $this->assetService->getDetail($asset);

        // Ambil dokumen terkait asset
        $documents = \App\Models\DocumentLink::with('document.type')
            ->where('entity_type', 'asset')
            ->where('entity_id', $asset->id)
            ->get()
            ->pluck('document');

        // Ambil foto terkait asset
        $photos = \App\Models\PhotoLink::with('photo')
            ->where('entity_type', 'asset')
            ->where('entity_id', $asset->id)
            ->get()
            ->pluck('photo');

        return Inertia::render('Assets/Show', [
            'asset' => $asset,
            'currentRelationships' => $this->relationshipService->currentForAsset($asset),
            'relationshipHistory' => $this->relationshipService->historyForAsset($asset),
            'allAssets' => Asset::where('id', '!=', $asset->id)
                ->orderBy('asset_code')
                ->get(['id', 'asset_code', 'asset_type_id']),
            'relationshipTypes' => RelationshipType::orderBy('name')->get(['id', 'name', 'code']),
            'documents' => $documents,
            'photos' => $photos,
        ]);
    }

    public function create()
    {
        $assetTypes = AssetType::with([
            'definitions' => fn ($q) => $q->orderBy('sort_order'),
        ])->orderBy('name')->get();

        return Inertia::render('Assets/Create', [
            'assetTypes' => $assetTypes,
        ]);
    }

    public function store(StoreAssetRequest $request)
    {
        $data = $request->validated();

        // Transaksi: kalau specService->sync() throw, Asset::create() ikut rollback
        $asset = DB::transaction(function () use ($data) {
            $asset = Asset::create([
                'id' => (string) Str::uuid(),
                'asset_code' => $data['asset_code'],
                'asset_type_id' => $data['asset_type_id'],
                'manufacturer' => $data['manufacturer'] ?? null,
                'model' => $data['model'] ?? null,
                'serial_number' => $data['serial_number'] ?? null,
                'status' => $data['status'],
                'description' => $data['description'] ?? null,
            ]);

            $this->specService->sync($asset, $data['specifications'] ?? []);

            return $asset;
        });

        $this->audit->log('create', 'asset', $asset->id, "Created asset {$asset->asset_code}");

        return redirect()
            ->route('assets.show', $asset->id)
            ->with('success', 'Asset berhasil dibuat.');
    }

    public function edit(Asset $asset)
    {
        $asset->load('specifications');

        $assetTypes = AssetType::with([
            'definitions' => fn ($q) => $q->orderBy('sort_order'),
        ])->orderBy('name')->get();

        return Inertia::render('Assets/Edit', [
            'asset' => $asset,
            'assetTypes' => $assetTypes,
        ]);
    }

    public function update(StoreAssetRequest $request, Asset $asset)
    {
        $data = $request->validated();

        DB::transaction(function () use ($asset, $data) {
            $asset->update([
                'asset_code' => $data['asset_code'],
                'asset_type_id' => $data['asset_type_id'],
                'manufacturer' => $data['manufacturer'] ?? null,
                'model' => $data['model'] ?? null,
                'serial_number' => $data['serial_number'] ?? null,
                'status' => $data['status'],
                'description' => $data['description'] ?? null,
            ]);

            $this->specService->sync($asset, $data['specifications'] ?? []);
        });

        $this->audit->log('update', 'asset', $asset->id, "Updated asset {$asset->asset_code}");

        return redirect()
            ->route('assets.show', $asset->id)
            ->with('success', 'Asset berhasil diperbarui.');
    }

    public function destroy(Asset $asset)
    {
        $activeInstall = $asset->equipmentAssignments()->whereNull('removed_at')->exists();
        if ($activeInstall) {
            return back()->with('error', 'Tidak bisa menghapus asset yang masih terpasang di equipment.');
        }

        $assetCode = $asset->asset_code;
        $assetId = $asset->id;

        $asset->delete();

        $this->audit->log('delete', 'asset', $assetId, "Deleted asset {$assetCode}");

        return redirect()
            ->route('assets.index')
            ->with('success', 'Asset berhasil dihapus.');
    }

    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));

        $query = Asset::with('assetType:id,name,code')
            ->where('status', '!=', 'scrapped'); // jangan tampilkan asset scrapped

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('asset_code', 'like', "%{$q}%")
                ->orWhere('manufacturer', 'like', "%{$q}%")
                ->orWhere('model', 'like', "%{$q}%")
                ->orWhere('serial_number', 'like', "%{$q}%");
            });
        }

        $results = $query->orderBy('asset_code')->limit(20)->get();

        return response()->json($results->map(fn ($a) => [
            'id' => $a->id,
            'asset_code' => $a->asset_code,
            'asset_type_name' => $a->assetType?->name,
            'manufacturer' => $a->manufacturer,
            'model' => $a->model,
            'status' => $a->status,
        ]));
    }
}