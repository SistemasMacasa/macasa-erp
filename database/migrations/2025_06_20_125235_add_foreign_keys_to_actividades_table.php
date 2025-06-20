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
        Schema::table('actividades', function (Blueprint $table) {
            $table->foreign(['id_cliente'], 'fk_actividades_id_cliente')->references(['id_cliente'])->on('clientes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_usuario'], 'fk_actividades_id_usuario')->references(['id_usuario'])->on('usuarios')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actividades', function (Blueprint $table) {
            $table->dropForeign('fk_actividades_id_cliente');
            $table->dropForeign('fk_actividades_id_usuario');
        });
    }
};
