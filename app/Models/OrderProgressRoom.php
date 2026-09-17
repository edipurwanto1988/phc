<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function buktiPhotos(): HasMany
    {
        return $this->hasMany(OrderProgressBukti::class, 'order_progress_room_id')->orderBy('id', 'asc');
    }
}
