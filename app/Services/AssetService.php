<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Location;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class AssetService
{
    public function paginate(Request $request): LengthAwarePaginator
    {
        $query = Asset::with('assetType');

        // Filter by location (recursive)
        if ($request->filled('location_id')) {
            $locationIds = Location::descendantIds($request->location_id);

            $query->whereHas('equipmentAssignments', function ($q) use ($locationIds) {
                $q->whereNull('removed_at')
                ->whereHas('equipment', function ($eq) use ($locationIds) {
                    $eq->whereIn('location_id', $locationIds);
                });
            });
        }

        if ($request->filled('asset_type_id')) {
            $query->where('asset_type_id', $request->asset_type_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('manufacturer')) {
            $query->where('manufacturer', 'like', '%' . $request->manufacturer . '%');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('asset_code', 'like', "%{$search}%")
                ->orWhere('serial_number', 'like', "%{$search}%")
                ->orWhere('model', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('asset_code')->paginate(20)->withQueryString();
    }

    public function getDetail(Asset $asset): Asset
    {
        return $asset->load([
            'assetType.definitions' => fn ($q) => $q->orderBy('sort_order'),
            'specifications.definition',
            'equipmentAssignments.equipment.location',
            'outgoingRelationships.targetAsset.assetType',
            'outgoingRelationships.relationshipType',
            'incomingRelationships.sourceAsset.assetType',
            'incomingRelationships.relationshipType',
            'failures' => fn ($q) => $q->orderByDesc('failure_date'),
        ]);
    }
}