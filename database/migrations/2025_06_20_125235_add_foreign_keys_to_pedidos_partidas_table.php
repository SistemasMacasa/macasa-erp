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
        Schema::table('pedidos_partidas', function (Blueprint $table) {
            $table->foreign(['id_pedido'], 'fk_pedidos_partidas_id_pedido')->references(['id_pedido'])->on('pedidos')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_proveedor'], 'fk_pedidos_partidas_id_proveedor')->references(['id_proveedor'])->on('proveedores')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos_partidas', function (Blueprint $table) {
            $table->dropForeign('fk_pedidos_partidas_id_pedido');
            $table->dropForeign('fk_pedidos_partidas_id_proveedor');
        });
    }
};
