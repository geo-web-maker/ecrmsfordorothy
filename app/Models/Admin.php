<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admin extends Model
{
    protected $table = 'admin';

    public $timestamps = false;

    protected $fillable = [
        'stuff_id',
        'full_name',
    ];

    public function stuff(): BelongsTo
    {
        return $this->belongsTo(Stuff::class, 'stuff_id', 'stuff_id');
    }
}
