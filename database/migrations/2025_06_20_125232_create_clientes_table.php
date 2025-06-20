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
            $table->integer('id_cliente', true);
            $table->string('nombre', 100)->nullable();
            $table->string('apellido_p', 100)->nullable();
            $table->string('apellido_m', 100)->nullable();
            $table->string('estatus', 50)->nullable();
            $table->string('tipo', 50)->nullable();
            $table->integer('id_vendedor')->nullable()->index('fk_clientes_id_vendedor');
            $table->string('sector', 100)->nullable();
            $table->string('segmento', 100)->nullable();
            $table->string('ciclo_venta', 100)->nullable();
            $table->date('recall')->nullable();
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
