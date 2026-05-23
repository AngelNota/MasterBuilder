<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Component;
use Illuminate\Database\Seeder;

class ComponentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener o crear categorías
        $procesador = Category::firstOrCreate(
            ['name' => 'Procesador'],
            ['parent_id' => null]
        );

        $procesadorAM5 = Category::firstOrCreate(
            ['name' => 'AM5 (Ryzen 7000/9000)'],
            ['parent_id' => $procesador->id]
        );

        $procesadorAM4 = Category::firstOrCreate(
            ['name' => 'AM4 (Ryzen 3000/5000)'],
            ['parent_id' => $procesador->id]
        );

        $ram = Category::firstOrCreate(
            ['name' => 'Memoria RAM'],
            ['parent_id' => null]
        );

        $ramDDR5 = Category::firstOrCreate(
            ['name' => 'DDR5'],
            ['parent_id' => $ram->id]
        );

        $ramDDR4 = Category::firstOrCreate(
            ['name' => 'DDR4'],
            ['parent_id' => $ram->id]
        );

        $motherboard = Category::firstOrCreate(
            ['name' => 'Tarjeta Madre'],
            ['parent_id' => null]
        );

        $motherboardAM5 = Category::firstOrCreate(
            ['name' => 'Socket AM5'],
            ['parent_id' => $motherboard->id]
        );

        $motherboardAM4 = Category::firstOrCreate(
            ['name' => 'Socket AM4'],
            ['parent_id' => $motherboard->id]
        );

        // Componentes AM5
        Component::firstOrCreate(
            ['nombre' => 'Ryzen 7 9700X'],
            [
                'category_id' => $procesadorAM5->id,
                'marca' => 'AMD',
                'modelo' => '9700X',
                'precio' => 349.99,
                'stock' => 5,
                'especificaciones' => ['socket' => 'AM5', 'tdp' => 105],
            ]
        );

        // Componentes AM4
        Component::firstOrCreate(
            ['nombre' => 'Ryzen 5 5600X'],
            [
                'category_id' => $procesadorAM4->id,
                'marca' => 'AMD',
                'modelo' => '5600X',
                'precio' => 199.99,
                'stock' => 5,
                'especificaciones' => ['socket' => 'AM4', 'tdp' => 65],
            ]
        );

        // RAM DDR5
        Component::firstOrCreate(
            ['nombre' => 'Kingston DDR5 32GB 6000MHz'],
            [
                'category_id' => $ramDDR5->id,
                'marca' => 'Kingston',
                'modelo' => 'KF560C40BS-32',
                'precio' => 149.99,
                'stock' => 10,
                'especificaciones' => ['tipo' => 'DDR5', 'velocidad' => 6000, 'capacidad' => 32],
            ]
        );

        // RAM DDR4
        Component::firstOrCreate(
            ['nombre' => 'Corsair DDR4 32GB 3600MHz'],
            [
                'category_id' => $ramDDR4->id,
                'marca' => 'Corsair',
                'modelo' => 'CMK32GX4M2B3600C18',
                'precio' => 99.99,
                'stock' => 10,
                'especificaciones' => ['tipo' => 'DDR4', 'velocidad' => 3600, 'capacidad' => 32],
            ]
        );

        // Motherboard AM5
        Component::firstOrCreate(
            ['nombre' => 'ASUS ROG STRIX X870-E'],
            [
                'category_id' => $motherboardAM5->id,
                'marca' => 'ASUS',
                'modelo' => 'ROG STRIX X870-E-E GAMING WIFI',
                'precio' => 349.99,
                'stock' => 3,
                'especificaciones' => ['socket' => 'AM5', 'chipset' => 'X870-E'],
            ]
        );

        // Motherboard AM4
        Component::firstOrCreate(
            ['nombre' => 'ASUS ROG STRIX B550-F'],
            [
                'category_id' => $motherboardAM4->id,
                'marca' => 'ASUS',
                'modelo' => 'ROG STRIX B550-F GAMING',
                'precio' => 179.99,
                'stock' => 5,
                'especificaciones' => ['socket' => 'AM4', 'chipset' => 'B550'],
            ]
        );
    }
}
