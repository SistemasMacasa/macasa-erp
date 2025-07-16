<?php
// database/migrations/2025_07_15_000000_rename_orden_en_venta_to_orden_de_venta.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->renameColumn('orden_en_venta', 'orden_de_venta');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->renameColumn('orden_de_venta', 'orden_en_venta');
        });
    }
};
