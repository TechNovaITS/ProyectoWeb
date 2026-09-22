<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        

        Usuario::create(
            [
                'ci'              => 12345678,
                'email'           => 'infrastructure@example.com',
                'password'        => Hash::make('password'),
                'rol'             => 'Administrador',
                'id_departamento' => 1,
            ]);
        Usuario::create([
                'ci'              => 87654321,
                'email'           => 'development@example.com',
                'password'        => Hash::make('password'),
                'rol'             => 'Operador',
                'id_departamento' => 2,
            ]);
            Usuario::create([
                'ci'              => 11223344,
                'email'           => 'support@example.com',
                'password'        => Hash::make('password'),
                'rol'             => 'Auditor',
                'id_departamento' => 3,
            ]);
            Usuario::create(
            [
                'ci'              => 55667788,
                'email'           => 'management@example.com',
                'password'        => Hash::make('password'),
                'rol'             => 'Recovery',
                'id_departamento' => 1,
            ]);
    }
}
