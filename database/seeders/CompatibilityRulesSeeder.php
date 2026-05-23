<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompatibilityRulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\CompatibilityRule::insert([
            // AM5 CPU + DDR5 RAM (COMPATIBLE)
            [
                'component_type_from' => 'procesador',
                'spec_from' => 'AM5',
                'component_type_to' => 'ram',
                'spec_to' => 'DDR5',
                'compatible' => true,
                'message' => 'Compatible: AM5 requiere DDR5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // AM5 CPU + DDR4 RAM (INCOMPATIBLE)
            [
                'component_type_from' => 'procesador',
                'spec_from' => 'AM5',
                'component_type_to' => 'ram',
                'spec_to' => 'DDR4',
                'compatible' => false,
                'message' => '❌ Error: Ryzen AM5 no soporta DDR4. Requiere DDR5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // AM4 CPU + DDR4 RAM (COMPATIBLE)
            [
                'component_type_from' => 'procesador',
                'spec_from' => 'AM4',
                'component_type_to' => 'ram',
                'spec_to' => 'DDR4',
                'compatible' => true,
                'message' => 'Compatible: AM4 requiere DDR4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // AM4 CPU + DDR5 RAM (INCOMPATIBLE)
            [
                'component_type_from' => 'procesador',
                'spec_from' => 'AM4',
                'component_type_to' => 'ram',
                'spec_to' => 'DDR5',
                'compatible' => false,
                'message' => '❌ Error: Ryzen AM4 no soporta DDR5. Requiere DDR4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // AM5 CPU + AM5 Motherboard (COMPATIBLE)
            [
                'component_type_from' => 'procesador',
                'spec_from' => 'AM5',
                'component_type_to' => 'motherboard',
                'spec_to' => 'AM5',
                'compatible' => true,
                'message' => 'Compatible: Socket AM5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // AM5 CPU + AM4 Motherboard (INCOMPATIBLE)
            [
                'component_type_from' => 'procesador',
                'spec_from' => 'AM5',
                'component_type_to' => 'motherboard',
                'spec_to' => 'AM4',
                'compatible' => false,
                'message' => '❌ Error: CPU AM5 no cabe en placa AM4. Socket incompatible',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // AM4 CPU + AM4 Motherboard (COMPATIBLE)
            [
                'component_type_from' => 'procesador',
                'spec_from' => 'AM4',
                'component_type_to' => 'motherboard',
                'spec_to' => 'AM4',
                'compatible' => true,
                'message' => 'Compatible: Socket AM4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // AM4 CPU + AM5 Motherboard (INCOMPATIBLE)
            [
                'component_type_from' => 'procesador',
                'spec_from' => 'AM4',
                'component_type_to' => 'motherboard',
                'spec_to' => 'AM5',
                'compatible' => false,
                'message' => '❌ Error: CPU AM4 no cabe en placa AM5. Socket incompatible',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Motherboard AM5 + DDR5 RAM (COMPATIBLE)
            [
                'component_type_from' => 'motherboard',
                'spec_from' => 'AM5',
                'component_type_to' => 'ram',
                'spec_to' => 'DDR5',
                'compatible' => true,
                'message' => 'Compatible: AM5 soporta DDR5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Motherboard AM5 + DDR4 RAM (INCOMPATIBLE)
            [
                'component_type_from' => 'motherboard',
                'spec_from' => 'AM5',
                'component_type_to' => 'ram',
                'spec_to' => 'DDR4',
                'compatible' => false,
                'message' => '❌ Error: Placa AM5 no acepta DDR4. Requiere DDR5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Motherboard AM4 + DDR4 RAM (COMPATIBLE)
            [
                'component_type_from' => 'motherboard',
                'spec_from' => 'AM4',
                'component_type_to' => 'ram',
                'spec_to' => 'DDR4',
                'compatible' => true,
                'message' => 'Compatible: AM4 soporta DDR4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Motherboard AM4 + DDR5 RAM (INCOMPATIBLE)
            [
                'component_type_from' => 'motherboard',
                'spec_from' => 'AM4',
                'component_type_to' => 'ram',
                'spec_to' => 'DDR5',
                'compatible' => false,
                'message' => '❌ Error: Placa AM4 no acepta DDR5. Requiere DDR4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
