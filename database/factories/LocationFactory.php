<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'name' => $this->faker->company(),
            'code' => strtoupper($this->faker->unique()->bothify('LOC-###')),
            'description' => $this->faker->sentence(),
        ];
    }
}