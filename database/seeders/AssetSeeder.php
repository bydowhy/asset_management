<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetSpecification;
use App\Models\AssetType;
use App\Models\AssetTypeDefinition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        $types = AssetType::with('definitions')->get();

        if ($types->isEmpty()) {
            $this->command->warn('Tidak ada AssetType. Jalankan AssetTypeSeeder dulu.');
            return;
        }

        // Buat 30 asset tersebar merata antar tipe
        for ($i = 1; $i <= 30; $i++) {
            $type = $types->random();

            $asset = Asset::create([
                'id' => (string) Str::uuid(),
                'asset_code' => sprintf('AST-%05d', $i),
                'asset_type_id' => $type->id,
                'manufacturer' => collect(['Grundfos', 'Siemens', 'ABB', 'Schneider', 'Danfoss'])->random(),
                'model' => strtoupper('MDL-' . Str::random(5)),
                'serial_number' => strtoupper('SN-' . Str::random(10)),
                'status' => collect(['active', 'active', 'active', 'inactive', 'scrapped'])->random(),
                'description' => 'Seeded asset for ' . $type->name,
            ]);

            // Isi spesifikasi sesuai definisi tipe
            foreach ($type->definitions as $def) {
                AssetSpecification::create([
                    'id' => (string) Str::uuid(),
                    'asset_id' => $asset->id,
                    'definition_id' => $def->id,
                    'value' => $this->fakeValueFor($def),
                ]);
            }
        }
    }

    private function fakeValueFor(AssetTypeDefinition $def): string
    {
        return match ($def->data_type) {
            'number' => (string) rand(1, 1000),
            'boolean' => (string) rand(0, 1),
            'date' => now()->subDays(rand(1, 365))->toDateString(),
            default => collect(['Steel', 'Bronze', 'PVC', 'Cast Iron'])->random(),
        };
    }
}