<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = [
            [
                'name' => 'Almacen Principal',
                'location' => 'Calle Principal 123, ciudad, Colombia',
            ],
            // ... más almacenes
        ];

        foreach ($warehouses as $warehouse) {
            Warehouse::firstOrCreate(
                ['name' => $warehouse['name']],
                $warehouse
            );
        }
    }
}
