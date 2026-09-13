<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstallAssetRequest;
use App\Http\Requests\ReplaceAssetRequest;
use App\Http\Requests\RemoveAssetRequest;
use App\Models\Equipment;
use App\Models\EquipmentAsset;
use App\Services\AssetInstallationService;
use Illuminate\Http\RedirectResponse;

class EquipmentAssetController extends Controller
{
    public function __construct(
        protected AssetInstallationService $service,
    ) {}

    public function store(InstallAssetRequest $request, Equipment $equipment): RedirectResponse
    {
        $this->service->install($equipment, $request->validated());

        return back()->with('success', 'Asset berhasil dipasang.');
    }

    public function remove(RemoveAssetRequest $request, EquipmentAsset $assignment): RedirectResponse
    {
        $this->service->remove(
            $assignment,
            $request->validated()['removed_at'],
            $request->validated()['notes'] ?? null
        );

        return back()->with('success', 'Asset berhasil dilepas.');
    }

    public function replace(ReplaceAssetRequest $request, EquipmentAsset $assignment): RedirectResponse
    {
        $this->service->replace(
            $assignment->equipment,
            $assignment,
            $request->validated()
        );

        return back()->with('success', 'Asset berhasil diganti.');
    }
}