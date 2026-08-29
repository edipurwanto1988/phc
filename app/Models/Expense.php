<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = [
        'tanggal',
        'kategori_biaya',
        'jumlah',
        'keterangan',
        'user_id',
        'is_gaji', // Mark whether it is a salary payment
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
        'is_gaji' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orderAssignments()
    {
        return $this->hasMany(OrderAssignment::class, 'expense_id');
    }
}
