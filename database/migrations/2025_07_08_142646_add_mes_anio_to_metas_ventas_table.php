<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('metas_ventas', function (Blueprint $table) {
            $table->unsignedTinyInteger('mes')->after('id_usuario'); // 1-12
            $table->unsignedSmallInteger('anio')->after('mes');      // ejemplo: 2025
        });
    }

    public function down()
    {
        Schema::table('metas_ventas', function (Blueprint $table) {
            $table->dropColumn(['mes', 'anio']);
        });
    }
};
