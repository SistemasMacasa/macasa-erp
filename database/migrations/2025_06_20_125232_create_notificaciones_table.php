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
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->integer('id_notificacion', true);
            $table->integer('id_usuario_origen')->nullable()->index('fk_notificaciones_id_usuario_origen');
            $table->integer('id_usuario_destino')->nullable()->index('fk_notificaciones_id_usuario_destino');
            $table->text('mensaje')->nullable();
            $table->boolean('estatus')->nullable();
            $table->dateTime('fecha_leido')->nullable();
            $table->string('tipo', 50)->nullable();
            $table->integer('id_referencia')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
