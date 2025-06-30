<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('equipo_usuario', function (Blueprint $table) {
            // BIGINT UNSIGNED porque equipos.id es BIGINT UNSIGNED
            $table->unsignedBigInteger('equipo_id');

            // INTEGER porque usuarios.id_usuario es INTEGER
            $table->integer('usuario_id');

            $table->enum('rol', ['lider', 'miembro'])->default('miembro');
            $table->timestamps();

            $table->primary(['equipo_id', 'usuario_id']);

            $table->foreign('equipo_id')
                ->references('id')
                ->on('equipos')
                ->onDelete('cascade');

            $table->foreign('usuario_id')
                ->references('id_usuario')
                ->on('usuarios')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipo_usuario');
    }
};
