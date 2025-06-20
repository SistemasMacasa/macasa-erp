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
        Schema::create('direcciones', function (Blueprint $table) {
            $table->integer('id_direccion', true);
            $table->integer('id_cliente')->nullable()->index('fk_direcciones_id_cliente');
            $table->string('nombre', 100)->nullable();
            $table->string('tipo', 50)->nullable();
            $table->string('calle', 100)->nullable();
            $table->string('num_ext', 20)->nullable();
            $table->string('num_int', 20)->nullable();
            $table->string('colonia', 100)->nullable();
            $table->integer('id_ciudad')->nullable()->index('fk_direcciones_id_ciudad');
            $table->integer('id_estado')->nullable()->index('fk_direcciones_id_estado');
            $table->integer('id_pais')->nullable()->index('fk_direcciones_id_pais');
            $table->string('cp', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direcciones');
    }
};
