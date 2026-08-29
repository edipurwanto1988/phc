<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'kategori_id',
        'nama',
        'nama_invoice',
        'slug',
        'deskripsi',
        'deskripsi_singkat',
        'harga',
        'satuan',
        'durasi_estimasi',
        'gambar',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'durasi_estimasi' => 'integer',
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'kategori_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
