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
        Schema::create('contactos', function (Blueprint $table) {
            $table->id('id_contacto');
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_direccion_entrega')->nullable();
            $table->string('nombre',100);
            $table->string('apellido_p',100)->nullable();
            $table->string('apellido_m',100)->nullable();
            $table->string('email',100)->nullable();
            $table->string('puesto',100)->nullable();
            // teléfonos y extensiones 1–5
            for ($i = 1; $i <= 5; $i++) {
                $table->string("telefono{$i}",20)->nullable();
                $table->string("ext{$i}",10)->nullable();
            }
            // llaves foráneas
            $table->foreign('id_cliente')->references('id_cliente')->on('clientes');
            $table->foreign('id_direccion_entrega')->references('id_direccion')->on('direcciones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contactos');
    }
};
