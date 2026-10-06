<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRelationshipTypeRequest;
use App\Models\RelationshipType;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Services\AuditLogService;

class RelationshipTypeController extends Controller
{
    public function __construct(
        protected AuditLogService $audit,
    ) {}

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

        $relationshipType = RelationshipType::create($data);

        $this->audit->log(
            'create',
            'relationship_type',
            $relationshipType->id,
            "Created relationship type {$relationshipType->code} ({$relationshipType->name})"
        );

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

        $this->audit->log(
            'update',
            'relationship_type',
            $relationshipType->id,
            "Updated relationship type {$relationshipType->code} ({$relationshipType->name})"
        );

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

        $typeId = $relationshipType->id;
        $typeCode = $relationshipType->code;
        $typeName = $relationshipType->name;

        $relationshipType->delete();

        $this->audit->log(
            'delete',
            'relationship_type',
            $typeId,
            "Deleted relationship type {$typeCode} ({$typeName})"
        );

        return redirect()
            ->route('relationship-types.index')
            ->with('success', 'Relationship Type berhasil dihapus.');
    }
}