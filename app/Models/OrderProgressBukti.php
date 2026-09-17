<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderProgressBukti extends Model
{
    protected $table = 'order_progress_bukti';

    protected $fillable = [
        'order_progress_room_id',
        'path',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(OrderProgressRoom::class, 'order_progress_room_id');
    }
}
