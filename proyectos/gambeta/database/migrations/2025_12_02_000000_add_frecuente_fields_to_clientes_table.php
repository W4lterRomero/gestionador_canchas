<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->boolean('es_frecuente')->default(false)->after('notas');
            $table->integer('total_reservas')->default(0)->after('es_frecuente');
            $table->date('ultima_reserva')->nullable()->after('total_reservas');
            $table->date('fecha_registro')->nullable()->after('ultima_reserva');
            $table->decimal('descuento_porcentaje', 5, 2)->default(0)->after('fecha_registro');
        });
    }

    
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn([
                'es_frecuente',
                'total_reservas',
                'ultima_reserva',
                'fecha_registro',
                'descuento_porcentaje'
            ]);
        });
    }
};
