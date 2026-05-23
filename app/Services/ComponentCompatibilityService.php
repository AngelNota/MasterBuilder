<?php

namespace App\Services;

use App\Models\Component;
use App\Models\CompatibilityRule;

class ComponentCompatibilityService
{
    /**
     * Obtiene componentes compatibles para una categoría
     */
    public function getCompatibleComponents(int $categoryId, array $selectedIds = [])
    {
        $selectedComponents = Component::whereIn('id', $selectedIds)->get();

        $components = Component::where('category_id', $categoryId)
            ->where('stock', '>', 0)
            ->get();

        // Si no hay selecciones previas, devuelve todos
        if ($selectedComponents->isEmpty()) {
            return $components;
        }

        // Filtrar componentes incompatibles
        return $components->filter(function ($component) use ($selectedComponents) {
            foreach ($selectedComponents as $selected) {
                if (!$this->isCompatible($selected, $component)) {
                    return false;
                }
            }
            return true;
        });
    }

    /**
     * Verifica si dos componentes son compatibles
     */
    public function isCompatible(Component $component1, Component $component2): bool
    {
        $specs1 = $component1->especificaciones ?? [];
        $specs2 = $component2->especificaciones ?? [];

        $cat1 = strtolower(str_replace('Á', 'A', str_replace('á', 'a', $component1->category->name)));
        $cat2 = strtolower(str_replace('Á', 'A', str_replace('á', 'a', $component2->category->name)));

        // Obtener los tipos de categoría (procesador, motherboard, ram)
        $type1 = $this->getCategoryType($component1);
        $type2 = $this->getCategoryType($component2);

        if (!$type1 || !$type2) {
            return true; // Si no podemos determinar, permitir
        }

        // Buscar reglas de compatibilidad
        $rules = CompatibilityRule::where(function ($query) use ($type1, $type2, $specs1, $specs2) {
            $query->where('component_type_from', $type1)
                ->where('spec_from', $specs1['socket'] ?? $specs1['tipo'] ?? null)
                ->where('component_type_to', $type2);
        })->get();

        foreach ($rules as $rule) {
            if ($rule->spec_to === ($specs2['socket'] ?? $specs2['tipo'] ?? null)) {
                return $rule->compatible;
            }
        }

        return true; // Si no hay regla, permitir
    }

    /**
     * Obtiene el tipo de categoría normalizado
     */
    private function getCategoryType(Component $component): ?string
    {
        $name = strtolower($component->category->name);

        if (str_contains($name, 'procesador') || str_contains($name, 'cpu')) {
            return 'procesador';
        }
        if (str_contains($name, 'ram') || str_contains($name, 'memoria')) {
            return 'ram';
        }
        if (str_contains($name, 'motherboard') || str_contains($name, 'placa') || str_contains($name, 'tarjeta')) {
            return 'motherboard';
        }

        return null;
    }

    /**
     * Valida una cotización completa
     */
    public function validateQuote(array $componentIds): array
    {
        $components = Component::whereIn('id', $componentIds)->get();
        $errors = [];

        for ($i = 0; $i < count($components); $i++) {
            for ($j = $i + 1; $j < count($components); $j++) {
                if (!$this->isCompatible($components[$i], $components[$j])) {
                    $errors[] = "{$components[$i]->nombre} es incompatible con {$components[$j]->nombre}";
                }
            }
        }

        return $errors;
    }
}
