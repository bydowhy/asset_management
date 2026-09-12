<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Photo extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['file_name', 'file_path', 'file_size', 'caption', 'taken_at', 'uploaded_by'];

    protected function casts(): array
    {
        return ['file_size' => 'integer', 'taken_at' => 'datetime'];
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by')->withTrashed();
    }

    public function links(): HasMany
    {
        return $this->hasMany(PhotoLink::class);
    }
}
