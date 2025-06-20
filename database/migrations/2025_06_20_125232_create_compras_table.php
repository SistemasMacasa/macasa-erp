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
        Schema::create('compras', function (Blueprint $table) {
            $table->integer('id_compra', true);
            $table->integer('id_proveedor')->nullable()->index('fk_compras_id_proveedor');
            $table->integer('id_comprador')->nullable()->index('fk_compras_id_comprador');
            $table->string('consecutivo_compra', 100)->nullable();
            $table->dateTime('fecha_compra')->nullable();
            $table->string('estatus', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
