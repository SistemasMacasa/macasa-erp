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
            $table->renameColumn('cuota_marginal', 'cuota_marginal_facturacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('metas_ventas', function (Blueprint $table) {
            $table->renameColumn('cuota_marginal_facturacion', 'cuota_marginal');
        });
    }
};
