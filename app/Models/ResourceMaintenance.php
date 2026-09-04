<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceMaintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_id', 'start_datetime', 'end_datetime',
        'reason', 'created_by', 'status'
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
    ];

    public function resource()
    {
        return $this->belongsTo(Resource::class, 'resource_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
