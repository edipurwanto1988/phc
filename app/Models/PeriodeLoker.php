<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class PeriodeLoker extends Model
{
    protected $table = 'periode_lokeres';

    protected $fillable = [
        'nama',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function lokeres(): HasMany
    {
        return $this->hasMany(Loker::class, 'periode_id');
    }

    /**
     * Apakah periode ini sedang dibuka saat ini?
     * Syarat: status aktif DAN tanggal hari ini berada dalam rentang tanggal.
     */
    public function isOpen(?Carbon $now = null): bool
    {
        if ($this->status !== 'aktif') {
            return false;
        }

        $now = $now ?? now();

        if ($this->tanggal_mulai && $now->lt($this->tanggal_mulai->startOfDay())) {
            return false;
        }

        if ($this->tanggal_selesai && $now->gt($this->tanggal_selesai->endOfDay())) {
            return false;
        }

        return true;
    }
}
