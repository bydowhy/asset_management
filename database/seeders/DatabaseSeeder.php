<?php

namespace Database\Seeders;

// use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
             // 1. Master data (tidak butuh yang lain)
            AssetTypeSeeder::class,
            RelationshipTypeSeeder::class,
            DocumentTypeSeeder::class,
            LocationSeeder::class,

            // 2. Butuh master data
            AssetSeeder::class,
            EquipmentSeeder::class,        // butuh Asset + Location

            // 3. Butuh asset & type
            AssetRelationshipSeeder::class,

            // 4. Butuh equipment & asset untuk link polymorphic
            DocumentSeeder::class,
            PhotoSeeder::class,

            // 5. Butuh asset & user
            FailureSeeder::class,
            AuditLogSeeder::class,
        ]);
    }
}
