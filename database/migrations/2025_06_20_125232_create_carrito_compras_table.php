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
        Schema::create('carrito_compras', function (Blueprint $table) {
            $table->integer('id_carrito', true);
            $table->integer('id_usuario')->nullable()->index('fk_carrito_compras_id_usuario');
            $table->string('id_producto_proveedor', 100)->nullable();
            $table->string('clave_api_proveedor', 100)->nullable();
            $table->string('num_parte_proveedor', 100)->nullable();
            $table->string('nombre_api', 100)->nullable();
            $table->string('marca_api', 100)->nullable();
            $table->string('categoria_api', 100)->nullable();
            $table->json('datos_extra')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carrito_compras');
    }
};
