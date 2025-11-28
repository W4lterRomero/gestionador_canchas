<?php

use App\Models\Reserva;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta los cambios en la base de datos.
     */
    public function up(): void
    {
        Schema::create('reservas_estados_historial', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('reserva_id')
                ->constrained('reservas')
                ->cascadeOnDelete();
            $table->enum('estado_anterior', Reserva::ESTADOS);
            $table->enum('estado_nuevo', Reserva::ESTADOS);
            $table->string('comentario', 255)->nullable();
            $table->dateTime('fecha_cambio');
            $table->foreignId('cambiado_por')
                ->constrained('users')
                ->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Revierte los cambios efectuados por la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas_estados_historial');
    }
};
