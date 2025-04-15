<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('cargo')->nullable();
            $table->string('tipo')->nullable(); // erp, ecommerce, otro
            $table->string('estatus')->default('Activo');
            $table->unsignedBigInteger('id_cliente')->nullable();
            $table->boolean('es_admin')->default(false);
            $table->timestamp('fecha_alta')->useCurrent();
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
