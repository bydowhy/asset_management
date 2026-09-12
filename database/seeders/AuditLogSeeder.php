<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AuditLog;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AuditLogSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::pluck('id');

        if ($users->isEmpty()) {
            $this->command->warn('Butuh user untuk seeder ini.');
            return;
        }

        $entities = collect()
            ->merge(Equipment::pluck('id')->map(fn ($id) => ['equipment', $id]))
            ->merge(Asset::pluck('id')->map(fn ($id) => ['asset', $id]));

        for ($i = 0; $i < 100; $i++) {
            $entity = $entities->isNotEmpty() ? $entities->random() : [null, null];

            AuditLog::create([
                'id' => (string) Str::uuid(),
                'user_id' => $users->random(),
                'action' => collect(['create', 'update', 'delete', 'view', 'login'])->random(),
                'entity_type' => $entity[0] ?? 'system',
                'entity_id' => $entity[1],
                'description' => 'Seeded audit log entry',
                'ip_address' => rand(1, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255),
                'created_at' => now()->subDays(rand(1, 90)),
            ]);
        }
    }
}