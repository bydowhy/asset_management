<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetType extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['name', 'code', 'description'];

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    public function definitions(): HasMany
    {
        return $this->hasMany(AssetTypeDefinition::class)->orderBy('sort_order');
    }
}
