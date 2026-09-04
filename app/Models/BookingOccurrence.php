<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingOccurrence extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_series_id', 'booking_id', 'occurrence_date', 'status'
    ];

    protected $casts = [
        'occurrence_date' => 'date',
    ];

    public function series()
    {
        return $this->belongsTo(BookingSeries::class, 'booking_series_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
