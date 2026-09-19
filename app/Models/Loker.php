<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loker extends Model
{
    protected $table = 'lokeres';

    protected $fillable = [
        'periode_id',
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
    ];

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeLoker::class, 'periode_id');
    }
}
