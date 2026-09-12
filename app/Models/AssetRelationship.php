<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetRelationship extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['source_asset_id', 'target_asset_id', 'relationship_type_id', 'valid_from', 'valid_to', 'description'];

    protected function casts(): array
    {
        return ['valid_from' => 'datetime', 'valid_to' => 'datetime'];
    }

    public function sourceAsset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'source_asset_id');
    }

    public function targetAsset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'target_asset_id');
    }

    public function relationshipType(): BelongsTo
    {
        return $this->belongsTo(RelationshipType::class);
    }
}
