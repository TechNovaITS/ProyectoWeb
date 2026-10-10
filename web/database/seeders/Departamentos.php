<?php

namespace Database\Seeders;

use App\Models\Departamento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Departamentos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Departamento::create(['nom_departamento' => 'Infraestructura']);
        Departamento::create(['nom_departamento' => 'Desarrollo']);
        Departamento::create(['nom_departamento' => 'Soporte']);
    }
}
