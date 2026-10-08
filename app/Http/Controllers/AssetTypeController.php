<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssetTypeRequest;
use App\Models\AssetType;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Services\AuditLogService;

class AssetTypeController extends Controller
{
    public function __construct(
        protected AuditLogService $audit,
    ) {}

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

        $this->audit->log(
            'create', 
            'asset_type', 
            $assetType->id, 
            "Created asset type {$assetType->code}"
        );

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

        $this->audit->log(
            'update', 
            'asset_type', 
            $assetType->id, 
            "Updated asset type {$assetType->code}"
        );

        return redirect()
            ->route('asset-types.edit', $assetType->id)
            ->with('success', 'Asset Type berhasil diperbarui.');
    }

    public function destroy(AssetType $assetType)
    {
        if ($assetType->assets()->exists()) {
            return back()->with('error', 'Tidak bisa menghapus asset type yang masih dipakai oleh asset.');
        }

        $assetTypeId = $assetType->id;
        $assetTypeCode = $assetType->code;
        $assetTypeName = $assetType->name;

        $assetType->delete();

        $this->audit->log('delete', 'asset_type', $assetTypeId, "Deleted asset type {$assetTypeCode} ({$assetTypeName})");

        return redirect()
            ->route('asset-types.index')
            ->with('success', 'Asset Type berhasil dihapus.');
    }
}