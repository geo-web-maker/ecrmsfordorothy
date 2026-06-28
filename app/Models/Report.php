<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    protected $table = 'report';

    protected $primaryKey = 'report_id';

    /**
     * Only created_at is used (no updated_at per schema spec).
     */
    const UPDATED_AT = null;

    protected $fillable = [
        'stuff_id',
        'crime_id',
        'description',
        'location_address',
        'location_latitude',
        'location_longitude',
        'status',
        'priority',
        'tracking_code',
    ];

    protected function casts(): array
    {
        return [
            'location_latitude'  => 'decimal:8',
            'location_longitude' => 'decimal:8',
        ];
    }

    /**
     * Alias for views and routes that reference ->id.
     */
    public function getIdAttribute(): ?int
    {
        return $this->report_id;
    }

    public function stuff(): BelongsTo
    {
        return $this->belongsTo(Stuff::class, 'stuff_id', 'stuff_id');
    }

    public function user(): BelongsTo
    {
        return $this->stuff();
    }

    public function crime(): BelongsTo
    {
        return $this->belongsTo(Crime::class, 'crime_id', 'crime_id');
    }

    /**
     * Backward-compatible accessor for views referencing crime category name.
     */
    public function getCrimeCategoryAttribute(): ?object
    {
        if (! $this->relationLoaded('crime')) {
            $this->loadMissing('crime');
        }

        if (! $this->crime) {
            return null;
        }

        return (object) ['name' => $this->crime->category_name];
    }

    public function mapLatitude(): ?float
    {
        if ($this->location_latitude !== null) {
            return (float) $this->location_latitude;
        }

        if ($this->crime?->latitude !== null) {
            return (float) $this->crime->latitude;
        }

        return null;
    }

    public function mapLongitude(): ?float
    {
        if ($this->location_longitude !== null) {
            return (float) $this->location_longitude;
        }

        if ($this->crime?->longitude !== null) {
            return (float) $this->crime->longitude;
        }

        return null;
    }

    public function hasMapCoordinates(): bool
    {
        return $this->mapLatitude() !== null && $this->mapLongitude() !== null;
    }

    public function evidence(): HasMany
    {
        return $this->hasMany(Evidence::class, 'report_id', 'report_id');
    }

    public function caseAssignments(): HasMany
    {
        return $this->hasMany(Caseassignment::class, 'report_id', 'report_id');
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(Status::class, 'report_id', 'report_id')
            ->latest('changed_at');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'report_id', 'report_id');
    }
}
