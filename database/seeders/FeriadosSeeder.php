<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeriadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \App\Models\Feriado::create([
            'fecha' => '2025-01-01',  // Año Nuevo
            'descripcion' => 'Año Nuevo',
        ]);
        \App\Models\Feriado::create([
            'fecha' => '2025-12-25',  // Navidad
            'descripcion' => 'Navidad',
        ]);
        // Agrega más feriados según sea necesario
    }

}
