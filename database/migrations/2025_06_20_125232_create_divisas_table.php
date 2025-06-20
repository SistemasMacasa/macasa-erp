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
        Schema::create('divisas', function (Blueprint $table) {
            $table->integer('id_divisa', true);
            $table->string('nombre', 100)->nullable();
            $table->string('nomenclatura', 10)->nullable();
            $table->decimal('tipo_cambio', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('divisas');
    }
};
