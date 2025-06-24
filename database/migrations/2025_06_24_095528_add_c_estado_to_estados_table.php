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
        Schema::table('estados', function (Blueprint $table) {
            $table->string('c_estado', 10)->after('id_estado');
            $table->string('d_estado');
        });
    }

    public function down(): void
    {
        Schema::table('estados', function (Blueprint $table) {
            $table->dropColumn('c_estado');
            $table->dropColumn('d_estado');

        });
    }
};
