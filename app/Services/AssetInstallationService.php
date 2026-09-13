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
    /**
     * Pasang asset ke equipment. Gagal jika asset sudah aktif terpasang di equipment lain.
     */
    public function install(Equipment $equipment, array $data): EquipmentAsset
    {
        $asset = Asset::findOrFail($data['asset_id']);

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
        // (opsional, bisa diaktifkan sesuai kebutuhan)
        $sameRoleActive = EquipmentAsset::where('equipment_id', $equipment->id)
            ->where('relationship_role', $data['relationship_role'])
            ->whereNull('removed_at')
            ->exists();

        if ($sameRoleActive) {
            throw ValidationException::withMessages([
                'relationship_role' => "Equipment sudah memiliki asset aktif dengan role '{$data['relationship_role']}'. Lakukan replace, bukan install.",
            ]);
        }

        return EquipmentAsset::create([
            'id' => (string) Str::uuid(),
            'equipment_id' => $equipment->id,
            'asset_id' => $asset->id,
            'relationship_role' => $data['relationship_role'],
            'installed_at' => $data['installed_at'],
            'removed_at' => null,
            'notes' => $data['notes'] ?? null,
        ]);
    }

    /**
     * Lepas asset dari equipment (set removed_at).
     */
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

        $assignment->update([
            'removed_at' => $removedAt,
            'notes' => $notes ?? $assignment->notes,
        ]);

        return $assignment->fresh();
    }

    /**
     * Ganti asset dalam satu equipment secara atomik:
     *  - Tutup assignment lama (set removed_at)
     *  - Buat assignment baru dengan role yang sama
     *  - Tutup relationship fungsional lama (jika ada)
     *  - Buat relationship fungsional baru (jika ditentukan)
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

            // Business rule: asset baru tidak boleh aktif di equipment lain
            $activeElsewhere = EquipmentAsset::where('asset_id', $newAsset->id)
                ->whereNull('removed_at')
                ->exists();

            if ($activeElsewhere) {
                throw ValidationException::withMessages([
                    'asset_id' => "Asset {$newAsset->asset_code} sedang aktif terpasang di equipment lain.",
                ]);
            }

            // 1. Tutup assignment lama
            $oldAssignment->update(['removed_at' => $data['replaced_at']]);

            // 2. Tutup relationship fungsional lama (jika ada)
            if ($data['close_relationships'] ?? true) {
                \App\Models\AssetRelationship::where('source_asset_id', $oldAssignment->asset_id)
                    ->whereNull('valid_to')
                    ->update(['valid_to' => $data['replaced_at']]);

                \App\Models\AssetRelationship::where('target_asset_id', $oldAssignment->asset_id)
                    ->whereNull('valid_to')
                    ->update(['valid_to' => $data['replaced_at']]);
            }

            // 3. Buat assignment baru
            $newAssignment = EquipmentAsset::create([
                'id' => (string) Str::uuid(),
                'equipment_id' => $equipment->id,
                'asset_id' => $newAsset->id,
                'relationship_role' => $oldAssignment->relationship_role,
                'installed_at' => $data['replaced_at'],
                'removed_at' => null,
                'notes' => $data['notes'] ?? "Menggantikan {$oldAssignment->asset->asset_code}",
            ]);

            // 4. Update status asset lama -> inactive (opsional, sesuai workflow)
            $oldAssignment->asset->update(['status' => 'inactive']);

            return $newAssignment;
        });
    }
}