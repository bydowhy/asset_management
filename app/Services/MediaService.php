<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentLink;
use App\Models\Photo;
use App\Models\PhotoLink;
use App\Support\EntityLinkResolver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaService
{
    public function __construct(
        protected EntityLinkResolver $resolver,
    ) {}

    /* ==================== Documents ==================== */

    public function storeDocument(array $data, UploadedFile $file, ?string $entityType, ?string $entityId): Document
    {
        // Validasi entity jika diberikan
        if ($entityType && $entityId) {
            $this->resolver->resolve($entityType, $entityId);
        }

        return DB::transaction(function () use ($data, $file, $entityType, $entityId) {
            $id = (string) Str::uuid();
            $originalName = $file->getClientOriginalName();
            $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $originalName);
            $path = "documents/{$id}/{$safeName}";

            Storage::disk('local')->putFileAs("documents/{$id}", $file, $safeName);

            $document = Document::create([
                'id' => $id,
                'name' => $data['name'],
                'document_type_id' => $data['document_type_id'],
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'description' => $data['description'] ?? null,
                'uploaded_by' => Auth::id(),
            ]);

            if ($entityType && $entityId) {
                DocumentLink::create([
                    'id' => (string) Str::uuid(),
                    'document_id' => $document->id,
                    'entity_type' => $entityType,
                    'entity_id' => $entityId,
                ]);
            }

            return $document;
        });
    }

    public function deleteDocument(Document $document): void
    {
        DB::transaction(function () use ($document) {
            $folder = "documents/{$document->id}";
            if (Storage::disk('local')->exists($folder)) {
                Storage::disk('local')->deleteDirectory($folder);
            }
            // document_links akan cascade
            $document->delete();
        });
    }

    /* ==================== Photos ==================== */

    public function storePhoto(array $data, UploadedFile $file, ?string $entityType, ?string $entityId): Photo
    {
        if ($entityType && $entityId) {
            $this->resolver->resolve($entityType, $entityId);
        }

        return DB::transaction(function () use ($data, $file, $entityType, $entityId) {
            $id = (string) Str::uuid();
            $ext = $file->getClientOriginalExtension();
            $generatedName = "{$id}.{$ext}";
            $path = "photos/{$generatedName}";

            Storage::disk('local')->putFileAs('photos', $file, $generatedName);

            $photo = Photo::create([
                'id' => $id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'caption' => $data['caption'] ?? null,
                'taken_at' => $data['taken_at'] ?? null,
                'uploaded_by' => Auth::id(),
            ]);

            if ($entityType && $entityId) {
                PhotoLink::create([
                    'id' => (string) Str::uuid(),
                    'photo_id' => $photo->id,
                    'entity_type' => $entityType,
                    'entity_id' => $entityId,
                ]);
            }

            return $photo;
        });
    }

    public function deletePhoto(Photo $photo): void
    {
        DB::transaction(function () use ($photo) {
            if (Storage::disk('local')->exists($photo->file_path)) {
                Storage::disk('local')->delete($photo->file_path);
            }
            $photo->delete();
        });
    }
}