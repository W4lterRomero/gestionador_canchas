<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CanchaPrecio extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'cancha_precios';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'cancha_id',
        'precio_hora',
        'fecha_desde',
        'fecha_hasta',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'precio_hora' => 'decimal:2',
        'fecha_desde' => 'date',
        'fecha_hasta' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Precio que pertenece a una cancha específica.
     */
    public function cancha(): BelongsTo
    {
        return $this->belongsTo(Cancha::class);
    }
}
