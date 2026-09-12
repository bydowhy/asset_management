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
                'password' => Hash::make('rahasiaAllah'),
                'role' => 'admin',
            ],
        );

        User::updateOrCreate(
            [
                'username' => 'mebel',
                'name' => 'Mebel',
                'email' => 'mejabelajar.mebel@gmail.com',
                'password' => Hash::make('rahasiaAllah'),
                'role' => 'guest',
            ],
        );
    }
}
