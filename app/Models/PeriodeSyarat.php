<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodeSyarat extends Model
{
    protected $table = 'periode_syarats';

    protected $fillable = [
        'periode_id',
        'nama',
        'wajib',
        'urutan',
    ];

    protected $casts = [
        'wajib' => 'boolean',
        'urutan' => 'integer',
    ];

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeLoker::class, 'periode_id');
    }

    public function berkas(): HasMany
    {
        return $this->hasMany(LokerBerkas::class, 'periode_syarat_id');
    }
}
