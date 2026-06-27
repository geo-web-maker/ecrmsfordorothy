<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Status extends Model
{
    protected $table = 'status';

    protected $primaryKey = 'status_id';

    public $timestamps = false;

    protected $fillable = [
        'report_id',
        'old_status',
        'new_status',
        'remarks',
        'changed_at',
        'changed_by',
    ];

    protected function casts(): array
    {
        return [
            'changed_at' => 'datetime',
        ];
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'report_id', 'report_id');
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(Stuff::class, 'changed_by', 'stuff_id');
    }

    /**
     * Alias for views that reference created_at.
     */
    public function getCreatedAtAttribute()
    {
        return $this->changed_at;
    }
}
