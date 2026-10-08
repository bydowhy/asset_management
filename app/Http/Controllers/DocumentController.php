<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequest;
use App\Models\Asset;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Equipment;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Services\AuditLogService;

class DocumentController extends Controller
{
    public function __construct(
        protected MediaService $media,
        protected AuditLogService $audit,
    ) {}

    public function index(Request $request)
    {
        $query = Document::with(['type', 'uploadedBy', 'links']);

        if ($request->filled('document_type_id')) {
            $query->where('document_type_id', $request->document_type_id);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $documents = $query->orderByDesc('id')->paginate(20)->withQueryString();

        return Inertia::render('Documents/Index', [
            'documents' => $documents,
            'documentTypes' => DocumentType::orderBy('name')->get(['id', 'name', 'code']),
            'filters' => $request->only(['document_type_id', 'search']),
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('Documents/Create', [
            'documentTypes' => DocumentType::orderBy('name')->get(['id', 'name', 'code']),
            'equipmentList' => Equipment::orderBy('tag')->get(['id', 'tag', 'name'])
                ->map(fn ($e) => ['id' => $e->id, 'label' => "{$e->tag} — {$e->name}"]),
            'assetList' => Asset::orderBy('asset_code')->get(['id', 'asset_code'])
                ->map(fn ($a) => ['id' => $a->id, 'label' => $a->asset_code]),
            'preselected' => [
                'entity_type' => $request->query('entity_type'),
                'entity_id' => $request->query('entity_id'),
            ],
        ]);
    }

    public function store(StoreDocumentRequest $request)
    {
        $data = $request->validated();
        $document = $this->media->storeDocument(
            $data,
            $request->file('file'),
            $data['entity_type'] ?? null,
            $data['entity_id'] ?? null,
        );

        $this->audit->log('create', 'document', $document->id, "Uploaded document \"{$document->name}\"");

        // Smart redirect: kalau upload dari entity page, kembali ke entity tersebut
        if (($data['entity_type'] ?? null) === 'equipment' && ! empty($data['entity_id'])) {
            return redirect()
                ->route('equipment.show', $data['entity_id'])
                ->with('success', "Dokumen \"{$document->name}\" berhasil diunggah.");
        }

        if (($data['entity_type'] ?? null) === 'asset' && ! empty($data['entity_id'])) {
            return redirect()
                ->route('assets.show', $data['entity_id'])
                ->with('success', "Dokumen \"{$document->name}\" berhasil diunggah.");
        }

        return redirect()
            ->route('documents.index')
            ->with('success', "Dokumen \"{$document->name}\" berhasil diunggah.");
    }

    public function show(Document $document)
    {
        $document->load(['type', 'uploadedBy', 'links']);

        return Inertia::render('Documents/Show', [
            'document' => $document,
        ]);
    }

    public function download(Document $document): StreamedResponse
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('local');

        abort_unless($disk->exists($document->file_path), 404);

        return $disk->download($document->file_path, $document->name);
    }

    public function destroy(Document $document)
    {
        $user = Auth::user();

        if ($user && $user->role !== 'admin' && $document->uploaded_by !== $user->id) {
            abort(403, 'Anda tidak berhak menghapus dokumen ini.');
        }

        $documentId = $document->id;
        $documentName = $document->name;

        $this->media->deleteDocument($document);

        $this->audit->log(
            'delete',
            'document',
            $documentId,
            "Deleted document \"{$documentName}\""
        );

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }
}