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
        $intel = Category::where('name', 'Intel')->first();
        $amd = Category::where('name', 'AMD')->first();
        $nvidia = Category::where('name', 'NVIDIA')->first();
        $ddr4 = Category::where('name', 'DDR4')->first();
        $nvme = Category::where('name', 'SSD NVMe')->first();

        Component::create([
            'category_id' => $intel->id,
            'nombre' => 'Core i9-13900K',
            'marca' => 'Intel',
            'modelo' => 'i9-13900K',
            'precio' => 12500.00,
            'stock' => 10,
            'especificaciones' => [
                'núcleos' => 24,
                'hilos' => 32,
                'frecuencia_base' => '3.0 GHz',
                'frecuencia_turbo' => '5.8 GHz',
                'socket' => 'LGA1700',
                'tdp' => '125W',
            ],
        ]);

        Component::create([
            'category_id' => $amd->id,
            'nombre' => 'Ryzen 9 7950X',
            'marca' => 'AMD',
            'modelo' => '7950X',
            'precio' => 11800.00,
            'stock' => 15,
            'especificaciones' => [
                'núcleos' => 16,
                'hilos' => 32,
                'frecuencia_base' => '4.5 GHz',
                'frecuencia_turbo' => '5.7 GHz',
                'socket' => 'AM5',
                'tdp' => '170W',
            ],
        ]);

        Component::create([
            'category_id' => $nvidia->id,
            'nombre' => 'GeForce RTX 4090',
            'marca' => 'ASUS',
            'modelo' => 'ROG Strix OC',
            'precio' => 45000.00,
            'stock' => 5,
            'especificaciones' => [
                'vram' => '24GB GDDR6X',
                'interfaz' => '384-bit',
                'puertos' => ['HDMI 2.1a', 'DisplayPort 1.4a'],
                'recomendada_psu' => '1000W',
            ],
        ]);

        Component::create([
            'category_id' => $ddr4->id,
            'nombre' => 'Vengeance LPX 16GB',
            'marca' => 'Corsair',
            'modelo' => 'CMK16GX4M2B3200C16',
            'precio' => 1200.00,
            'stock' => 50,
            'especificaciones' => [
                'capacidad' => '16GB (2x8GB)',
                'frecuencia' => '3200MHz',
                'latencia' => 'CL16',
                'voltaje' => '1.35V',
            ],
        ]);

        Component::create([
            'category_id' => $nvme->id,
            'nombre' => '980 PRO 1TB',
            'marca' => 'Samsung',
            'modelo' => 'MZ-V8P1T0BW',
            'precio' => 2500.00,
            'stock' => 30,
            'especificaciones' => [
                'capacidad' => '1TB',
                'interfaz' => 'PCIe Gen 4.0 x4',
                'lectura_secuencial' => '7000 MB/s',
                'escritura_secuencial' => '5000 MB/s',
            ],
        ]);
    }
}
