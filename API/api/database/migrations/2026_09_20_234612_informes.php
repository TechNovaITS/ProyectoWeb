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
        Schema::create('asigna', function (Blueprint $table) {
            $table->unsignedinteger('ci_usuario')->references('ci')->on('usuarios')->primary();
            $table->foreignid('id_alerta')->constrained('alertas');
            $table->timestamps();
        });

        Schema::create('informes', function (Blueprint $table) {
            $table->id();
            $table->unsignedinteger('ci_usuario')->references('ci_usuario')->on('asigna');
            $table->foreignid('id_alerta')->references('id_alerta')->on('asigna');
            $table->text('causa');
            $table->text('resolucion');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
