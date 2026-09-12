<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Equipment;
use App\Models\Photo;
use App\Models\PhotoLink;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PhotoSeeder extends Seeder
{
    public function run(): void
    {
        $uploader = User::query()->inRandomOrder()->first();

        if (! $uploader) {
            $this->command->warn('Tidak ada user. Buat user dulu.');
            return;
        }

        $equipmentIds = Equipment::pluck('id');
        $assetIds = Asset::pluck('id');

        for ($i = 0; $i < 20; $i++) {
            $photo = Photo::create([
                'id' => (string) Str::uuid(),
                'file_name' => Str::uuid() . '.jpg',
                'file_path' => 'photos/' . Str::uuid() . '.jpg',
                'file_size' => rand(100_000, 2_000_000),
                'caption' => 'Seeded photo ' . ($i + 1),
                'taken_at' => now()->subDays(rand(1, 500)),
                'uploaded_by' => $uploader->id,
            ]);

            $links = collect();

            if ($equipmentIds->isNotEmpty() && rand(0, 1)) {
                $links->push(['equipment', $equipmentIds->random()]);
            }
            if ($assetIds->isNotEmpty() && rand(0, 1)) {
                $links->push(['asset', $assetIds->random()]);
            }

            if ($links->isEmpty() && $assetIds->isNotEmpty()) {
                $links->push(['asset', $assetIds->random()]);
            }

            foreach ($links as [$type, $id]) {
                PhotoLink::create([
                    'id' => (string) Str::uuid(),
                    'photo_id' => $photo->id,
                    'entity_type' => $type,
                    'entity_id' => $id,
                ]);
            }
        }
    }
}