<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LokerBerkas extends Model
{
    protected $table = 'loker_berkas';

    protected $fillable = [
        'loker_id',
        'periode_syarat_id',
        'path',
    ];

    public function loker(): BelongsTo
    {
        return $this->belongsTo(Loker::class, 'loker_id');
    }

    public function syarat(): BelongsTo
    {
        return $this->belongsTo(PeriodeSyarat::class, 'periode_syarat_id');
    }
}
