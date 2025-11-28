<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    use HasFactory;

    /**
     * Tipos válidos para el campo tipo_pago.
     *
     * @var list<string>
     */
    public const TIPOS = ['anticipo', 'saldo', 'total'];

    /**
     * Tabla asociada al modelo.
     */
    protected $table = 'pagos';

    /**
     * Atributos asignables en masa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'reserva_id',
        'monto',
        'tipo_pago',
        'metodo_pago',
        'fecha_pago',
        'comprobante_url',
        'registrado_por',
    ];

    /**
     * Conversión de atributos relevantes.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_pago' => 'datetime',
    ];

    /**
     * Reserva asociada al pago.
     */
    public function reserva(): BelongsTo
    {
        return $this->belongsTo(Reserva::class);
    }

    /**
     * Usuario que registró el pago.
     */
    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }
}

