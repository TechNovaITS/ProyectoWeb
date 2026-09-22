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
        Schema::create('alertas', function (Blueprint $table) {
            $table->id();
            $table->foreignID('id_dispositivo')->constrained('dispositivos');
            $table->string('entorno',50);
            $table->string('job', 100);
            $table->string('estado_alerta',30);
            $table->string('nom_alerta', 100);
            $table->string('contenedor_alerta',100);
            $table->text('descripcion');
            $table->dateTime('inicio_alerta');
            $table->dateTime('fin_alerta');
            $table->string('url_alerta', 500);
            $table->string('severidad', 30);
            $table->enum('estado', ['Pendiente', 'En proceso',  'Cerrado'])->default('Pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alertas');
    }
};
