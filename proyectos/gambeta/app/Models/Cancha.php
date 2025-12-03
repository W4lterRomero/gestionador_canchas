<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cancha extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'canchas';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
        'tipo',
        'descripcion',
        'precio_hora',
        'imagen_url',
        'activa',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'precio_hora' => 'decimal:2',
        'activa' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Precios históricos vigentes para la cancha.
     */
    public function precios(): HasMany
    {
        return $this->hasMany(CanchaPrecio::class);
    }

    /**
     * Bloqueos de horario configurados para la cancha.
     */
    public function bloqueos(): HasMany
    {
        return $this->hasMany(BloqueoHorario::class);
    }

    /**
     * Imágenes adicionales de la cancha.
     */
    public function imagenes(): HasMany
    {
        return $this->hasMany(CanchaImagen::class);
    }
}
