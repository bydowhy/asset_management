<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetType;
use App\Models\RelationshipType;
use App\Services\AssetService;
use App\Services\AssetRelationshipService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AssetController extends Controller
{
    public function __construct(
        protected AssetService $assetService,
        protected AssetRelationshipService $relationshipService,
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
}