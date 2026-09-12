<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Asset extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['asset_code', 'asset_type_id', 'manufacturer', 'model', 'serial_number', 'status', 'description'];

    public function assetType()
    {
        return $this->belongsTo(AssetType::class);
    }

    public function specifications(): HasMany
    {
        return $this->hasMany(AssetSpecification::class);
    }

    public function failures(): HasMany
    {
        return $this->hasMany(Failure::class);
    }

    public function equipmentAssets()
    {
        return $this->hasMany(EquipmentAsset::class);
    }

    public function equipment()
    {
        return $this->belongsToMany(Equipment::class, 'equipment_assets')
            ->withPivot(['id', 'relationship_role', 'installed_at', 'removed_at', 'notes']);
    }

    public function outgoingRelationships(): HasMany
    {
        return $this->hasMany(AssetRelationship::class, 'source_asset_id');
    }

    public function incomingRelationships(): HasMany
    {
        return $this->hasMany(AssetRelationship::class, 'target_asset_id');
    }

    public function documentLinks(): MorphMany
    {
        return $this->morphMany(DocumentLink::class, 'entity', 'entity_type', 'entity_id');
    }

    public function photoLinks(): MorphMany
    {
        return $this->morphMany(PhotoLink::class, 'entity', 'entity_type', 'entity_id');
    }
}
