<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loker extends Model
{
    protected $table = 'lokeres';

    protected $fillable = [
        'periode_id',
        'formasi_id',
        'nama',
        'email',
        'alamat',
        'jenis_kelamin',
        'pengalaman',
        'keahlian_khusus',
        'cerita',
        'no_wa',
        'ktp',
        'ktp_drive_id',
        'rating',
    ];

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeLoker::class, 'periode_id');
    }

    public function formasi(): BelongsTo
    {
        return $this->belongsTo(Formasi::class, 'formasi_id');
    }

    public function berkas(): HasMany
    {
        return $this->hasMany(LokerBerkas::class, 'loker_id');
    }
}
