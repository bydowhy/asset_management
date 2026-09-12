<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Equipment extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['location_id', 'tag', 'name', 'description', 'equipment_type'];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function assets()
    {
        return $this->belongsToMany(Asset::class, 'equipment_assets')
            ->withPivot(['id', 'relationship_role', 'installed_at', 'removed_at', 'notes']);
    }

    public function equipmentAssets()
    {
        return $this->hasMany(EquipmentAsset::class);
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
