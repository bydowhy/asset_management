<?php

namespace App\Support;

use App\Models\Asset;
use App\Models\Equipment;
use Illuminate\Validation\ValidationException;

class EntityLinkResolver
{
    /**
     * Validasi & resolve entity polymorphic.
     * Mengembalikan [entity_type, entity_id] yang sudah dipastikan ada.
     */
    public function resolve(string $entityType, string $entityId): array
    {
        $exists = match ($entityType) {
            'equipment' => Equipment::where('id', $entityId)->exists(),
            'asset' => Asset::where('id', $entityId)->exists(),
            default => false,
        };

        if (! $exists) {
            throw ValidationException::withMessages([
                'entity_id' => "Entity {$entityType} dengan ID tersebut tidak ditemukan.",
            ]);
        }

        return [$entityType, $entityId];
    }
}