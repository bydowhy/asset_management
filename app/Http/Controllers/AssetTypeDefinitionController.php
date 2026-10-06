<?php

namespace App\Http\Controllers;

use App\Http\Requests\SyncAssetTypeDefinitionsRequest;
use App\Models\AssetType;
use App\Services\AssetTypeDefinitionService;
use Illuminate\Http\RedirectResponse;
use App\Services\AuditLogService;

class AssetTypeDefinitionController extends Controller
{
    public function __construct(
        protected AssetTypeDefinitionService $service,
        protected AuditLogService $audit,
    ) {}

    public function sync(SyncAssetTypeDefinitionsRequest $request, AssetType $assetType): RedirectResponse
    {
        $definitions = $request->validated()['definitions'] ?? [];

        // Hitung ringkasan aksi untuk log
        $createCount = 0;
        $updateCount = 0;
        foreach ($definitions as $def) {
            if (empty($def['id'])) {
                $createCount++;
            } else {
                $updateCount++;
            }
        }

        // Simpan jumlah definition sebelum sync untuk hitung deletion
        $beforeCount = $assetType->definitions()->count();

        $this->service->sync($assetType, $definitions);

        $afterCount = $assetType->definitions()->count();
        $deleteCount = max(0, $beforeCount + $createCount - $afterCount);

        $this->audit->log(
            'update',
            'asset_type',
            $assetType->id,
            "Synced specifications for asset type '{$assetType->code}': "
                . "{$createCount} created, {$updateCount} updated, {$deleteCount} deleted"
        );

        return back()->with('success', 'Specifications berhasil disimpan.');
    }
}