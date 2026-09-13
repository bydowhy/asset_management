<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'username' => 'mirza',
                'name' => 'Mirza',
                'email' => 'mirza.alfarisi88@gmail.com',
                'role' => 'admin',
                'password' => Hash::make('rahasiaAllah'),
                'department' => 'Engineering',
                'email_verified_at' => now()
            ],
        );

        User::updateOrCreate(
            [
                'username' => 'mebel',
                'name' => 'Mebel',
                'email' => 'mejabelajar.mebel@gmail.com',
                'role' => 'guest',
                'password' => Hash::make('rahasiaAllah'),
                'department' => 'Marketing',
                'email_verified_at' => now()
            ],
        );
    }
}
