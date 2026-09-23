<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderSchedule extends Model
{
    protected $fillable = ['order_id', 'tanggal'];
    
    protected $casts = [
        'tanggal' => 'date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function attendances()
    {
        return $this->hasMany(OrderAttendance::class);
    }
}
