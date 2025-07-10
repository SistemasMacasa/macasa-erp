<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->decimal('subtotal', 15, 2)->after('id_cliente')->nullable();
        });
    }

    public function down()
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->dropColumn('subtotal');
        });
    }

};
