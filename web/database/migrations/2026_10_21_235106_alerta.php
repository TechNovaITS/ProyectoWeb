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
            $table->string('estado',10)->nullable();
            $table->string('titulo',100)->nullable();
            $table->string('alerta',100)->nullable();
            $table->foreignId('dispositivo_id')->constrained('dispositivos');
            $table->string('job',100)->nullable();
            $table->string('departamento',100)->nullable();
            $table->string('valor_actual',100)->nullable();
            $table->dateTime('iniciado_en')->nullable();
            $table->dateTime('finalizado_en')->nullable();
            $table->string('url_grafana', 255)->nullable();
            $table->enum('estado_seguimiento', ['Pendiente', 'En Progreso', 'Cerrado'])->default('Pendiente');
            $table->softDeletes();
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
