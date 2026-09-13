<?php

namespace App\Http\Controllers;

use App\Http\Requests\SyncAssetSpecificationsRequest;
use App\Models\Asset;
use App\Services\AssetSpecificationService;
use Illuminate\Http\RedirectResponse;

class AssetSpecificationController extends Controller
{
    public function __construct(
        protected AssetSpecificationService $service,
    ) {}

    public function sync(SyncAssetSpecificationsRequest $request, Asset $asset): RedirectResponse
    {
        $this->service->sync($asset, $request->validated()['specifications'] ?? []);

        return back()->with('success', 'Spesifikasi berhasil disimpan.');
    }
}