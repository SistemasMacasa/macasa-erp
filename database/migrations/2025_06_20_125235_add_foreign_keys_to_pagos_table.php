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
        Schema::table('pagos', function (Blueprint $table) {
            $table->foreign(['id_cliente'], 'fk_pagos_id_cliente')->references(['id_cliente'])->on('clientes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_divisa'], 'fk_pagos_id_divisa')->references(['id_divisa'])->on('divisas')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_forma_pago'], 'fk_pagos_id_forma_pago')->references(['id_forma_pago'])->on('forma_pagos')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_metodo_pago'], 'fk_pagos_id_metodo_pago')->references(['id_metodo_pago'])->on('metodo_pagos')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign('fk_pagos_id_cliente');
            $table->dropForeign('fk_pagos_id_divisa');
            $table->dropForeign('fk_pagos_id_forma_pago');
            $table->dropForeign('fk_pagos_id_metodo_pago');
        });
    }
};
