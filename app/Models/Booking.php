<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'resource_id', 'start_datetime', 'end_datetime',
        'purpose', 'participants', 'priority', 'notes', 'status',
        'approval_type', 'approved_by', 'approved_at', 
        'rejection_reason', 'rejected_by', 'rejected_at',
        'cancellation_reason', 'cancelled_by', 'cancelled_at',
        'recurring_pattern', 'parent_booking_id'
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class, 'resource_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function parent()
    {
        return $this->belongsTo(Booking::class, 'parent_booking_id');
    }

    public function occurrences()
    {
        return $this->hasMany(Booking::class, 'parent_booking_id');
    }
}
