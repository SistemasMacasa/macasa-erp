<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {

            // 👇  use unsignedInteger()  (mismo tipo que contactos.id_contacto)
            if (!Schema::hasColumn('cotizaciones', 'id_contacto_entrega')) {
                $table->unsignedInteger('id_contacto_entrega')
                    ->after('id_direccion_entrega');   // NOT NULL si lo necesitas

                $table->foreign('id_contacto_entrega')
                    ->references('id_contacto')->on('contactos')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();              // o ->cascadeOnDelete()
            }
        });

    }

    public function down(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->dropForeign(['id_contacto_entrega']);
            $table->dropColumn('id_contacto_entrega');
        });
    }
};
