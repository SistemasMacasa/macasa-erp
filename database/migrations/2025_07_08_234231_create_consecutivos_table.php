<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('consecutivos', function (Blueprint $table) {
            $table->id();                              // id (PK autoincremental)
            $table->string('tipo')->unique();          // ej. 'cotizaciones', 'pedidos', etc.
            $table->string('prefijo', 10);             // ej. 'MC2'
            $table->unsignedBigInteger('valor_actual')->default(0);
            $table->timestamps();                      // created_at / updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consecutivos');
    }
};
