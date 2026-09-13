<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRelationshipTypeRequest;
use App\Models\RelationshipType;
use Illuminate\Support\Str;
use Inertia\Inertia;

class RelationshipTypeController extends Controller
{
    public function index()
    {
        $types = RelationshipType::withCount('assetRelationships')
            ->orderBy('name')
            ->get();

        return Inertia::render('RelationshipTypes/Index', [
            'types' => $types,
        ]);
    }

    public function create()
    {
        return Inertia::render('RelationshipTypes/Create');
    }

    public function store(StoreRelationshipTypeRequest $request)
    {
        $data = $request->validated();
        $data['id'] = (string) Str::uuid();

        RelationshipType::create($data);

        return redirect()
            ->route('relationship-types.index')
            ->with('success', 'Relationship Type berhasil dibuat.');
    }

    public function edit(RelationshipType $relationshipType)
    {
        return Inertia::render('RelationshipTypes/Edit', [
            'type' => $relationshipType,
        ]);
    }

    public function update(StoreRelationshipTypeRequest $request, RelationshipType $relationshipType)
    {
        $relationshipType->update($request->validated());

        return redirect()
            ->route('relationship-types.index')
            ->with('success', 'Relationship Type berhasil diperbarui.');
    }

    public function destroy(RelationshipType $relationshipType)
    {
        if ($relationshipType->assetRelationships()->exists()) {
            return back()->withErrors([
                'delete' => 'Tidak bisa menghapus relationship type yang masih dipakai oleh asset relationship.',
            ]);
        }

        $relationshipType->delete();

        return redirect()
            ->route('relationship-types.index')
            ->with('success', 'Relationship Type berhasil dihapus.');
    }
}