<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BloqueoHorario extends Model
{
    use HasFactory;

    /**
     * Tabla asociada al modelo.
     */
    protected $table = 'bloqueos_horarios';

    /**
     * Atributos asignables masivamente.
     *
     * @var list<string>
     */
    protected $fillable = [
        'cancha_id',
        'fecha_inicio',
        'fecha_fin',
        'motivo',
        'creado_por',
    ];

    /**
     * Casts para las fechas.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];

    /**
     * Cancha a la que pertenece el bloqueo.
     */
    public function cancha(): BelongsTo
    {
        return $this->belongsTo(Cancha::class);
    }

    /**
     * Usuario que registró el bloqueo.
     */
    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }
}
