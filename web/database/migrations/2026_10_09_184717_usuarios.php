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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->integer('ci')->unsigned()->primary();
            $table->string('email', 150)->unique();
            $table->string('password', 60);
            $table->enum('rol', ['Administrador', 'Operador', 'Auditor', 'Recovery']);
            $table->foreignId('id_departamento')->constrained('departamentos');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
