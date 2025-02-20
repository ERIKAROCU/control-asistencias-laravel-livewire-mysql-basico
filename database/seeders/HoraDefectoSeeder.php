<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HoraDefectoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\HoraDefecto::create([
            'hora_entrada_defecto' => '08:00:00',
            'hora_salida_defecto' => '17:00:00',
        ]);
    }
}
