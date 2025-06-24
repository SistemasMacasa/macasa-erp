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
            $table->dropColumn(['clave', 'nombre']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estados', function (Blueprint $table) {
            $table->string('clave', 100)->nullable(); // Ajusta el tipo según lo original
            $table->string('nombre', 100)->nullable(); // Igual aquí
        });
    }
};
