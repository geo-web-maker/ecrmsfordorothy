<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Caseassignment extends Model
{
    protected $table = 'caseassignment';

    protected $primaryKey = 'assignment_id';

    public $timestamps = false;

    protected $fillable = [
        'report_id',
        'stuff_id',
        'priority',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'report_id', 'report_id');
    }

    public function stuff(): BelongsTo
    {
        return $this->belongsTo(Stuff::class, 'stuff_id', 'stuff_id');
    }
}
