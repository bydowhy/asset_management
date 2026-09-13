<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\AssetRelationship;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AssetRelationshipService
{
    public function create(Asset $source, array $data): AssetRelationship
    {
        if ($source->id === $data['target_asset_id']) {
            throw ValidationException::withMessages([
                'target_asset_id' => 'Source dan target tidak boleh sama.',
            ]);
        }

        return AssetRelationship::create([
            'id' => (string) Str::uuid(),
            'source_asset_id' => $source->id,
            'target_asset_id' => $data['target_asset_id'],
            'relationship_type_id' => $data['relationship_type_id'],
            'valid_from' => $data['valid_from'],
            'valid_to' => null,
            'description' => $data['description'] ?? null,
        ]);
    }

    public function end(AssetRelationship $relationship, string $validTo): AssetRelationship
    {
        if ($validTo < $relationship->valid_from->toDateTimeString()) {
            throw ValidationException::withMessages([
                'valid_to' => 'Tanggal akhir tidak boleh lebih awal dari tanggal mulai.',
            ]);
        }

        $relationship->update(['valid_to' => $validTo]);

        return $relationship->fresh();
    }

    /**
     * Ganti relationship atomik: tutup yang lama + buat yang baru.
     */
    public function replace(AssetRelationship $old, array $data): AssetRelationship
    {
        return DB::transaction(function () use ($old, $data) {
            $this->end($old, $data['valid_from']);

            return AssetRelationship::create([
                'id' => (string) Str::uuid(),
                'source_asset_id' => $old->source_asset_id,
                'target_asset_id' => $data['target_asset_id'],
                'relationship_type_id' => $old->relationship_type_id,
                'valid_from' => $data['valid_from'],
                'valid_to' => null,
                'description' => $data['description'] ?? null,
            ]);
        });
    }

    public function currentForAsset(Asset $asset)
    {
        return AssetRelationship::with(['targetAsset.assetType', 'relationshipType'])
            ->where('source_asset_id', $asset->id)
            ->whereNull('valid_to')
            ->orderBy('valid_from', 'desc')
            ->get();
    }

    public function historyForAsset(Asset $asset)
    {
        return AssetRelationship::with(['targetAsset.assetType', 'relationshipType'])
            ->where('source_asset_id', $asset->id)
            ->orderBy('valid_from', 'desc')
            ->get();
    }
}