<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class OrderPayment extends Model
{
    protected $fillable = [
        'order_id',
        'amount',
        'payment_date',
        'type',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Pastikan kolom 'status' ada di database. Jika di server production belum migrate,
     * secara otomatis tambahkan kolom agar tidak terjadi SQL error 1054 Unknown column.
     */
    public static function ensureStatusColumn(): bool
    {
        static $hasColumn = null;
        if ($hasColumn === null) {
            try {
                $hasColumn = Schema::hasColumn('order_payments', 'status');
                if (!$hasColumn) {
                    Schema::table('order_payments', function (Blueprint $table) {
                        $table->string('status', 20)->default('lunas')->after('type');
                    });
                    $hasColumn = true;
                }
            } catch (\Throwable $e) {
                $hasColumn = false;
            }
        }
        return $hasColumn;
    }

    /**
     * Scope query untuk pembayaran yang berstatus Lunas (uang masuk riil).
     */
    public function scopeLunas($query)
    {
        if (self::ensureStatusColumn()) {
            return $query->where(function($q) {
                $q->where('status', 'lunas')->orWhereNull('status');
            });
        }
        return $query;
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
