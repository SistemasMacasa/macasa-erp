<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdSegmentoToClientesTable extends Migration
{
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->unsignedBigInteger('id_segmento')->nullable()->after('sector');

            $table->foreign('id_segmento')
                ->references('id_segmento')
                ->on('segmentos')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign(['id_segmento']);
            $table->dropColumn('id_segmento');
        });
    }
}

