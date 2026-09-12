<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\AssetRelationship;
use App\Models\RelationshipType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AssetRelationshipFactory extends Factory
{
    protected $model = AssetRelationship::class;

    public function definition(): array
    {
        $validFrom = $this->faker->dateTimeBetween('-2 years', 'now');

        return [
            'id' => (string) Str::uuid(),
            'source_asset_id' => Asset::factory(),
            'target_asset_id' => Asset::factory(),
            'relationship_type_id' => RelationshipType::factory(),
            'valid_from' => $validFrom,
            'valid_to' => $this->faker->boolean(30)
                ? $this->faker->dateTimeBetween($validFrom, 'now')
                : null,
            'description' => $this->faker->sentence(),
        ];
    }
}