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
        Schema::table('razones_sociales', function (Blueprint $table) {
            $table->text('notas_facturacion')->nullable()->after('id_regimen_fiscal');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('razones_sociales', function (Blueprint $table) {
            $table->dropColumn('notas_facturacion');
        });
    }
};
