<?php

use App\Models\{ 
    Asset, 
    AssetRelationship, 
    AssetSpecification, 
    AssetType, 
    AssetTypeDefinition, 
    AuditLog, 
    Document, 
    DocumentLink, 
    DocumentType, 
    Equipment, 
    EquipmentAsset, 
    Failure, 
    Location, 
    Photo, 
    PhotoLink, 
    RelationshipType, 
    User };
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->seed(); // jalankan semua seeder
});

// ============================================================
// LOCATION (self-reference)
// ============================================================

it('has hierarchical locations', function () {
    $leaf = Location::doesntHave('children')->first();
    expect($leaf)->not->toBeNull()
        ->and($leaf->parent)->not->toBeNull()
        ->and($leaf->parent->parent)->not->toBeNull();
});

it('has equipment under locations', function () {
    $loc = Location::has('equipment')->first();
    expect($loc->equipment)->not->toBeEmpty();
});

// ============================================================
// EQUIPMENT ↔ LOCATION & ASSET
// ============================================================

it('equipment belongs to a location', function () {
    $eq = Equipment::first();
    expect($eq->location)->toBeInstanceOf(Location::class);
});

it('equipment has many assets via pivot', function () {
    $eq = Equipment::has('assets')->first();
    expect($eq->assets)->not->toBeEmpty();

    $pivot = $eq->assets->first()->pivot;
    expect($pivot->relationship_role)->not->toBeNull()
        ->and($pivot->installed_at)->not->toBeNull();
});

// ============================================================
// ASSET TYPE → DEFINITIONS & ASSETS
// ============================================================

it('asset type has definitions', function () {
    $type = AssetType::has('definitions')->first();
    expect($type->definitions)->not->toBeEmpty();
    expect($type->definitions->first())->toBeInstanceOf(AssetTypeDefinition::class);
});

it('asset type has assets', function () {
    $type = AssetType::has('assets')->first();
    expect($type->assets)->not->toBeEmpty();
});

// ============================================================
// ASSET → SPECIFICATIONS
// ============================================================

it('asset has specifications with definitions', function () {
    $asset = Asset::has('specifications')->first();
    expect($asset->specifications)->not->toBeEmpty();

    $spec = $asset->specifications->first();
    expect($spec)->toBeInstanceOf(AssetSpecification::class)
        ->and($spec->definition)->toBeInstanceOf(AssetTypeDefinition::class)
        ->and($spec->value)->not->toBeNull();
});

// ============================================================
// EQUIPMENT_ASSET PIVOT
// ============================================================

it('pivot links equipment and asset correctly', function () {
    $pivot = EquipmentAsset::first();
    expect($pivot->equipment)->toBeInstanceOf(Equipment::class)
        ->and($pivot->asset)->toBeInstanceOf(Asset::class);
});

it('pivot respects removed_at >= installed_at', function () {
    $bad = EquipmentAsset::whereNotNull('removed_at')
        ->whereColumn('removed_at', '<', 'installed_at')
        ->count();
    expect($bad)->toBe(0);
});

// ============================================================
// ASSET RELATIONSHIPS
// ============================================================

it('asset relationship links two assets', function () {
    $rel = AssetRelationship::with(['sourceAsset', 'targetAsset', 'relationshipType'])->first();

    expect($rel->sourceAsset)->toBeInstanceOf(Asset::class)
        ->and($rel->targetAsset)->toBeInstanceOf(Asset::class)
        ->and($rel->relationshipType)->toBeInstanceOf(RelationshipType::class);
});

it('asset relationship respects date range', function () {
    $bad = AssetRelationship::whereNotNull('valid_to')
        ->whereColumn('valid_to', '<', 'valid_from')
        ->count();
    expect($bad)->toBe(0);
});

// ============================================================
// FAILURES
// ============================================================

it('failure belongs to asset and creator', function () {
    $fail = Failure::with(['asset', 'createdBy'])->first();

    expect($fail->asset)->toBeInstanceOf(Asset::class)
        ->and($fail->createdBy)->toBeInstanceOf(User::class);
});

// ============================================================
// DOCUMENTS (polymorphic)
// ============================================================

it('document has type and uploader', function () {
    $doc = Document::with(['documentType', 'uploadedBy'])->first();

    expect($doc->documentType)->toBeInstanceOf(DocumentType::class)
        ->and($doc->uploadedBy)->toBeInstanceOf(User::class);
});

it('document link resolves polymorphic entity', function () {
    $link = DocumentLink::with('document')->first();

    expect($link->document)->toBeInstanceOf(Document::class);
    expect(['asset', 'equipment'])->toContain($link->entity_type);

    $model = $link->entity_type === 'asset' ? Asset::class : Equipment::class;
    expect($model::find($link->entity_id))->not->toBeNull();
});

// ============================================================
// PHOTOS (polymorphic)
// ============================================================

it('photo has uploader and linkable entity', function () {
    $photo = Photo::with('uploadedBy')->first();
    expect($photo->uploadedBy)->toBeInstanceOf(User::class);

    $link = PhotoLink::where('photo_id', $photo->id)->first();
    if ($link) {
        $model = $link->entity_type === 'asset' ? Asset::class : Equipment::class;
        expect($model::find($link->entity_id))->not->toBeNull();
    }
});

// ============================================================
// AUDIT LOG
// ============================================================

it('audit log belongs to user', function () {
    $log = AuditLog::with('user')->first();
    expect($log->user)->toBeInstanceOf(User::class)
        ->and($log->created_at)->not->toBeNull();
});

// ============================================================
// INTEGRITAS UMUM
// ============================================================

it('has no orphan records', function () {
    expect(Equipment::doesntHave('location')->count())->toBe(0);
    expect(Asset::doesntHave('assetType')->count())->toBe(0);
    expect(AssetSpecification::doesntHave('asset')->count())->toBe(0);
    expect(AssetSpecification::doesntHave('definition')->count())->toBe(0);
    expect(Failure::doesntHave('asset')->count())->toBe(0);
});

it('has no duplicate unique keys', function () {
    $dupes = DB::table('asset_specifications')
        ->select('asset_id', 'definition_id', DB::raw('COUNT(*) as c'))
        ->groupBy('asset_id', 'definition_id')
        ->having('c', '>', 1)
        ->get();
    expect($dupes)->toBeEmpty();
});