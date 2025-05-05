<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('direcciones', function (Blueprint $table) {
            $table->id('id_direccion');
            $table->unsignedBigInteger('id_cliente')->nullable();
            $table->enum('tipo',['entrega','facturacion']);
            $table->string('nombre',100)->nullable();
            $table->string('calle',100);
            $table->string('num_ext',20)->nullable();
            $table->string('num_int',20)->nullable();
            $table->string('colonia',100)->nullable();
            $table->unsignedBigInteger('id_ciudad')->nullable();
            $table->unsignedBigInteger('id_estado')->nullable();
            $table->unsignedBigInteger('id_pais')->nullable();
            $table->string('cp',10)->nullable();
            // FKs
            $table->foreign('id_cliente')->references('id_cliente')->on('clientes');
            $table->foreign('id_ciudad')->references('id_ciudad')->on('ciudades');
            $table->foreign('id_estado')->references('id_estado')->on('estados');
            $table->foreign('id_pais')->references('id_pais')->on('paises');
        });
    }

    public function down()
    {
        Schema::dropIfExists('direcciones');
    }
};
