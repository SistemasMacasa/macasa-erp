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
        Schema::create('pagos', function (Blueprint $table) {
            $table->integer('id_pago', true);
            $table->integer('id_cliente')->nullable()->index('fk_pagos_id_cliente');
            $table->decimal('importe', 10)->nullable();
            $table->dateTime('fecha_pago')->nullable();
            $table->integer('id_metodo_pago')->nullable()->index('fk_pagos_id_metodo_pago');
            $table->integer('id_forma_pago')->nullable()->index('fk_pagos_id_forma_pago');
            $table->integer('id_divisa')->nullable()->index('fk_pagos_id_divisa');
            $table->decimal('tipo_cambio', 10)->nullable();
            $table->string('referencia', 100)->nullable();
            $table->boolean('es_anticipo')->nullable();
            $table->text('comentarios')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
