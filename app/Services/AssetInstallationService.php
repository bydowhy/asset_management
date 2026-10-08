<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Equipment;
use App\Models\EquipmentAsset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AssetInstallationService
{
    public function __construct(
        protected AssetRelationshipService $relationships,
    ) {}

    /**
     * Pasang asset ke equipment.
     * - Blokir jika asset scrapped
     * - Set status asset → active
     */
    public function install(Equipment $equipment, array $data): EquipmentAsset
    {
        $asset = Asset::findOrFail($data['asset_id']);

        $this->ensureNotScrapped($asset);

        // Business rule: satu asset tidak boleh aktif di dua equipment sekaligus
        $activeElsewhere = EquipmentAsset::where('asset_id', $asset->id)
            ->whereNull('removed_at')
            ->where('equipment_id', '!=', $equipment->id)
            ->exists();

        if ($activeElsewhere) {
            throw ValidationException::withMessages([
                'asset_id' => "Asset {$asset->asset_code} masih terpasang di equipment lain. Lepas terlebih dahulu.",
            ]);
        }

        // Business rule: satu equipment tidak boleh punya dua asset aktif dengan role sama
        $sameRoleActive = EquipmentAsset::where('equipment_id', $equipment->id)
            ->where('relationship_role', $data['relationship_role'])
            ->whereNull('removed_at')
            ->exists();

        if ($sameRoleActive) {
            throw ValidationException::withMessages([
                'relationship_role' => "Equipment sudah memiliki asset aktif dengan role '{$data['relationship_role']}'. Lakukan replace, bukan install.",
            ]);
        }

        return DB::transaction(function () use ($equipment, $asset, $data) {
            $assignment = EquipmentAsset::create([
                'id' => (string) Str::uuid(),
                'equipment_id' => $equipment->id,
                'asset_id' => $asset->id,
                'relationship_role' => $data['relationship_role'],
                'installed_at' => $data['installed_at'],
                'removed_at' => null,
                'notes' => $data['notes'] ?? null,
            ]);

            // Auto-set status asset menjadi active
            $asset->update(['status' => 'active']);

            return $assignment;
        });
    }

    public function remove(EquipmentAsset $assignment, string $removedAt, ?string $notes = null): EquipmentAsset
    {
        if ($assignment->removed_at) {
            throw ValidationException::withMessages([
                'removed_at' => 'Asset ini sudah dilepas sebelumnya.',
            ]);
        }

        if ($removedAt < $assignment->installed_at->toDateTimeString()) {
            throw ValidationException::withMessages([
                'removed_at' => 'Tanggal lepas tidak boleh lebih awal dari tanggal pasang.',
            ]);
        }

        return DB::transaction(function () use ($assignment, $removedAt, $notes) {
            $asset = $assignment->asset;

            // 1. Tutup assignment
            $assignment->update([
                'removed_at' => $removedAt,
                'notes' => $notes ?? $assignment->notes,
            ]);

            // 2. Set status asset → inactive
            $asset->update(['status' => 'inactive']);

            // 3. Auto-close semua relationship aktif asset ini
            $this->relationships->closeAllActiveFor($asset, $removedAt);

            return $assignment->fresh();
        });
    }

    /**
     * Ganti asset dalam satu equipment secara atomik:
     *  - Tutup assignment lama (set removed_at)
     *  - Set status asset lama → inactive
     *  - Auto-transfer semua relationship fungsional ke asset baru
     *  - Buat assignment baru dengan role yang sama
     *  - Set status asset baru → active
     */
    public function replace(Equipment $equipment, EquipmentAsset $oldAssignment, array $data): EquipmentAsset
    {
        return DB::transaction(function () use ($equipment, $oldAssignment, $data) {
            if ($oldAssignment->removed_at) {
                throw ValidationException::withMessages([
                    'asset_id' => 'Assignment lama sudah dilepas. Gunakan install, bukan replace.',
                ]);
            }

            $newAsset = Asset::findOrFail($data['asset_id']);

            $this->ensureNotScrapped($newAsset);

            // Business rule: asset baru tidak boleh aktif di equipment lain
            $activeElsewhere = EquipmentAsset::where('asset_id', $newAsset->id)
                ->whereNull('removed_at')
                ->exists();

            if ($activeElsewhere) {
                throw ValidationException::withMessages([
                    'asset_id' => "Asset {$newAsset->asset_code} sedang aktif terpasang di equipment lain.",
                ]);
            }

            $oldAsset = $oldAssignment->asset;

            // 1. Tutup assignment lama
            $oldAssignment->update(['removed_at' => $data['replaced_at']]);

            // 2. Set status asset lama → inactive
            $oldAsset->update(['status' => 'inactive']);

            // 3. Auto-transfer relationship aktif dari asset lama → asset baru
            $this->relationships->transferActiveFrom(
                $oldAsset,
                $newAsset,
                $data['replaced_at']
            );

            // 4. Buat assignment baru
            $newAssignment = EquipmentAsset::create([
                'id' => (string) Str::uuid(),
                'equipment_id' => $equipment->id,
                'asset_id' => $newAsset->id,
                'relationship_role' => $oldAssignment->relationship_role,
                'installed_at' => $data['replaced_at'],
                'removed_at' => null,
                'notes' => $data['notes'] ?? "Menggantikan {$oldAsset->asset_code}",
            ]);

            // 5. Set status asset baru → active
            $newAsset->update(['status' => 'active']);

            return $newAssignment;
        });
    }

    /**
     * Pastikan asset tidak dalam status scrapped.
     */
    private function ensureNotScrapped(Asset $asset): void
    {
        if ($asset->status === 'scrapped') {
            throw ValidationException::withMessages([
                'asset_id' => "Asset {$asset->asset_code} sudah berstatus scrapped dan tidak bisa dipasang.",
            ]);
        }
    }
}