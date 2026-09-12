<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetSpecification extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['asset_id', 'definition_id', 'value'];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function definition(): BelongsTo
    {
        return $this->belongsTo(AssetTypeDefinition::class, 'definition_id');
    }
}
