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
        Schema::create('proveedores', function (Blueprint $table) {
            $table->integer('id_proveedor', true);
            $table->string('nombre', 100)->nullable();
            $table->integer('id_direccion')->nullable()->index('fk_proveedores_id_direccion');
            $table->string('estatus', 50)->nullable();
            $table->string('telefono', 100)->nullable();
            $table->string('ext', 10)->nullable();
            $table->string('celular', 100)->nullable();
            $table->string('email', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
