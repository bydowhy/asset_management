<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentTypeRequest;
use App\Models\DocumentType;
use Illuminate\Support\Str;
use Inertia\Inertia;

class DocumentTypeController extends Controller
{
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

        DocumentType::create($data);

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

        $documentType->delete();

        return redirect()
            ->route('document-types.index')
            ->with('success', 'Document Type berhasil dihapus.');
    }
}