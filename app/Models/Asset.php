<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    public const KONDISI = [
        'baik' => 'Baik',
        'rusak' => 'Rusak',
        'dalam_perbaikan' => 'Dalam Perbaikan',
        'tidak_aktif' => 'Tidak Aktif',
    ];

    protected $fillable = [
        'kode_alat',
        'nama',
        'kategori_id',
        'merk',
        'tanggal_beli',
        'harga',
        'expense_id',
        'kondisi',
        'lokasi',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_beli' => 'date',
        'harga' => 'decimal:2',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(AssetCategory::class, 'kategori_id');
    }

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class, 'expense_id');
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(AssetMaintenance::class, 'asset_id')->orderByDesc('tanggal')->orderByDesc('id');
    }

    public function getKondisiLabelAttribute(): string
    {
        return self::KONDISI[$this->kondisi] ?? ucfirst($this->kondisi);
    }

    public function getTotalBiayaPerbaikanAttribute(): float
    {
        return (float) $this->maintenances()->sum('biaya');
    }
}
