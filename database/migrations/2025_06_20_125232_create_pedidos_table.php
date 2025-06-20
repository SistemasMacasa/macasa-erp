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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->integer('id_pedido', true);
            $table->integer('id_cotizacion')->nullable()->index('fk_pedidos_id_cotizacion');
            $table->string('consecutivo_pedido', 100)->nullable();
            $table->dateTime('fecha_pedido')->nullable();
            $table->dateTime('fecha_pdf')->nullable();
            $table->string('estatus', 50)->nullable();
            $table->integer('id_cliente')->nullable()->index('fk_pedidos_id_cliente');
            $table->integer('id_razon_social')->nullable()->index('fk_pedidos_id_razon_social');
            $table->integer('id_vendedor')->nullable()->index('fk_pedidos_id_vendedor');
            $table->integer('id_direccion_entrega')->nullable()->index('fk_pedidos_id_direccion_entrega');
            $table->integer('id_divisa')->nullable()->index('fk_pedidos_id_divisa');
            $table->string('orden_en_venta', 100)->nullable();
            $table->string('factura_pdf', 100)->nullable();
            $table->string('factura_xml', 100)->nullable();
            $table->decimal('score_final', 10)->nullable();
            $table->text('notas_entrega')->nullable();
            $table->text('notas_facturacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
