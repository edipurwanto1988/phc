<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceCategory extends Model
{
    protected $table = 'service_categories';

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'icon',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'kategori_id')->orderBy('urutan');
    }
}
