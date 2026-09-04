<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSeries extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'resource_id', 'frequency',
        'start_date', 'end_date', 'start_time', 'end_time'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class, 'resource_id');
    }

    public function occurrences()
    {
        return $this->hasMany(BookingOccurrence::class, 'booking_series_id');
    }
}
