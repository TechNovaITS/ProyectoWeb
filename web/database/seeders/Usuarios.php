<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class Usuarios extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Usuario::create(
            [
                'ci'              => 12345678,
                'email'           => 'infrastructure@cutcsa.com',
                'password'        => Hash::make('Password1'),
                'rol'             => 'Administrador',
                'id_departamento' => 1,
            ]);
        Usuario::create([
                'ci'              => 87654321,
                'email'           => 'development@cutcsa.com',
                'password'        => Hash::make('Password1'),
                'rol'             => 'Operador',
                'id_departamento' => 2,
            ]);
            Usuario::create([
                'ci'              => 11223344,
                'email'           => 'support@cutcsa.com',
                'password'        => Hash::make('Password1'),
                'rol'             => 'Auditor',
                'id_departamento' => 3,
            ]);
            Usuario::create(
            [
                'ci'              => 55667788,
                'email'           => 'management@cutcsa.com',
                'password'        => Hash::make('Password1'),
                'rol'             => 'Recovery',
                'id_departamento' => 1,
            ]);
    }
}
