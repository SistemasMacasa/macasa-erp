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
        Schema::create('razones_sociales', function (Blueprint $table) {
            $table->integer('id_razon_social', true);
            $table->string('nombre', 100)->nullable();
            $table->integer('id_cliente')->nullable()->index('fk_razones_sociales_id_cliente');
            $table->string('RFC', 13)->nullable();
            $table->integer('id_metodo_pago')->nullable()->index('fk_razones_sociales_id_metodo_pago');
            $table->integer('id_forma_pago')->nullable()->index('fk_razones_sociales_id_forma_pago');
            $table->integer('id_regimen_fiscal')->nullable()->index('fk_razones_sociales_id_regimen_fiscal');
            $table->integer('id_direccion_facturacion')->nullable()->index('fk_razones_sociales_id_direccion_facturacion');
            $table->integer('id_uso_cfdi')->nullable()->index('fk_razones_sociales_uso_cfdi');
            $table->decimal('saldo', 10)->nullable();
            $table->decimal('limite_credito', 10)->nullable();
            $table->integer('dias_credito')->nullable();
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
        Schema::dropIfExists('razones_sociales');
    }
};
