<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function payments(): HasMany
    {
        return $this->hasMany(OrderAssignmentPayment::class, 'assignment_id')
            ->orderBy('payment_date', 'asc')
            ->orderBy('id', 'asc');
    }

    /**
     * Total amount already paid (cashbon + pelunasan) for this assignment.
     */
    public function totalPaid(): float
    {
        return (float) $this->payments()->sum('amount');
    }

    /**
     * Remaining amount still owed for this assignment's salary.
     */
    public function remaining(): float
    {
        return max(0, (float) $this->gaji - $this->totalPaid());
    }

    /**
     * Whether the salary has been fully paid (lunas).
     */
    public function isLunas(): bool
    {
        return $this->remaining() <= 0 && (float) $this->gaji > 0;
    }
}
