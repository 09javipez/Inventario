<?php

namespace Database\Seeders;

use App\Models\category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronicos',
                'description' => 'Articulos electronicos',
            ],
            [
                'name' => 'Repuestos',
                'description' => 'Articulos Repuestos',
            ],
            [
               'name' => 'herramienta menor',
               'description' => 'Articulos de herramienta menor',
            ],
        ];
        foreach ($categories as $category) {
            category::create($category);
        }
    }
}
