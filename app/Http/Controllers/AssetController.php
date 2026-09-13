<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssetRequest;
use App\Models\Asset;
use App\Models\AssetType;
use App\Models\RelationshipType;
use App\Services\AssetRelationshipService;
use App\Services\AssetService;
use App\Services\AssetSpecificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AssetController extends Controller
{
    public function __construct(
        protected AssetService $assetService,
        protected AssetRelationshipService $relationshipService,
        protected AssetSpecificationService $specService,
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
            'filters' => $request->only(['asset_type_id', 'status', 'manufacturer', 'search']),
        ]);
    }

    public function show(Asset $asset)
    {
        $asset = $this->assetService->getDetail($asset);

        return Inertia::render('Assets/Show', [
            'asset' => $asset,
            'currentRelationships' => $this->relationshipService->currentForAsset($asset),
            'relationshipHistory' => $this->relationshipService->historyForAsset($asset),
            'allAssets' => Asset::where('id', '!=', $asset->id)
                ->orderBy('asset_code')
                ->get(['id', 'asset_code', 'asset_type_id']),
            'relationshipTypes' => RelationshipType::orderBy('name')->get(['id', 'name', 'code']),
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

        // Simpan spesifikasi
        if (! empty($data['specifications'])) {
            $this->specService->sync($asset, $data['specifications']);
        }

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

        $asset->update([
            'asset_code' => $data['asset_code'],
            'asset_type_id' => $data['asset_type_id'],
            'manufacturer' => $data['manufacturer'] ?? null,
            'model' => $data['model'] ?? null,
            'serial_number' => $data['serial_number'] ?? null,
            'status' => $data['status'],
            'description' => $data['description'] ?? null,
        ]);

        // Sinkronisasi spesifikasi
        $this->specService->sync($asset, $data['specifications'] ?? []);

        return redirect()
            ->route('assets.show', $asset->id)
            ->with('success', 'Asset berhasil diperbarui.');
    }

    public function destroy(Asset $asset)
    {
        // Business rule: hanya asset tanpa instalasi aktif yang boleh dihapus
        $activeInstall = $asset->equipmentAssignments()->whereNull('removed_at')->exists();
        if ($activeInstall) {
            return back()->withErrors([
                'delete' => 'Tidak bisa menghapus asset yang masih terpasang di equipment.',
            ]);
        }

        $asset->delete();

        return redirect()
            ->route('assets.index')
            ->with('success', 'Asset berhasil dihapus.');
    }
}