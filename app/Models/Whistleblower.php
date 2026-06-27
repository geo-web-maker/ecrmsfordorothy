<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Whistleblower extends Model
{
    protected $table = 'whistleblower';

    public $timestamps = false;

    protected $fillable = [
        'stuff_id',
        'first_name',
        'last_name',
        'phone_number',
        'registration_date',
        'is_anonymous',
    ];

    protected function casts(): array
    {
        return [
            'registration_date' => 'date',
            'is_anonymous'      => 'boolean',
        ];
    }

    public function stuff(): BelongsTo
    {
        return $this->belongsTo(Stuff::class, 'stuff_id', 'stuff_id');
    }
}
