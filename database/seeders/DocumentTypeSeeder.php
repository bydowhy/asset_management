<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DocumentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Manual Book',    'code' => 'MANUAL'],
            ['name' => 'Certificate',    'code' => 'CERT'],
            ['name' => 'Warranty',       'code' => 'WARRANTY'],
            ['name' => 'Inspection',     'code' => 'INSPECTION'],
            ['name' => 'Drawing',        'code' => 'DRAWING'],
        ];

        foreach ($types as $t) {
            DocumentType::updateOrCreate(
                ['code' => $t['code']],
                [
                    'id' => (string) Str::uuid(),
                    'name' => $t['name'],
                    'description' => "Document type: {$t['name']}",
                ]
            );
        }
    }
}