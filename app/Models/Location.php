<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['parent_id', 'name', 'code', 'description'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function equipment(): HasMany
    {
        return $this->hasMany(Equipment::class);
    }

    /**
     * Kumpulkan ID lokasi ini + semua turunannya (recursive).
     */
    public static function descendantIds(string $rootId): array
    {
        $ids = [$rootId];
        $queue = [$rootId];

        while (! empty($queue)) {
            $parentId = array_shift($queue);
            $children = static::where('parent_id', $parentId)->pluck('id')->all();

            foreach ($children as $childId) {
                $ids[] = $childId;
                $queue[] = $childId;
            }
        }

        return $ids;
    }
}
