<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CanchaImagen extends Model
{
    protected $table = 'cancha_imagenes';

    protected $fillable = [
        'cancha_id',
        'imagen_url',
    ];

    public function cancha(): BelongsTo
    {
        return $this->belongsTo(Cancha::class);
    }
}
