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
        Schema::create('pagos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('reserva_id')->constrained('reservas')->restrictOnDelete();
            $table->decimal('monto', 10, 2);
            $table->enum('tipo_pago', ['anticipo', 'saldo', 'total']);
            $table->string('metodo_pago', 50);
            $table->dateTime('fecha_pago');
            $table->string('comprobante_url', 255);
            $table->foreignId('registrado_por')->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Revierte los cambios efectuados por la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
