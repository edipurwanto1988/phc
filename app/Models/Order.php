<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'customer_id',
        'tanggal_order',
        'tanggal_jadwal',
        'alamat_pengerjaan',
        'latitude',
        'longitude',
        'status',
        'total_harga',
        'diskon',
        'grand_total',
        'metode_bayar',
        'status_bayar',
        'down_payment',
        'down_payment_date',
        'final_payment',
        'final_payment_date',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_order' => 'date',
        'tanggal_jadwal' => 'datetime',
        'down_payment_date' => 'date',
        'final_payment_date' => 'date',
        'total_harga' => 'decimal:2',
        'diskon' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(OrderAssignment::class)->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }
}
