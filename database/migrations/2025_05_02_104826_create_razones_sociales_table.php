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
            $table->id();
            $table->timestamps();
        });

        Schema::create('razones_sociales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cliente');
            $table->string('nombre',100);
            $table->string('rfc',13);
            $table->unsignedBigInteger('id_metodo_pago')->nullable();
            $table->unsignedBigInteger('id_forma_pago')->nullable();
            $table->unsignedBigInteger('id_regimen_fiscal')->nullable();
            $table->unsignedBigInteger('id_direccion_facturacion')->nullable();
            $table->decimal('limite_credito',12,2)->default(0);
            $table->integer('dias_credito')->default(0);
            $table->decimal('saldo',12,2)->default(0);
            // FKs
            $table->foreign('id_cliente')->references('id_cliente')->on('clientes');
            $table->foreign('id_direccion_facturacion')->references('id_direccion')->on('direcciones');
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
