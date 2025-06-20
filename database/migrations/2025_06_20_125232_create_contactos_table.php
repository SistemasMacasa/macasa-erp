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
            $table->integer('id_contacto', true);
            $table->integer('id_cliente')->nullable()->index('fk_contactos_id_cliente');
            $table->integer('id_direccion_entrega')->nullable()->index('fk_contactos_direccion_entrega');
            $table->string('nombre', 100)->nullable();
            $table->string('apellido_p', 100)->nullable();
            $table->string('apellido_m', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('puesto', 100)->nullable();
            $table->enum('genero', ['masculino', 'femenino', 'no-especificado'])->nullable();
            $table->string('telefono1', 100)->nullable();
            $table->string('ext1', 10)->nullable();
            $table->string('celular1', 100)->nullable();
            $table->string('telefono2', 100)->nullable();
            $table->string('ext2', 10)->nullable();
            $table->string('celular2', 100)->nullable();
            $table->string('telefono3', 20)->nullable();
            $table->string('ext3', 10)->nullable();
            $table->string('celular3', 100)->nullable();
            $table->string('telefono4', 20)->nullable();
            $table->string('ext4', 10)->nullable();
            $table->string('celular4', 100)->nullable();
            $table->string('telefono5', 20)->nullable();
            $table->string('ext5', 10)->nullable();
            $table->string('celular5', 100)->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->boolean('predeterminado')->default(false);
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
