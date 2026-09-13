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

class PhotoController extends Controller
{
    public function __construct(
        protected MediaService $media,
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

        return redirect()->route('photos.index')
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

        $this->media->deletePhoto($photo);

        return redirect()->route('photos.index')
            ->with('success', 'Foto berhasil dihapus.');
    }
}