<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Stuff extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     */
    protected $table = 'stuff';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'stuff_id';

    protected $fillable = [
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * Alias for views and forms that reference ->id.
     */
    public function getIdAttribute(): ?int
    {
        return $this->stuff_id;
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'Admin';
    }

    public function isOfficer(): bool
    {
        return $this->role === 'Officer';
    }

    public function isWhistleblower(): bool
    {
        return $this->role === 'Whistleblower';
    }

    public function isCitizen(): bool
    {
        return $this->isWhistleblower();
    }

    /**
     * Display name from the role-specific profile.
     */
    public function getNameAttribute(): string
    {
        if ($this->isWhistleblower()) {
            $profile = $this->whistleblowerProfile;

            if ($profile) {
                $name = trim("{$profile->first_name} {$profile->last_name}");

                if ($name !== '') {
                    return $name;
                }
            }
        }

        if ($this->isOfficer()) {
            $name = $this->officerProfile?->full_name;

            if (filled($name)) {
                return $name;
            }
        }

        if ($this->isAdmin()) {
            $name = $this->adminProfile?->full_name;

            if (filled($name)) {
                return $name;
            }
        }

        return (string) ($this->attributes['email'] ?? 'User');
    }

    /**
     * First letter of the display name for avatar badges.
     */
    public function getInitialAttribute(): string
    {
        $letter = mb_strtoupper(mb_substr(trim($this->name), 0, 1));

        return $letter !== '' ? $letter : '?';
    }

    // Profile relationships
    public function whistleblowerProfile(): HasOne
    {
        return $this->hasOne(Whistleblower::class, 'stuff_id', 'stuff_id');
    }

    public function adminProfile(): HasOne
    {
        return $this->hasOne(Admin::class, 'stuff_id', 'stuff_id');
    }

    public function officerProfile(): HasOne
    {
        return $this->hasOne(Officer::class, 'stuff_id', 'stuff_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'stuff_id', 'stuff_id');
    }

    public function caseAssignments(): HasMany
    {
        return $this->hasMany(Caseassignment::class, 'stuff_id', 'stuff_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'stuff_id', 'stuff_id');
    }

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'password'  => 'hashed',
            'is_active' => 'boolean',
        ];
    }
}
