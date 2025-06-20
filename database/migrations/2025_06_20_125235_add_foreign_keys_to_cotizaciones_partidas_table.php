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
        Schema::table('cotizaciones_partidas', function (Blueprint $table) {
            $table->foreign(['id_cotizacion'], 'fk_cotizaciones_partidas_id_cotizacion')->references(['id_cotizacion'])->on('cotizaciones')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_proveedor'], 'fk_cotizaciones_partidas_id_proveedor')->references(['id_proveedor'])->on('proveedores')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cotizaciones_partidas', function (Blueprint $table) {
            $table->dropForeign('fk_cotizaciones_partidas_id_cotizacion');
            $table->dropForeign('fk_cotizaciones_partidas_id_proveedor');
        });
    }
};
