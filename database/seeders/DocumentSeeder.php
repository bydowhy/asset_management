<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Document;
use App\Models\DocumentLink;
use App\Models\DocumentType;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $uploader = User::query()->inRandomOrder()->first();

        if (! $uploader) {
            $this->command->warn('Tidak ada user. Buat user dulu (register / AdminSeeder).');
            return;
        }

        $documentTypes = DocumentType::all();
        $equipmentIds = Equipment::pluck('id');
        $assetIds = Asset::pluck('id');

        if ($documentTypes->isEmpty()) {
            $this->command->warn('Jalankan DocumentTypeSeeder dulu.');
            return;
        }

        for ($i = 0; $i < 20; $i++) {
            $doc = Document::create([
                'id' => (string) Str::uuid(),
                'name' => 'Document ' . ($i + 1) . '.pdf',
                'document_type_id' => $documentTypes->random()->id,
                'file_path' => 'documents/' . Str::uuid() . '.pdf',
                'file_size' => rand(50_000, 3_000_000),
                'description' => 'Seeded document',
                'uploaded_by' => $uploader->id,
            ]);

            // Link ke 1-2 entity (campuran equipment & asset)
            $links = collect();

            if ($equipmentIds->isNotEmpty() && rand(0, 1)) {
                $links->push(['equipment', $equipmentIds->random()]);
            }
            if ($assetIds->isNotEmpty() && rand(0, 1)) {
                $links->push(['asset', $assetIds->random()]);
            }

            // Minimal 1 link
            if ($links->isEmpty()) {
                if ($assetIds->isNotEmpty()) {
                    $links->push(['asset', $assetIds->random()]);
                } elseif ($equipmentIds->isNotEmpty()) {
                    $links->push(['equipment', $equipmentIds->random()]);
                }
            }

            foreach ($links as [$type, $id]) {
                DocumentLink::create([
                    'id' => (string) Str::uuid(),
                    'document_id' => $doc->id,
                    'entity_type' => $type,
                    'entity_id' => $id,
                ]);
            }
        }
    }
}