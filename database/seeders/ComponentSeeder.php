<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Component;
use Illuminate\Database\Seeder;

class ComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Obtenemos las categorías que acabamos de crear
        $catProcesador = Category::where('name', 'Procesadores')->first();
        $catGrafica = Category::where('name', 'Tarjetas de Video')->first();
        $catRam = Category::where('name', 'Memoria RAM')->first();
        $catMotherboard = Category::where('name', 'Tarjetas Madre')->first();
        $catAlmacenamiento = Category::where('name', 'Almacenamiento')->first();

        // 2. Insertamos componentes con la nueva estructura JSON

        // PROCESADORES
        Component::create([
            'category_id' => $catProcesador->id,
            'nombre' => 'Ryzen 9 7950X',
            'marca' => 'AMD',
            'modelo' => '100-100000514WOF',
            'precio' => 11800.00,
            'stock' => 15,
            'especificaciones' => [
                'socket' => 'AM5',
            ],
        ]);

        Component::create([
            'category_id' => $catProcesador->id,
            'nombre' => 'Core i9-13900K',
            'marca' => 'Intel',
            'modelo' => 'BX8071513900K',
            'precio' => 12500.00,
            'stock' => 10,
            'especificaciones' => [
                'socket' => 'LGA1700',
            ],
        ]);

        // TARJETAS MADRE
        Component::create([
            'category_id' => $catMotherboard->id,
            'nombre' => 'ROG STRIX X870-E GAMING',
            'marca' => 'ASUS',
            'modelo' => 'X870-E',
            'precio' => 6500.00,
            'stock' => 5,
            'especificaciones' => [
                'socket' => 'AM5',
                'tipo_memoria' => 'DDR5'
            ],
        ]);

        // MEMORIA RAM
        Component::create([
            'category_id' => $catRam->id,
            'nombre' => 'Kingston Fury Beast 32GB 6000MHz',
            'marca' => 'Kingston',
            'modelo' => 'KF560C40BS-32',
            'precio' => 2800.00,
            'stock' => 20,
            'especificaciones' => [
                'tipo_memoria' => 'DDR5'
            ],
        ]);

        // TARJETAS DE VIDEO
        Component::create([
            'category_id' => $catGrafica->id,
            'nombre' => 'GeForce RTX 4090 ROG Strix',
            'marca' => 'ASUS',
            'modelo' => 'ROG-STRIX-RTX4090-O24G',
            'precio' => 45000.00,
            'stock' => 3,
            'especificaciones' => [
                'vram' => '24'
            ],
        ]);

        // ALMACENAMIENTO
        Component::create([
            'category_id' => $catAlmacenamiento->id,
            'nombre' => '980 PRO 1TB',
            'marca' => 'Samsung',
            'modelo' => 'MZ-V8P1T0BW',
            'precio' => 2500.00,
            'stock' => 30,
            'especificaciones' => [
                'tipo_almacenamiento' => 'NVME'
            ],
        ]);
    }
}