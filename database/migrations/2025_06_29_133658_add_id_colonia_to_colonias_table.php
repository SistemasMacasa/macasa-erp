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
        Schema::table('colonias', function (Blueprint $table) {
            // Agregamos id_colonia como autoincremental único, pero sin marcarlo como PK
            $table->unsignedBigInteger('id_colonia')->nullable()->after('id_asenta_cpcons');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('colonias', function (Blueprint $table) {
            $table->dropColumn('id_colonia');
        });
    }
};
