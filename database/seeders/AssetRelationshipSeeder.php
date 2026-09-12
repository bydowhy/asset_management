<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetRelationship;
use App\Models\RelationshipType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AssetRelationshipSeeder extends Seeder
{
    public function run(): void
    {
        $assets = Asset::all();
        $types = RelationshipType::all();

        if ($assets->count() < 2 || $types->isEmpty()) {
            $this->command->warn('Butuh minimal 2 asset & 1 relationship type.');
            return;
        }

        $count = 40;

        for ($i = 0; $i < $count; $i++) {
            // Pilih pasangan berbeda
            [$source, $target] = $assets->random(2)->values()->all();

            $validFrom = now()->subDays(rand(30, 700));

            AssetRelationship::create([
                'id' => (string) Str::uuid(),
                'source_asset_id' => $source->id,
                'target_asset_id' => $target->id,
                'relationship_type_id' => $types->random()->id,
                'valid_from' => $validFrom,
                'valid_to' => rand(0, 1) ? now()->subDays(rand(0, 30)) : null,
                'description' => 'Seeded relationship',
            ]);
        }
    }
}