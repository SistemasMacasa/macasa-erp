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
        Schema::create('metas_ventas', function (Blueprint $table) {
            $table->integer('id_meta_venta', true);
            $table->integer('id_usuario')->nullable()->index('fk_metas_ventas_id_usuario');
            $table->dateTime('mes_aplicacion')->nullable();
            $table->decimal('cuota_facturacion', 10)->nullable();
            $table->decimal('cuota_marginal', 10)->nullable();
            $table->decimal('cuota_cotizaciones', 10)->nullable();
            $table->decimal('cuota_llamadas', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metas_ventas');
    }
};
