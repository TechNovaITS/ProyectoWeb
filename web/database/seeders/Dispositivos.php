<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dispositivo;

class Dispositivos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dispositivo::create([
            'nombre' => 'THINKPAD',
            'ip' => '192.168.1.1'
        ]);
    }
}
