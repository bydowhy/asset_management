<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Failure extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['asset_id', 'failure_date', 'failure_type', 'symptom', 'root_cause', 'action_taken', 'downtime_hours', 'description', 'created_by'];

    protected function casts(): array
    {
        return ['failure_date' => 'datetime', 'downtime_hours' => 'decimal:2'];
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }
}
