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
    Schema::table('usuarios', function (Blueprint $table) {
        $table->timestamp('fecha_baja')->nullable()->after('archivado');
    });
}

public function down()
{
    Schema::table('usuarios', function (Blueprint $table) {
        $table->dropColumn('fecha_baja');
    });
}

};
