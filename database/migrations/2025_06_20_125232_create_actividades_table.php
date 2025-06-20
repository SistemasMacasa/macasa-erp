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
        Schema::create('actividades', function (Blueprint $table) {
            $table->integer('id_actividad', true);
            $table->integer('id_usuario')->nullable()->index('fk_actividades_id_usuario');
            $table->integer('id_cliente')->nullable()->index('fk_actividades_id_cliente');
            $table->dateTime('fecha_actividad')->nullable();
            $table->text('descripcion')->nullable();
            $table->boolean('recordatorio')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};
