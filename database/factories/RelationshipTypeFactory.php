<?php

namespace Database\Factories;

use App\Models\RelationshipType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RelationshipTypeFactory extends Factory
{
    protected $model = RelationshipType::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->word();

        return [
            'id' => (string) Str::uuid(),
            'name' => ucfirst($name),
            'code' => strtoupper(Str::slug($name, '_')),
            'description' => $this->faker->sentence(),
        ];
    }
}