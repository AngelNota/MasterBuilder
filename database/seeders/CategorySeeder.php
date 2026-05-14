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
        $categories = [
            'Procesadores' => ['Intel', 'AMD'],
            'Tarjetas de Video' => ['NVIDIA', 'AMD'],
            'Memoria RAM' => ['DDR4', 'DDR5'],
            'Almacenamiento' => ['SSD NVMe', 'SSD SATA', 'HDD'],
            'Tarjetas Madre' => ['Socket AM4', 'Socket AM5', 'Socket LGA1700'],
            'Fuentes de Poder' => ['80+ Bronze', '80+ Gold', '80+ Platinum'],
            'Gabinetes' => ['Mid Tower', 'Full Tower', 'Mini ITX'],
        ];

        foreach ($categories as $parentName => $subcategories) {
            $parent = Category::create(['name' => $parentName]);
            foreach ($subcategories as $subName) {
                Category::create([
                    'name' => $subName,
                    'parent_id' => $parent->id,
                ]);
            }
        }
    }
}
