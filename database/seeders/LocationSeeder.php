<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        // 3 gedung
        $buildings = ['Building A', 'Building B', 'Building C'];

        foreach ($buildings as $bIndex => $name) {
            $building = Location::create([
                'id' => (string) Str::uuid(),
                'name' => $name,
                'code' => 'BLD-' . chr(65 + $bIndex),
                'description' => "Main {$name}",
            ]);

            // 3 lantai per gedung
            for ($floor = 1; $floor <= 3; $floor++) {
                $floorLoc = Location::create([
                    'id' => (string) Str::uuid(),
                    'parent_id' => $building->id,
                    'name' => "Floor {$floor}",
                    'code' => $building->code . '-F' . $floor,
                    'description' => "{$name} Floor {$floor}",
                ]);

                // 2 ruangan per lantai
                for ($room = 1; $room <= 2; $room++) {
                    Location::create([
                        'id' => (string) Str::uuid(),
                        'parent_id' => $floorLoc->id,
                        'name' => "Room {$floor}0{$room}",
                        'code' => $floorLoc->code . '-R' . $room,
                        'description' => "{$floorLoc->name} Room {$room}",
                    ]);
                }
            }
        }
    }
}