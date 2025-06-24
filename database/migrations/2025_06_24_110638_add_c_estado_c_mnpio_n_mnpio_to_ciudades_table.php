<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ciudades', function (Blueprint $table) {
            $table->string('c_estado', 10)->nullable();
            $table->string('c_mnpio', 10)->nullable();
            $table->string('n_mnpio', 100)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('ciudades', function (Blueprint $table) {
            $table->dropColumn(['c_estado', 'c_mnpio', 'n_mnpio']);
        });
    }
};
