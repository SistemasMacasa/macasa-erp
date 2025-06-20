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
        Schema::table('compras_partidas', function (Blueprint $table) {
            $table->foreign(['id_compra'], 'fk_compras_partidas_id_compra')->references(['id_compra'])->on('compras')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('compras_partidas', function (Blueprint $table) {
            $table->dropForeign('fk_compras_partidas_id_compra');
        });
    }
};
