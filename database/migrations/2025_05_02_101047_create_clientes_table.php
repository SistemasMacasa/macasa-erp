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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('clientes', function (Blueprint $table) {
            $table->id('id_cliente');
            $table->string('nombre',100);
            $table->string('apellido_p')->nullable();
            $table->string('apellido_m')->nullable();
            $table->enum('estatus',['activo','inactivo'])->default('activo');
            $table->string('tipo',50);
            $table->unsignedBigInteger('id_vendedor')->nullable();
            $table->foreign('id_vendedor')->references('id_usuario')->on('usuarios');
            $table->string('sector',50);
            $table->string('segmento',50);
            $table->string('ciclo_venta',50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
