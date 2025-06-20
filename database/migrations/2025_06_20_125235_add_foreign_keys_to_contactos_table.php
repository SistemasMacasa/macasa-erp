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
        Schema::table('contactos', function (Blueprint $table) {
            $table->foreign(['id_direccion_entrega'], 'fk_contactos_direccion_entrega')->references(['id_direccion'])->on('direcciones')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['id_cliente'], 'fk_contactos_id_cliente')->references(['id_cliente'])->on('clientes')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contactos', function (Blueprint $table) {
            $table->dropForeign('fk_contactos_direccion_entrega');
            $table->dropForeign('fk_contactos_id_cliente');
        });
    }
};
