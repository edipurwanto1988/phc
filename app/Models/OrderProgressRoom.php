<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderProgressRoom extends Model
{
    protected $table = 'order_progress_rooms';

    protected $fillable = [
        'order_id',
        'lantai',
        'ruangan',
        'luas',
        'status',
        'catatan',
        'bukti',
        'masalah',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'luas' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
