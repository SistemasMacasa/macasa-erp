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
        Schema::table('razones_sociales', function (Blueprint $table) {
            $table->foreign(['id_cliente'], 'fk_razones_sociales_id_cliente')->references(['id_cliente'])->on('clientes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_direccion_facturacion'], 'fk_razones_sociales_id_direccion_facturacion')->references(['id_direccion'])->on('direcciones')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_forma_pago'], 'fk_razones_sociales_id_forma_pago')->references(['id_forma_pago'])->on('forma_pagos')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_metodo_pago'], 'fk_razones_sociales_id_metodo_pago')->references(['id_metodo_pago'])->on('metodo_pagos')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_regimen_fiscal'], 'fk_razones_sociales_id_regimen_fiscal')->references(['id_regimen_fiscal'])->on('regimen_fiscales')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_uso_cfdi'], 'fk_razones_sociales_uso_cfdi')->references(['id_uso_cfdi'])->on('uso_cfdis')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('razones_sociales', function (Blueprint $table) {
            $table->dropForeign('fk_razones_sociales_id_cliente');
            $table->dropForeign('fk_razones_sociales_id_direccion_facturacion');
            $table->dropForeign('fk_razones_sociales_id_forma_pago');
            $table->dropForeign('fk_razones_sociales_id_metodo_pago');
            $table->dropForeign('fk_razones_sociales_id_regimen_fiscal');
            $table->dropForeign('fk_razones_sociales_uso_cfdi');
        });
    }
};
