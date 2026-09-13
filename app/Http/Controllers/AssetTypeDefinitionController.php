<?php

namespace App\Http\Controllers;

use App\Http\Requests\SyncAssetTypeDefinitionsRequest;
use App\Models\AssetType;
use App\Services\AssetTypeDefinitionService;
use Illuminate\Http\RedirectResponse;

class AssetTypeDefinitionController extends Controller
{
    public function __construct(
        protected AssetTypeDefinitionService $service,
    ) {}

    public function sync(SyncAssetTypeDefinitionsRequest $request, AssetType $assetType): RedirectResponse
    {
        $this->service->sync($assetType, $request->validated()['definitions'] ?? []);

        return back()->with('success', 'Specifications berhasil disimpan.');
    }
}