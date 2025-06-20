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
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->foreign(['id_cliente'], 'fk_cotizaciones_id_cliente')->references(['id_cliente'])->on('clientes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_direccion_entrega'], 'fk_cotizaciones_id_direccion_entrega')->references(['id_direccion'])->on('direcciones')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_divisa'], 'fk_cotizaciones_id_divisa')->references(['id_divisa'])->on('divisas')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_razon_social'], 'fk_cotizaciones_id_razon_social')->references(['id_razon_social'])->on('razones_sociales')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_vendedor'], 'fk_cotizaciones_id_vendedor')->references(['id_usuario'])->on('usuarios')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->dropForeign('fk_cotizaciones_id_cliente');
            $table->dropForeign('fk_cotizaciones_id_direccion_entrega');
            $table->dropForeign('fk_cotizaciones_id_divisa');
            $table->dropForeign('fk_cotizaciones_id_razon_social');
            $table->dropForeign('fk_cotizaciones_id_vendedor');
        });
    }
};
