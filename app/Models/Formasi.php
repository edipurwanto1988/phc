<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Formasi extends Model
{
    protected $table = 'formasis';

    protected $fillable = [
        'nama',
    ];

    public function lokeres(): HasMany
    {
        return $this->hasMany(Loker::class, 'formasi_id');
    }

    public function periodes(): BelongsToMany
    {
        return $this->belongsToMany(PeriodeLoker::class, 'periode_formasi', 'formasi_id', 'periode_id');
    }
}
