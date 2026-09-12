<?php

namespace Database\Factories;

use App\Models\Photo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PhotoFactory extends Factory
{
    protected $model = Photo::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'file_name' => Str::uuid() . '.jpg',
            'file_path' => 'photos/' . Str::uuid() . '.jpg',
            'file_size' => $this->faker->numberBetween(50_000, 3_000_000),
            'caption' => $this->faker->sentence(),
            'taken_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'uploaded_by' => User::query()->inRandomOrder()->value('id')
                ?? User::factory(),
        ];
    }
}