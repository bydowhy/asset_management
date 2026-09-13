<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\AssetSpecification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AssetSpecificationService
{
    /**
     * Sinkronisasi seluruh spesifikasi asset dalam satu transaksi.
     * $values = [definition_id => value, ...]
     */
    public function sync(Asset $asset, array $values): void
    {
        $allowedDefinitionIds = $asset->assetType
            ->definitions()
            ->pluck('id')
            ->all();

        foreach (array_keys($values) as $definitionId) {
            if (! in_array($definitionId, $allowedDefinitionIds, true)) {
                throw ValidationException::withMessages([
                    'specifications' => "Definition {$definitionId} tidak milik asset type ini.",
                ]);
            }
        }

        // Cek required
        $required = $asset->assetType->definitions()
            ->where('is_required', true)
            ->pluck('id');

        foreach ($required as $defId) {
            if (! isset($values[$defId]) || $values[$defId] === '' || $values[$defId] === null) {
                $def = $asset->assetType->definitions()->find($defId);
                throw ValidationException::withMessages([
                    'specifications' => "Spesifikasi '{$def->name}' wajib diisi.",
                ]);
            }
        }

        DB::transaction(function () use ($asset, $values) {
            // Hapus yang tidak ada di payload
            $asset->specifications()
                ->whereNotIn('definition_id', array_keys($values))
                ->delete();

            foreach ($values as $definitionId => $value) {
                if ($value === '' || $value === null) {
                    // Skip optional yang kosong
                    $asset->specifications()->where('definition_id', $definitionId)->delete();
                    continue;
                }

                AssetSpecification::updateOrCreate(
                    ['asset_id' => $asset->id, 'definition_id' => $definitionId],
                    ['value' => (string) $value]
                );
            }
        });
    }
}