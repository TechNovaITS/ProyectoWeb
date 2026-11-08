<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dashboard;

class Dashboards extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dashboard::create([
            'nombre' => 'Dashboard 1',
            'url' => 'http://localhost:3000/d/adnm6vb/metricas-equipos',
        ]);
    }
}
