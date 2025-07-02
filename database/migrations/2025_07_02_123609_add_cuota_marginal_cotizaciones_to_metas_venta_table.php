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
        Schema::table('metas_ventas', function (Blueprint $table) {
            $table->decimal('cuota_marginal_cotizaciones', 10, 2)->after('cuota_cotizaciones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('metas_ventas', function (Blueprint $table) {
            $table->dropColumn('cuota_marginal_cotizaciones');
        });
    }
};
