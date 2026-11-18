<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetMaintenance extends Model
{
    public const JENIS = [
        'kerusakan' => 'Kerusakan',
        'perbaikan' => 'Perbaikan',
    ];

    protected $fillable = [
        'asset_id',
        'tanggal',
        'jenis',
        'deskripsi',
        'biaya',
        'expense_id',
        'kondisi_setelah',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'biaya' => 'decimal:2',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class, 'expense_id');
    }

    public function getJenisLabelAttribute(): string
    {
        return self::JENIS[$this->jenis] ?? ucfirst($this->jenis);
    }
}
