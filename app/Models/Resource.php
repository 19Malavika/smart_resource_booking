<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'manager_id', 'name', 'code', 'location',
        'description', 'capacity', 'status', 'approval_type'
    ];

    public function category()
    {
        return $this->belongsTo(ResourceCategory::class, 'category_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function availabilities()
    {
        return $this->hasMany(ResourceAvailability::class, 'resource_id');
    }

    public function maintenances()
    {
        return $this->hasMany(ResourceMaintenance::class, 'resource_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'resource_id');
    }
}
