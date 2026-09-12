<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\AssetType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AssetFactory extends Factory
{
    protected $model = Asset::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'asset_code' => strtoupper($this->faker->unique()->bothify('AST-#####')),
            'asset_type_id' => AssetType::factory(),
            'manufacturer' => $this->faker->company(),
            'model' => strtoupper($this->faker->bothify('MDL-###??')),
            'serial_number' => strtoupper($this->faker->unique()->bothify('SN-########')),
            'status' => $this->faker->randomElement(['active', 'inactive', 'scrapped']),
            'description' => $this->faker->paragraph(),
        ];
    }
}