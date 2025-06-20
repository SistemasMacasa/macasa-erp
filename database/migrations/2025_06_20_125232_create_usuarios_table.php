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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->integer('id_usuario', true);
            $table->string('username', 100)->nullable();
            $table->string('nombre', 30)->nullable();
            $table->string('apellido_p', 30)->nullable();
            $table->string('apellido_m', 30)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('password', 100)->nullable();
            $table->string('cargo', 100)->nullable();
            $table->string('tipo', 50)->nullable();
            $table->string('estatus', 50)->nullable();
            $table->integer('id_cliente')->nullable()->index('fk_usuarios_id_cliente');
            $table->boolean('es_admin')->nullable()->default(false);
            $table->dateTime('fecha_alta')->nullable();
            $table->date('birthday')->nullable();
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
