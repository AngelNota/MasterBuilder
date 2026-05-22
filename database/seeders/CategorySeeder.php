<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Solo creamos las categorías maestras, el resto se maneja vía JSON y Marcas
        $categories = [
            'Procesadores',
            'Tarjetas de Video',
            'Memoria RAM',
            'Almacenamiento',
            'Tarjetas Madre',
            'Fuentes de Poder',
            'Gabinetes',
            'Ventilación'
        ];

        foreach ($categories as $categoryName) {
            Category::firstOrCreate([
                'name' => $categoryName,
                'parent_id' => null
            ]);
        }
    }
}