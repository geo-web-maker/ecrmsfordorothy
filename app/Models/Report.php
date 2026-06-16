<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'crime_category_id',
    'description',
    'location_latitude',
    'location_longitude',
    'location_address',
    'status',
    'priority',
    'tracking_code',
])]
class Report extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function crimeCategory(): BelongsTo
    {
        return $this->belongsTo(CrimeCategory::class);
    }

    public function evidence(): HasMany
    {
        return $this->hasMany(Evidence::class);
    }

    public function caseAssignments(): HasMany
    {
        return $this->hasMany(CaseAssignment::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(StatusHistory::class);
    }

    public function crimeNotifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    protected function casts(): array
    {
        return [
            'location_latitude' => 'decimal:8',
            'location_longitude' => 'decimal:8',
        ];
    }
}
