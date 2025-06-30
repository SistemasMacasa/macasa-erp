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
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();  // id auto increment para equipos
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->integer('lider_id')->nullable(); // aquí defines la columna, debe ser integer signed si 'usuarios.id_usuario' es integer signed

            // ahora defines la clave foránea
            $table->foreign('lider_id')
                ->references('id_usuario')
                ->on('usuarios')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};
