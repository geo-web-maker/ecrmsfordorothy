<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Officer extends Model
{
    protected $table = 'officer';

    public $timestamps = false;

    protected $fillable = [
        'stuff_id',
        'full_name',
    ];

    public function stuff(): BelongsTo
    {
        return $this->belongsTo(Stuff::class, 'stuff_id', 'stuff_id');
    }

    public function caseAssignments(): HasMany
    {
        return $this->hasMany(Caseassignment::class, 'stuff_id', 'stuff_id');
    }
}
