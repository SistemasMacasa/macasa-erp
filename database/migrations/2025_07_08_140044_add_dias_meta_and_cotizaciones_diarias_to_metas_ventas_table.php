<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('metas_ventas', function (Blueprint $table) {
            $table->unsignedTinyInteger('dias_meta')->nullable()->after('cuota_marginal_facturacion');
            $table->unsignedSmallInteger('cotizaciones_diarias')->nullable()->after('cuota_cotizaciones');
        });
    }

    public function down(): void
    {
        Schema::table('metas_ventas', function (Blueprint $table) {
            $table->dropColumn(['dias_meta', 'cotizaciones_diarias']);
        });
    }
};
