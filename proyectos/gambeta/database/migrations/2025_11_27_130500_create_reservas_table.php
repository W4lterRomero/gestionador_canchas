<?php

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
        Schema::create('reservas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('cancha_id')->constrained('canchas')->restrictOnDelete();
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->date('fecha_reserva'); // Día en el que se tomó la reserva
            $table->dateTime('fecha_inicio'); // Fecha y hora de arranque del turno
            $table->dateTime('fecha_fin'); // Fecha y hora estimada de finalización
            $table->integer('duracion_minutos'); // Duración total del turno en minutos
            $table->decimal('precio_hora', 10, 2); // Precio vigente por hora de juego
            $table->decimal('total', 10, 2); // Total calculado para la reserva
            $table->enum('estado', ['pendiente', 'confirmada', 'finalizada', 'cancelada']); // Estado de seguimiento
            $table->text('observaciones')->nullable(); // Notas internas o peticiones especiales
            $table->foreignId('creado_por')->constrained('users')->restrictOnDelete();
            $table->foreignId('actualizado_por')->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Revierte los cambios efectuados por la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};

