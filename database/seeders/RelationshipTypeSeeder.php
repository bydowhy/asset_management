<?php

namespace Database\Seeders;

use App\Models\RelationshipType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RelationshipTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Drives',        'code' => 'DRIVES'],
            ['name' => 'Supplies',      'code' => 'SUPPLIES'],
            ['name' => 'Controls',      'code' => 'CONTROLS'],
            ['name' => 'Connected To',  'code' => 'CONNECTED_TO'],
            ['name' => 'Backup For',    'code' => 'BACKUP_FOR'],
        ];

        foreach ($types as $t) {
            RelationshipType::updateOrCreate(
                ['code' => $t['code']],
                [
                    'id' => (string) Str::uuid(),
                    'name' => $t['name'],
                    'description' => "Relationship: {$t['name']}",
                ]
            );
        }
    }
}