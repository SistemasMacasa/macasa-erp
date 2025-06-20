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
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->integer('id_cotizacion', true);
            $table->integer('id_cliente')->nullable()->index('fk_cotizaciones_id_cliente');
            $table->integer('id_razon_social')->nullable()->index('fk_cotizaciones_id_razon_social');
            $table->integer('id_vendedor')->nullable()->index('fk_cotizaciones_id_vendedor');
            $table->dateTime('fecha_alta')->nullable();
            $table->dateTime('vencimiento')->nullable();
            $table->integer('id_direccion_entrega')->nullable()->index('fk_cotizaciones_id_direccion_entrega');
            $table->string('estatus', 50)->nullable();
            $table->integer('id_divisa')->nullable()->index('fk_cotizaciones_id_divisa');
            $table->string('num_consecutivo', 100)->nullable();
            $table->string('orden_de_venta', 100)->nullable();
            $table->decimal('score_final', 10)->nullable();
            $table->text('notas_entrega')->nullable();
            $table->text('notas_facturacion')->nullable();
            $table->integer('id_termino_pago')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};
