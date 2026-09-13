<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssetRelationshipRequest;
use App\Http\Requests\EndAssetRelationshipRequest;
use App\Models\Asset;
use App\Models\AssetRelationship;
use App\Services\AssetRelationshipService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AssetRelationshipController extends Controller
{
    public function __construct(
        protected AssetRelationshipService $service,
    ) {}

    public function store(StoreAssetRelationshipRequest $request, Asset $asset): RedirectResponse
    {
        $this->service->create($asset, $request->validated());

        return back()->with('success', 'Relationship berhasil dibuat.');
    }

    public function end(EndAssetRelationshipRequest $request, AssetRelationship $relationship): RedirectResponse
    {
        $this->service->end($relationship, $request->validated()['valid_to']);

        return back()->with('success', 'Relationship berhasil ditutup.');
    }

    public function replace(Request $request, AssetRelationship $relationship): RedirectResponse
    {
        $data = $request->validate([
            'target_asset_id' => ['required', 'string', 'exists:assets,id'],
            'valid_from' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ]);

        $this->service->replace($relationship, $data);

        return back()->with('success', 'Relationship berhasil diganti.');
    }
}