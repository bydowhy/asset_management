<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Equipment;
use App\Models\EquipmentAsset;
use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        // Hanya lokasi yang tidak punya anak (leaf / room)
        $leafLocations = Location::doesntHave('children')->get();

        if ($leafLocations->isEmpty()) {
            $this->command->warn('Tidak ada leaf location. Jalankan LocationSeeder dulu.');
            return;
        }

        foreach ($leafLocations as $location) {
            // 1-2 equipment per ruangan
            $count = rand(1, 2);

            for ($i = 0; $i < $count; $i++) {
                $equipment = Equipment::create([
                    'id' => (string) Str::uuid(),
                    'location_id' => $location->id,
                    'tag' => strtoupper('EQ-' . Str::random(6)),
                    'name' => 'Equipment at ' . $location->name,
                    'description' => 'Auto-generated equipment',
                    'equipment_type' => collect(['pump', 'motor', 'valve'])->random(),
                ]);

                // Attach 1-3 asset ke equipment ini via pivot
                $assets = Asset::query()->inRandomOrder()->limit(rand(1, 3))->get();

                foreach ($assets as $asset) {
                    EquipmentAsset::create([
                        'id' => (string) Str::uuid(),
                        'equipment_id' => $equipment->id,
                        'asset_id' => $asset->id,
                        'relationship_role' => collect(['component', 'main', 'backup'])->random(),
                        'installed_at' => now()->subDays(rand(30, 1000)),
                        'removed_at' => null,
                        'notes' => 'Seeded relation',
                    ]);
                }
            }
        }
    }
}