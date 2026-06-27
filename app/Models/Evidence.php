<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evidence extends Model
{
    protected $table = 'evidence';

    protected $primaryKey = 'evidence_id';

    /**
     * Only updated_at per schema spec (no created_at).
     */
    const CREATED_AT = null;

    protected $fillable = [
        'report_id',
        'file_name',
        'file_path',
        'file_type',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'report_id', 'report_id');
    }
}
