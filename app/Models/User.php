<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'username', 'email', 'password', 'role_id', 'jenis', 'level', 'phone', 'foto', 'status', 'google_id', 'keahlian', 'phc_id'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime', 'password' => 'hashed'];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function createdOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'created_by');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(OrderAssignment::class, 'user_id');
    }

    public function isCleaner(): bool
    {
        return $this->role && strtolower($this->role->name) === 'cleaner';
    }

    public function hasPermission(string $permission): bool
    {
        return $this->role && $this->role->hasPermission($permission);
    }

    public function hasLinkedGoogleAccount(): bool
    {
        return !empty($this->google_id);
    }
}