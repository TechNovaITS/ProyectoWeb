<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        

        DB::table('usuarios')->insert([
        [
            'ci'              => 12345678,
            'email'           => 'infrastructure@example.com',
            'password'        => Hash::make('password'),
            'rol'             => 'Administrador',
            'departamento_id' => 1
        ],
        [
            'ci'              => 87654321,
            'email'           => 'development@example.com',
            'password'        => Hash::make('password'),
            'rol'             => 'Operador',
            'departamento_id' => 2
        ],
        [
            'ci'              => 11223344,
            'email'           => 'support@example.com',
            'password'        => Hash::make('password'),
            'rol'             => 'Auditor',
            'departamento_id' => 3
        ],
        [
            'ci'              => 44332211,
            'email'           => 'recovery@example.com',
            'password'        => Hash::make('password'),
            'rol'             => 'Recovery',
            'departamento_id' => 1
        ],
    ]);
    }
}
