<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentTypeRequest;
use App\Models\DocumentType;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Services\AuditLogService;

class DocumentTypeController extends Controller
{
    public function __construct(
        protected AuditLogService $audit,
    ) {}

    public function index()
    {
        $types = DocumentType::withCount('documents')
            ->orderBy('name')
            ->get();

        return Inertia::render('DocumentTypes/Index', [
            'types' => $types,
        ]);
    }

    public function create()
    {
        return Inertia::render('DocumentTypes/Create');
    }

    public function store(StoreDocumentTypeRequest $request)
    {
        $data = $request->validated();
        $data['id'] = (string) Str::uuid();

        $documentType = DocumentType::create($data);

        $this->audit->log(
            'create',
            'document_type',
            $documentType->id,
            "Created document type {$documentType->code}"
        );

        return redirect()
            ->route('document-types.index')
            ->with('success', 'Document Type berhasil dibuat.');
    }

    public function edit(DocumentType $documentType)
    {
        return Inertia::render('DocumentTypes/Edit', [
            'type' => $documentType,
        ]);
    }

    public function update(StoreDocumentTypeRequest $request, DocumentType $documentType)
    {
        $documentType->update($request->validated());

        $this->audit->log(
            'update',
            'document_type',
            $documentType->id,
            "Updated document type {$documentType->code}"
        );

        return redirect()
            ->route('document-types.index')
            ->with('success', 'Document Type berhasil diperbarui.');
    }

    public function destroy(DocumentType $documentType)
    {
        if ($documentType->documents()->exists()) {
            return back()->withErrors([
                'delete' => 'Tidak bisa menghapus document type yang masih dipakai oleh dokumen.',
            ]);
        }

        $documentTypeId = $documentType->id;
        $documentTypeCode = $documentType->code;
        $documentTypeName = $documentType->name;

        $documentType->delete();

        $this->audit->log(
            'delete',
            'document_type',
            $documentTypeId,
            "Deleted document type {$documentTypeCode} ({$documentTypeName})"
        );

        return redirect()
            ->route('document-types.index')
            ->with('success', 'Document Type berhasil dihapus.');
    }
}