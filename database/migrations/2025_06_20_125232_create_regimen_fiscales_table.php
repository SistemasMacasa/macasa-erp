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
        Schema::create('regimen_fiscales', function (Blueprint $table) {
            $table->integer('id_regimen_fiscal', true);
            $table->string('clave', 50)->nullable();
            $table->string('nombre', 100)->nullable();
            $table->enum('tipo_persona', ['Física', 'Moral', 'Ambas'])->nullable()->default('Física');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regimen_fiscales');
    }
};
