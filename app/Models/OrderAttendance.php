<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAttendance extends Model
{
    protected $fillable = [
        'order_schedule_id',
        'user_id',
        'latitude',
        'longitude',
        'waktu_absen',
        'status',
    ];

    protected $casts = [
        'waktu_absen' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function schedule()
    {
        return $this->belongsTo(OrderSchedule::class, 'order_schedule_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
