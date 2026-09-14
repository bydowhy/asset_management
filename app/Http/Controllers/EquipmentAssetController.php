<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstallAssetRequest;
use App\Http\Requests\ReplaceAssetRequest;
use App\Http\Requests\RemoveAssetRequest;
use App\Models\Equipment;
use App\Models\EquipmentAsset;
use App\Services\AssetInstallationService;
use Illuminate\Http\RedirectResponse;
use App\Services\AuditLogService;

class EquipmentAssetController extends Controller
{
    public function __construct(
        protected AssetInstallationService $service,
        protected AuditLogService $audit,
    ) {}

    public function store(InstallAssetRequest $request, Equipment $equipment): RedirectResponse
    {
        $data = $request->validated();
        $assignment = $this->service->install($equipment, $data);

        // ✅ Audit SEBELUM return
        $this->audit->log(
            'install',
            'asset',
            $assignment->asset_id,
            "Installed asset on equipment {$equipment->tag} as {$data['relationship_role']}"
        );

        return back()->with('success', 'Asset berhasil dipasang.');
    }

    public function remove(RemoveAssetRequest $request, EquipmentAsset $assignment): RedirectResponse
    {
        $assetId = $assignment->asset_id;
        $equipmentTag = $assignment->equipment->tag;

        $this->service->remove(
            $assignment,
            $request->validated()['removed_at'],
            $request->validated()['notes'] ?? null
        );

        // ✅ Audit SEBELUM return
        $this->audit->log(
            'remove',
            'asset',
            $assetId,
            "Removed asset from equipment {$equipmentTag}"
        );

        return back()->with('success', 'Asset berhasil dilepas.');
    }

    public function replace(ReplaceAssetRequest $request, EquipmentAsset $assignment): RedirectResponse
    {
        $data = $request->validated();
        $equipment = $assignment->equipment;
        $oldAssetCode = $assignment->asset->asset_code;

        $newAssignment = $this->service->replace($equipment, $assignment, $data);

        // ✅ Audit SEBELUM return
        $this->audit->log(
            'replace',
            'asset',
            $newAssignment->asset_id,
            "Replaced {$oldAssetCode} with {$newAssignment->asset->asset_code} on equipment {$equipment->tag}"
        );

        return back()->with('success', 'Asset berhasil diganti.');
    }
}