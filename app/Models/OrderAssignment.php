<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderAssignment extends Model
{
    protected $table = 'order_assignments';

    protected $fillable = [
        'order_id',
        'user_id',
        'status',
        'foto_sebelum',
        'foto_sesudah',
        'started_at',
        'finished_at',
        'gaji',
        'status_gaji',
        'sort_order',
        'expense_id',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function cleaner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
