<?php

namespace Database\Seeders;

use App\Models\Reason;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reasons =[
            //Razones para ingreso.
            [
                'name' => 'Ajuste por inventario',
                'type' => 1,
            ],
            [
                'name' => 'Devolucion del cliente',
                'type' => 1,
            ],
            [
                'name' => 'Produccion terminada',
                'type' => 1,
            ],
            [
                'name' => 'Error en salida anterior',
                'type' => 1,
            ],
            //Razones para salida.
            [
                'name' => 'Deterioro material',
                'type' => 2,
            ],
            [
                'name' => 'Devolucion del cliente',
                'type' => 2,
            ],
            [
                'name' => 'Consumo interno',
                'type' => 2,
            ],
            [
                'name' => 'Caducidad',
                'type' => 2,
            ],
        ];

        foreach($reasons as $reason){
            Reason::create($reason);
        }
    }
}
