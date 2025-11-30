<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'activo',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function bloqueosCreados(): HasMany
    {
        return $this->hasMany(BloqueoHorario::class, 'creado_por');
    }

    /**
     * Verificar si el usuario es administrador
     */
    public function isAdmin(): bool
    {
        return $this->hasAnyRole(['admin', 'Administrador', 'administrator']);
    }

    /**
     * Verificar si el usuario es empleado o admin
     */
    public function isEmployee(): bool
    {
        return $this->hasAnyRole([
            'empleado', 
            'Empleado', 
            'employee', 
            'admin', 
            'Administrador', 
            'administrator'
        ]);
    }
}