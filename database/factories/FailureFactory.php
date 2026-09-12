<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\Failure;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FailureFactory extends Factory
{
    protected $model = Failure::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'asset_id' => Asset::factory(),
            'failure_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'failure_type' => $this->faker->randomElement([
                'mechanical', 'electrical', 'leak', 'overheating', 'vibration',
            ]),
            'symptom' => $this->faker->sentence(),
            'root_cause' => $this->faker->sentence(),
            'action_taken' => $this->faker->sentence(),
            'downtime_hours' => $this->faker->randomFloat(2, 0.5, 72),
            'description' => $this->faker->paragraph(),
            'created_by' => User::query()->inRandomOrder()->value('id')
                ?? User::factory(),
        ];
    }
}