<?php

namespace Database\Factories;

use App\Models\AssetType;
use App\Models\AssetTypeDefinition;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AssetTypeDefinitionFactory extends Factory
{
    protected $model = AssetTypeDefinition::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'asset_type_id' => AssetType::factory(),
            'name' => $this->faker->words(2, true),
            'code' => $this->faker->unique()->slug(2, false),
            'data_type' => $this->faker->randomElement(['number', 'text', 'boolean', 'date']),
            'unit' => $this->faker->randomElement(['m', 'kg', 'kW', 'V', 'A', null]),
            'is_required' => $this->faker->boolean(60),
            'sort_order' => $this->faker->numberBetween(0, 10),
        ];
    }
}