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
        Schema::table('direcciones', function (Blueprint $table) {
            $table->foreign(['id_ciudad'], 'fk_direcciones_ciudad')->references(['id_ciudad'])->on('ciudades')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['id_estado'], 'fk_direcciones_estado')->references(['id_estado'])->on('estados')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['id_ciudad'], 'fk_direcciones_id_ciudad')->references(['id_ciudad'])->on('ciudades')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_cliente'], 'fk_direcciones_id_cliente')->references(['id_cliente'])->on('clientes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_estado'], 'fk_direcciones_id_estado')->references(['id_estado'])->on('estados')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_pais'], 'fk_direcciones_id_pais')->references(['id_pais'])->on('paises')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_pais'], 'fk_direcciones_pais')->references(['id_pais'])->on('paises')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('direcciones', function (Blueprint $table) {
            $table->dropForeign('fk_direcciones_ciudad');
            $table->dropForeign('fk_direcciones_estado');
            $table->dropForeign('fk_direcciones_id_ciudad');
            $table->dropForeign('fk_direcciones_id_cliente');
            $table->dropForeign('fk_direcciones_id_estado');
            $table->dropForeign('fk_direcciones_id_pais');
            $table->dropForeign('fk_direcciones_pais');
        });
    }
};
