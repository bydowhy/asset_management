<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'name' => $this->faker->words(3, true) . '.pdf',
            'document_type_id' => DocumentType::factory(),
            'file_path' => 'documents/' . Str::uuid() . '.pdf',
            'file_size' => $this->faker->numberBetween(10_000, 5_000_000),
            'description' => $this->faker->sentence(),
            'uploaded_by' => User::query()->inRandomOrder()->value('id')
                ?? User::factory(),
        ];
    }
}