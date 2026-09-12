<?php

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'user_id' => User::query()->inRandomOrder()->value('id')
                ?? User::factory(),
            'action' => $this->faker->randomElement([
                'create', 'update', 'delete', 'login', 'logout', 'view',
            ]),
            'entity_type' => $this->faker->randomElement([
                'equipment', 'asset', 'document', 'user',
            ]),
            'entity_id' => null,
            'description' => $this->faker->sentence(),
            'ip_address' => $this->faker->ipv4(),
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }
}