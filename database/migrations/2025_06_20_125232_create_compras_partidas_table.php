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
        Schema::create('compras_partidas', function (Blueprint $table) {
            $table->integer('id_compras_partidas', true);
            $table->integer('id_compra')->nullable()->index('fk_compras_partidas_id_compra');
            $table->string('sku', 100)->nullable();
            $table->string('descripcion', 100)->nullable();
            $table->integer('cantidad')->nullable();
            $table->decimal('costo', 10)->nullable();
            $table->integer('cantidad_recibida')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras_partidas');
    }
};
