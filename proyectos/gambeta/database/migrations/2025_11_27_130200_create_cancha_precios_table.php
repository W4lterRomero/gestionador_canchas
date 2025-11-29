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
        Schema::create('cancha_precios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('cancha_id')
                ->constrained('canchas')
                ->cascadeOnDelete();
            $table->decimal('precio_hora', 10, 2);
            $table->dateTime('fecha_desde');
            $table->dateTime('fecha_hasta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Revierte los cambios efectuados por la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('cancha_precios');
    }
};
