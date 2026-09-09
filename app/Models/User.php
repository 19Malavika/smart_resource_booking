<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'employee_id',
        'role',
        'department_id',
        'designation',
        'joining_date',
        'manager_id',
        'profile_image',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'joining_date' => 'date',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Role Helpers
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isResourceManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function subordinates(): HasMany
    {
        return $this->hasMany(User::class, 'manager_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    public function managedResources(): HasMany
    {
        return $this->hasMany(Resource::class, 'manager_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'user_id');
    }
}