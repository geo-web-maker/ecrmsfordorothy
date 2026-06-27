<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Crime extends Model
{
    protected $table = 'crime';

    protected $primaryKey = 'crime_id';

    protected $fillable = [
        'category_name',
        'description',
        'location',
        'latitude',
        'longitude',
        'severity_level',
        'date_occurred',
        'status',
        'is_verified',
        'verified_by',
    ];

    protected function casts(): array
    {
        return [
            'latitude'      => 'decimal:8',
            'longitude'     => 'decimal:8',
            'date_occurred' => 'date',
            'is_verified'   => 'boolean',
        ];
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(Stuff::class, 'verified_by', 'stuff_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'crime_id', 'crime_id');
    }
}
