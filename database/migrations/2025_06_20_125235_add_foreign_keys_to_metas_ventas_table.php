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
        Schema::table('metas_ventas', function (Blueprint $table) {
            $table->foreign(['id_usuario'], 'fk_metas_ventas_id_usuario')->references(['id_usuario'])->on('usuarios')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('metas_ventas', function (Blueprint $table) {
            $table->dropForeign('fk_metas_ventas_id_usuario');
        });
    }
};
