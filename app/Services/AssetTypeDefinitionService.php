<?php

namespace App\Services;

use App\Models\AssetType;
use App\Models\AssetTypeDefinition;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AssetTypeDefinitionService
{
    /**
     * Sinkronisasi definitions untuk satu asset type.
     * - Definition dengan id yang tidak ada di payload → dihapus.
     * - Definition dengan id yang ada → diupdate.
     * - Definition tanpa id → dibuat baru.
     */
    public function sync(AssetType $assetType, array $definitions): void
    {
        // Cek duplikasi code dalam payload
        $codes = array_column($definitions, 'code');
        $duplicates = array_diff_assoc($codes, array_unique($codes));

        if (! empty($duplicates)) {
            throw ValidationException::withMessages([
                'definitions' => 'Ada code yang duplikat: ' . implode(', ', array_unique($duplicates)),
            ]);
        }

        DB::transaction(function () use ($assetType, $definitions) {
            $existingIds = $assetType->definitions()->pluck('id')->all();
            $incomingIds = collect($definitions)
                ->pluck('id')
                ->filter()
                ->values()
                ->all();

            // 1. Hapus definitions yang tidak ada di payload
            $toDelete = array_diff($existingIds, $incomingIds);
            if (! empty($toDelete)) {
                AssetTypeDefinition::whereIn('id', $toDelete)->delete();
            }

            // 2. Update / create
            foreach ($definitions as $def) {
                $data = [
                    'asset_type_id' => $assetType->id,
                    'name' => $def['name'],
                    'code' => $def['code'],
                    'data_type' => $def['data_type'],
                    'unit' => $def['unit'] ?? null,
                    'is_required' => (bool) $def['is_required'],
                    'sort_order' => (int) $def['sort_order'],
                ];

                if (! empty($def['id'])) {
                    AssetTypeDefinition::where('id', $def['id'])
                        ->where('asset_type_id', $assetType->id)
                        ->update($data);
                } else {
                    AssetTypeDefinition::create(array_merge($data, [
                        'id' => (string) Str::uuid(),
                    ]));
                }
            }
        });
    }
}