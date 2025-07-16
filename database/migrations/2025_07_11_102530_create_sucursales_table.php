<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sucursales', function (Blueprint $table) {
            $table->id('id_sucursal');
            $table->string('nombre');
            $table->unsignedBigInteger('id_segmento');

            $table->foreign('id_segmento')
                ->references('id_segmento')
                ->on('segmentos')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('sucursales', function (Blueprint $table) {
            $table->dropForeign(['id_segmento']);
        });

        Schema::dropIfExists('sucursales');
    }
};
