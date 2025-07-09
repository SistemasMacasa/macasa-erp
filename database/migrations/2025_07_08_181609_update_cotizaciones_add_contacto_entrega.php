<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactoEntregaToCotizaciones extends Migration
{
    public function up(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            // Agrega nueva columna sin eliminar la anterior
            $table->unsignedBigInteger('id_contacto_entrega')->nullable()->after('id_razon_social');

            // Establece la FK hacia 'contactos'
            $table->foreign('id_contacto_entrega')
                  ->references('id_contacto')->on('contactos')
                  ->nullOnDelete(); // Para no romper si se elimina el contacto
        });
    }

    public function down(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->dropForeign(['id_contacto_entrega']);
            $table->dropColumn('id_contacto_entrega');
        });
    }
}
