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
        Schema::create('informes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_alerta');
            $table->text('causa');
            $table->text('resolucion');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('id_alerta')->references('id_alerta')->on('asigna');
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
