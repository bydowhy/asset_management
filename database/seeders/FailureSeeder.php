<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Failure;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FailureSeeder extends Seeder
{
    public function run(): void
    {
        $assets = Asset::all();
        $users = User::pluck('id');

        if ($assets->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Butuh asset & user untuk seeder ini.');
            return;
        }

        for ($i = 0; $i < 25; $i++) {
            Failure::create([
                'id' => (string) Str::uuid(),
                'asset_id' => $assets->random()->id,
                'failure_date' => now()->subDays(rand(1, 365)),
                'failure_type' => collect([
                    'mechanical', 'electrical', 'leak',
                    'overheating', 'vibration',
                ])->random(),
                'symptom' => 'Unusual noise / temperature rise',
                'root_cause' => 'Worn bearing / loose connection',
                'action_taken' => 'Replaced part / tightened bolt',
                'downtime_hours' => rand(1, 72) + (rand(0, 99) / 100),
                'description' => 'Seeded failure record',
                'created_by' => $users->random(),
            ]);
        }
    }
}