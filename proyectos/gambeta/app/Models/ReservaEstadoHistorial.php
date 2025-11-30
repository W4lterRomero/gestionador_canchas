<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservaEstadoHistorial extends Model
{
    use HasFactory;

    /**
     * Tabla asociada al modelo.
     */
    protected $table = 'reservas_estados_historial';

    /**
     * Atributos asignables en masa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'reserva_id',
        'estado_anterior',
        'estado_nuevo',
        'comentario',
        'fecha_cambio',
        'cambiado_por',
    ];

    /**
     * Conversión de atributos relevantes.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_cambio' => 'datetime',
    ];

    /**
     * Reserva asociada al cambio de estado.
     */
    public function reserva(): BelongsTo
    {
        return $this->belongsTo(Reserva::class);
    }

    /**
     * Usuario que ejecutó el cambio de estado.
     */
    public function cambiadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cambiado_por');
    }
}
