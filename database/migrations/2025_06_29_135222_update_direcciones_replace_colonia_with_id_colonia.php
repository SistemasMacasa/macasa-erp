<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('direcciones', function (Blueprint $table) {
            // Agregamos la nueva columna FK
            $table->unsignedBigInteger('id_colonia')->nullable()->after('id_cliente');

            // Eliminamos la columna antigua que contenía el texto
            $table->dropColumn('colonia');

            // (Opcional) puedes agregar una foreign key si deseas
            // $table->foreign('id_colonia')->references('id_colonia')->on('colonias');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('direcciones', function (Blueprint $table) {
            // Revertimos los cambios
            $table->string('colonia', 100)->nullable();
            $table->dropColumn('id_colonia');
        });
    }

};
