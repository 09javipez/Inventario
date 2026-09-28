<?php

namespace Database\Seeders;

use App\Models\Category;
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
            Category::create($category);
        }
    }
}
