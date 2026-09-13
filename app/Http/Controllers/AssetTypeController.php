<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssetTypeRequest;
use App\Models\AssetType;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AssetTypeController extends Controller
{
    public function index()
    {
        $assetTypes = AssetType::withCount(['assets', 'definitions'])
            ->orderBy('name')
            ->get();

        return Inertia::render('AssetTypes/Index', [
            'assetTypes' => $assetTypes,
        ]);
    }

    public function create()
    {
        return Inertia::render('AssetTypes/Create');
    }

    public function store(StoreAssetTypeRequest $request)
    {
        $data = $request->validated();
        $data['id'] = (string) Str::uuid();

        $assetType = AssetType::create($data);

        return redirect()
            ->route('asset-types.edit', $assetType->id)
            ->with('success', 'Asset Type berhasil dibuat. Tambahkan spesifikasi di bawah.');
    }

    public function edit(AssetType $assetType)
    {
        $assetType->load([
            'definitions' => fn ($q) => $q->orderBy('sort_order'),
        ]);

        return Inertia::render('AssetTypes/Edit', [
            'assetType' => $assetType,
        ]);
    }

    public function update(StoreAssetTypeRequest $request, AssetType $assetType)
    {
        $assetType->update($request->validated());

        return redirect()
            ->route('asset-types.edit', $assetType->id)
            ->with('success', 'Asset Type berhasil diperbarui.');
    }

    public function destroy(AssetType $assetType)
    {
        // Restrict: tolak jika masih ada asset yang memakai
        if ($assetType->assets()->exists()) {
            return back()->withErrors([
                'delete' => 'Tidak bisa menghapus asset type yang masih dipakai oleh asset.',
            ]);
        }

        // Definitions akan terhapus otomatis via ON DELETE CASCADE
        $assetType->delete();

        return redirect()
            ->route('asset-types.index')
            ->with('success', 'Asset Type berhasil dihapus.');
    }
}