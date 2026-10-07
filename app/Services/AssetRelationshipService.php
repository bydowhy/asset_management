<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\AssetRelationship;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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

    /**
     * Transfer semua relationship aktif dari $oldAsset ke $newAsset.
     *
     * Menangani DUA arah:
     *   1. Old asset sebagai SOURCE  →  new asset menggantikan posisi source
     *   2. Old asset sebagai TARGET  →  new asset menggantikan posisi target
     *
     * Untuk setiap relasi:
     *   - Relasi lama ditutup (valid_to = $at)
     *   - Relasi baru dibuat dengan new asset di posisi yang sama
     *   - Skip self-loop & duplikat
     */
    public function transferActiveFrom(Asset $oldAsset, Asset $newAsset, string $at): void
    {
        if ($oldAsset->id === $newAsset->id) {
            return;
        }

        // ============================================================
        // PART 1 — Old asset sebagai SOURCE
        // (mis. MTR-00120 CONNECTED_TO PMP-00120 → jadi MTR-00121 CONNECTED_TO PMP-00120)
        // ============================================================
        $asSource = AssetRelationship::where('source_asset_id', $oldAsset->id)
            ->whereNull('valid_to')
            ->get();

        foreach ($asSource as $rel) {
            $rel->update(['valid_to' => $at]);

            // Skip jika target = new asset (mencegah self-loop)
            if ($rel->target_asset_id === $newAsset->id) {
                continue;
            }

            // Skip jika sudah ada relasi identik yang aktif
            if ($this->hasActive($newAsset->id, $rel->target_asset_id, $rel->relationship_type_id)) {
                continue;
            }

            AssetRelationship::create([
                'id' => (string) Str::uuid(),
                'source_asset_id' => $newAsset->id,
                'target_asset_id' => $rel->target_asset_id,
                'relationship_type_id' => $rel->relationship_type_id,
                'valid_from' => $at,
                'valid_to' => null,
                'description' => "Auto-transferred from {$oldAsset->asset_code}",
            ]);
        }

        // ============================================================
        // PART 2 — Old asset sebagai TARGET
        // (mis. INV-00122 DRIVES MTR-00120 → jadi INV-00122 DRIVES MTR-00121)
        // ============================================================
        $asTarget = AssetRelationship::where('target_asset_id', $oldAsset->id)
            ->whereNull('valid_to')
            ->get();

        foreach ($asTarget as $rel) {
            $rel->update(['valid_to' => $at]);

            // Skip jika source = new asset (mencegah self-loop)
            if ($rel->source_asset_id === $newAsset->id) {
                continue;
            }

            // Skip jika sudah ada relasi identik yang aktif
            if ($this->hasActive($rel->source_asset_id, $newAsset->id, $rel->relationship_type_id)) {
                continue;
            }

            AssetRelationship::create([
                'id' => (string) Str::uuid(),
                'source_asset_id' => $rel->source_asset_id,
                'target_asset_id' => $newAsset->id,
                'relationship_type_id' => $rel->relationship_type_id,
                'valid_from' => $at,
                'valid_to' => null,
                'description' => "Auto-transferred from {$oldAsset->asset_code}",
            ]);
        }
    }

    private function hasActive(string $sourceId, string $targetId, string $typeId): bool
    {
        return AssetRelationship::where('source_asset_id', $sourceId)
            ->where('target_asset_id', $targetId)
            ->where('relationship_type_id', $typeId)
            ->whereNull('valid_to')
            ->exists();
    }
}