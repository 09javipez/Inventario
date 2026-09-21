<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Warehouse::create([
            'name' => 'Almacen Principal',
            'location' => 'Calle Principal 123, cuidad, Colombia',
        ]);
        Warehouse::create([
            'name' => 'Almacen Secundario',
            'location' => 'Calle secundaria 12, yopal, Colombia',
        ]);
    }
}
