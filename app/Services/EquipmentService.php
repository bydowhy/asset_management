<?php

namespace App\Services;

use App\Models\Equipment;

class EquipmentService
{
    public function getCurrentAssets(Equipment $equipment)
    {
        return $equipment->assets()
            ->wherePivotNull('removed_at')
            ->with('assetType')
            ->orderBy('equipment_assets.relationship_role')
            ->get();
    }

    public function getAssetHistory(Equipment $equipment, ?string $role = null)
    {
        $query = $equipment->assets()->with('assetType');

        if ($role) {
            $query->wherePivot('relationship_role', $role);
        }

        return $query->orderBy('equipment_assets.installed_at', 'desc')->get();
    }
}