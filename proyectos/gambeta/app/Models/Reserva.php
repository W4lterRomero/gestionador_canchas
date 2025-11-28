<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reserva extends Model
{
    use HasFactory;

    /**
     * Posibles estados de una reserva.
     *
     * @var list<string>
     */
    public const ESTADOS = ['pendiente', 'confirmada', 'finalizada', 'cancelada'];

    /**
     * Tabla asociada al modelo.
     */
    protected $table = 'reservas';

    /**
     * Atributos asignables en masa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'cancha_id',
        'cliente_id',
        'fecha_reserva',
        'fecha_inicio',
        'fecha_fin',
        'duracion_minutos',
        'precio_hora',
        'total',
        'estado',
        'observaciones',
        'creado_por',
        'actualizado_por',
    ];

    /**
     * Conversión de atributos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_reserva' => 'date',
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'precio_hora' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Cancha asociada a la reserva.
     */
    public function cancha(): BelongsTo
    {
        return $this->belongsTo(Cancha::class);
    }

    /**
     * Cliente que solicitó la reserva.
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Usuario que creó la reserva.
     */
    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    /**
     * Usuario que actualizó por última vez la reserva.
     */
    public function actualizador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actualizado_por');
    }
}

