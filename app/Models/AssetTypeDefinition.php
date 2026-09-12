<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetTypeDefinition extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['asset_type_id', 'name', 'code', 'data_type', 'unit', 'is_required', 'sort_order'];

    protected function casts(): array
    {
        return ['is_required' => 'boolean', 'sort_order' => 'integer'];
    }

    public function assetType(): BelongsTo
    {
        return $this->belongsTo(AssetType::class);
    }

    public function specifications(): HasMany
    {
        return $this->hasMany(AssetSpecification::class, 'definition_id');
    }
}
