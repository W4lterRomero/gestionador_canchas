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
        Schema::create('canchas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 100); // Nombre comercial de la cancha
            $table->string('tipo', 50); // Tipo de cancha (fútbol 5, sintética, etc.)
            $table->text('descripcion')->nullable(); // Detalle opcional sobre las características
            $table->decimal('precio_hora', 10, 2); // Precio base por hora de alquiler
            $table->string('imagen_url', 255)->nullable(); // Ruta o URL de la imagen de referencia
            $table->boolean('activa'); // Define si la cancha está disponible para reservas
            $table->timestamps();
        });
    }

    /**
     * Revierte los cambios efectuados por la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('canchas');
    }
};
