<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

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

    /**
     * Obtiene el precio vigente tomando en cuenta los rangos definidos por el administrador.
     */
    public function precioHoraVigente(?CarbonInterface $momento = null): float
    {
        $momento = $momento
            ? Carbon::instance($momento)
            : now();

        $precioVigente = $this->precios()
            ->where('fecha_desde', '<=', $momento)
            ->where(function ($query) use ($momento) {
                $query->whereNull('fecha_hasta')
                    ->orWhere('fecha_hasta', '>=', $momento);
            })
            ->orderByDesc('fecha_desde')
            ->first();

        return (float) ($precioVigente->precio_hora ?? $this->precio_hora);
    }
}
