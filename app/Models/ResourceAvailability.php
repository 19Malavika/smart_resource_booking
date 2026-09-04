<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_id', 'day_of_week', 'start_time', 'end_time', 'is_available'
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function resource()
    {
        return $this->belongsTo(Resource::class, 'resource_id');
    }
}
