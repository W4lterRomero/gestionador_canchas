<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cancha_imagenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cancha_id')->constrained('canchas')->onDelete('cascade');
            $table->string('imagen_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cancha_imagenes');
    }
};
