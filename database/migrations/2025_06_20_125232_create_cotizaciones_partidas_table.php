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
        Schema::create('cotizaciones_partidas', function (Blueprint $table) {
            $table->integer('id_cotizacion_partida', true);
            $table->integer('id_cotizacion')->nullable()->index('fk_cotizaciones_partidas_id_cotizacion');
            $table->integer('id_proveedor')->nullable()->index('fk_cotizaciones_partidas_id_proveedor');
            $table->string('sku', 100)->nullable();
            $table->string('descripcion', 100)->nullable();
            $table->integer('cantidad')->nullable();
            $table->decimal('precio', 10)->nullable();
            $table->decimal('costo', 10)->nullable();
            $table->decimal('score', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizaciones_partidas');
    }
};
