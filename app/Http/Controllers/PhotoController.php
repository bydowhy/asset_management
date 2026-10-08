<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhotoRequest;
use App\Models\Asset;
use App\Models\Equipment;
use App\Models\Photo;
use App\Services\MediaService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Services\AuditLogService;

class PhotoController extends Controller
{
    public function __construct(
        protected MediaService $media,
        protected AuditLogService $audit,
    ) {}

    public function index()
    {
        $photos = Photo::with(['uploadedBy', 'links'])
            ->orderByDesc('taken_at')
            ->orderByDesc('id')
            ->paginate(24);

        return Inertia::render('Photos/Index', [
            'photos' => $photos,
        ]);
    }

    public function create(\Illuminate\Http\Request $request)
    {
        return Inertia::render('Photos/Create', [
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

    public function store(StorePhotoRequest $request)
    {
        $data = $request->validated();
        $photo = $this->media->storePhoto(
            $data,
            $request->file('file'),
            $data['entity_type'] ?? null,
            $data['entity_id'] ?? null,
        );

        $this->audit->log('create', 'photo', $photo->id, "Uploaded photo \"{$photo->file_name}\"");

        // Smart redirect: kalau upload dari entity page, kembali ke entity tersebut
        if (($data['entity_type'] ?? null) === 'equipment' && ! empty($data['entity_id'])) {
            return redirect()
                ->route('equipment.show', $data['entity_id'])
                ->with('success', 'Foto berhasil diunggah.');
        }

        if (($data['entity_type'] ?? null) === 'asset' && ! empty($data['entity_id'])) {
            return redirect()
                ->route('assets.show', $data['entity_id'])
                ->with('success', 'Foto berhasil diunggah.');
        }

        return redirect()
            ->route('photos.index')
            ->with('success', 'Foto berhasil diunggah.');
    }

    public function show(Photo $photo)
    {
        $photo->load(['uploadedBy', 'links']);

        return Inertia::render('Photos/Show', [
            'photo' => $photo,
        ]);
    }

    /**
     * Stream file untuk preview (inline, bukan attachment).
     */
    public function file(Photo $photo): StreamedResponse
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('local');

        abort_unless($disk->exists($photo->file_path), 404);

        return $disk->response($photo->file_path);
    }

    public function destroy(Photo $photo)
    {
        $user = Auth::user();

        if ($user && $user->role !== 'admin' && $photo->uploaded_by !== $user->id) {
            abort(403, 'Anda tidak berhak menghapus foto ini.');
        }

        $photoId = $photo->id;
        $photoName = $photo->file_name;

        $this->media->deletePhoto($photo);

        $this->audit->log(
            'delete',
            'photo',
            $photoId,
            "Deleted photo \"{$photoName}\""
        );

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}