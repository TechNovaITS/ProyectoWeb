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
            $table->foreignId('id_alerta')->primary()->constrained('alertas');
            $table->unsignedInteger('ci_usuario');
            $table->foreign('ci_usuario')->references('ci')->on('usuarios');
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
