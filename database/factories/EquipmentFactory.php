<?php

namespace Database\Factories;

use App\Models\Equipment;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EquipmentFactory extends Factory
{
    protected $model = Equipment::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'location_id' => Location::factory(),
            'tag' => strtoupper($this->faker->unique()->bothify('EQ-####')),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'equipment_type' => $this->faker->randomElement(['pump', 'motor', 'valve', 'compressor', 'heat_exchanger']),
        ];
    }
}