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
        Schema::table('pedidos', function (Blueprint $table) {
            $table->foreign(['id_cliente'], 'fk_pedidos_id_cliente')->references(['id_cliente'])->on('clientes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_cotizacion'], 'fk_pedidos_id_cotizacion')->references(['id_cotizacion'])->on('cotizaciones')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_direccion_entrega'], 'fk_pedidos_id_direccion_entrega')->references(['id_direccion'])->on('direcciones')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_divisa'], 'fk_pedidos_id_divisa')->references(['id_divisa'])->on('divisas')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_razon_social'], 'fk_pedidos_id_razon_social')->references(['id_razon_social'])->on('razones_sociales')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_vendedor'], 'fk_pedidos_id_vendedor')->references(['id_usuario'])->on('usuarios')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropForeign('fk_pedidos_id_cliente');
            $table->dropForeign('fk_pedidos_id_cotizacion');
            $table->dropForeign('fk_pedidos_id_direccion_entrega');
            $table->dropForeign('fk_pedidos_id_divisa');
            $table->dropForeign('fk_pedidos_id_razon_social');
            $table->dropForeign('fk_pedidos_id_vendedor');
        });
    }
};
