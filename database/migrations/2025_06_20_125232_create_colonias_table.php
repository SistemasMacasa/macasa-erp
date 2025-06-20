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
        Schema::create('colonias', function (Blueprint $table) {
            $table->string('d_codigo', 10)->index('ix_cp');
            $table->string('d_asenta', 100)->nullable();
            $table->string('d_tipo_asenta', 50)->nullable();
            $table->string('D_mnpio', 100)->nullable();
            $table->string('d_estado', 100)->nullable();
            $table->string('d_ciudad', 100)->nullable();
            $table->string('d_CP', 10)->nullable();
            $table->string('c_estado', 10)->nullable();
            $table->string('c_oficina', 10)->nullable();
            $table->string('c_tipo_asenta', 10)->nullable();
            $table->string('c_mnpio', 10)->nullable();
            $table->string('id_asenta_cpcons', 20);
            $table->string('d_zona', 20)->nullable();
            $table->string('c_cve_ciudad', 10)->nullable();

            $table->primary(['d_codigo', 'id_asenta_cpcons']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colonias');
    }
};
