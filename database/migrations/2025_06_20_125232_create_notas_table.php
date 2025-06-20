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
        Schema::create('notas', function (Blueprint $table) {
            $table->integer('id_nota', true);
            $table->integer('id_cliente')->index('id_cliente');
            $table->integer('id_usuario')->nullable()->index('id_usuario');
            $table->string('etapa', 50)->nullable();
            $table->text('contenido');
            $table->dateTime('fecha_registro')->nullable()->useCurrent();
            $table->date('fecha_reprogramacion')->nullable();
            $table->boolean('es_automatico')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};
