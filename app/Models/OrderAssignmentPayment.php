<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderAssignmentPayment extends Model
{
    protected $table = 'order_assignment_payments';

    protected $fillable = [
        'assignment_id',
        'amount',
        'type',
        'payment_date',
        'catatan',
        'expense_id',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(OrderAssignment::class, 'assignment_id');
    }

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class, 'expense_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
