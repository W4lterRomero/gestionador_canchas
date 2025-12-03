<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    
    protected $table = 'clientes';

    /**
     * Atributos asignables en masa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
        'telefono',
        'equipo',
        'email',
        'notas',
        'es_frecuente',
        'total_reservas',
        'ultima_reserva',
        'fecha_registro',
        'descuento_porcentaje',
    ];

    /**
     * Conversión de atributos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'es_frecuente' => 'boolean',
        'total_reservas' => 'integer',
        'ultima_reserva' => 'date',
        'fecha_registro' => 'date',
        'descuento_porcentaje' => 'decimal:2',
    ];


    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'cliente_id');
    }


    public function esFrecuente(): bool
    {
        return $this->es_frecuente;
    }


    public function marcarComoFrecuente(float $descuento = 0): void
    {
        $this->update([
            'es_frecuente' => true,
            'descuento_porcentaje' => $descuento,
        ]);
    }


    public function desmarcarComoFrecuente(): void
    {
        $this->update([
            'es_frecuente' => false,
            'descuento_porcentaje' => 0,
        ]);
    }


    public function actualizarEstadisticas(): void
    {
        $this->update([
            'total_reservas' => $this->reservas()->count(),
            'ultima_reserva' => $this->reservas()->latest('fecha_reserva')->first()?->fecha_reserva,
        ]);
    }


    public function scopeFrecuentes($query)
    {
        return $query->where('es_frecuente', true);
    }


    public function scopeActivos($query)
    {
        return $query->where('total_reservas', '>', 0);
    }

    public function getNombreCompletoAttribute(): string
    {
        return $this->equipo ? "{$this->nombre} ({$this->equipo})" : $this->nombre;
    }
}
