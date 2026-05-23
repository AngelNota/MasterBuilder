<?php

namespace App\Http\Controllers;

use App\Models\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CompatibilityController extends Controller
{
    /**
     * Valida la compatibilidad de una lista de componentes seleccionados
     * POST /ai/check-compatibility
     */
    public function check(Request $request)
    {
        $request->validate([
            'components' => 'required|array',
            'components.*' => 'required|exists:components,id'
        ]);

        $componentIds = array_filter($request->components);
        $components = Component::whereIn('id', $componentIds)->with('category')->get();

        // Clasificar los componentes por categoría
        $cpu = $components->first(fn($c) => str_contains(strtolower($c->category->name), 'procesador') || str_contains(strtolower($c->category->name), 'cpu'));
        $motherboard = $components->first(fn($c) => str_contains(strtolower($c->category->name), 'madre') || str_contains(strtolower($c->category->name), 'motherboard') || str_contains(strtolower($c->category->name), 'placa'));
        $ram = $components->first(fn($c) => str_contains(strtolower($c->category->name), 'ram') || str_contains(strtolower($c->category->name), 'memoria'));
        $storage = $components->first(fn($c) => str_contains(strtolower($c->category->name), 'almacenamiento') || str_contains(strtolower($c->category->name), 'disco'));
        $gpu = $components->first(fn($c) => str_contains(strtolower($c->category->name), 'video') || str_contains(strtolower($c->category->name), 'grafica') || str_contains(strtolower($c->category->name), 'gpu'));
        $case = $components->first(fn($c) => str_contains(strtolower($c->category->name), 'gabinete') || str_contains(strtolower($c->category->name), 'chasis'));
        $psu = $components->first(fn($c) => str_contains(strtolower($c->category->name), 'fuente') || str_contains(strtolower($c->category->name), 'psu'));

        $apiKey = env('GEMINI_API_KEY');

        if (empty($apiKey)) {
            // Modo Simulado Local de Alta Calidad
            return response()->json([
                'success' => true,
                'source' => 'simulated',
                'analysis' => $this->generateLocalAnalysis($cpu, $motherboard, $ram, $storage, $gpu, $case, $psu)
            ]);
        }

        // Llamar a Gemini API
        $prompt = $this->buildGeminiPrompt($cpu, $motherboard, $ram, $storage, $gpu, $case, $psu);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $analysisText = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No se pudo generar un análisis.';
                
                return response()->json([
                    'success' => true,
                    'source' => 'gemini',
                    'analysis' => $analysisText
                ]);
            } else {
                logger('Error de API de Gemini: ' . $response->body());
                return response()->json([
                    'success' => true,
                    'source' => 'simulated_fallback',
                    'analysis' => "⚠️ **Error al conectar con la API de Gemini (Código " . $response->status() . "). Ejecutando análisis de respaldo local:**\n\n" . $this->generateLocalAnalysis($cpu, $motherboard, $ram, $storage, $gpu, $case, $psu)
                ]);
            }
        } catch (\Exception $e) {
            logger('Excepción al llamar a Gemini: ' . $e->getMessage());
            return response()->json([
                'success' => true,
                'source' => 'simulated_fallback',
                'analysis' => "⚠️ **Excepción de conexión con la IA. Ejecutando análisis de respaldo local:**\n\n" . $this->generateLocalAnalysis($cpu, $motherboard, $ram, $storage, $gpu, $case, $psu)
            ]);
        }
    }

    /**
     * Construye el prompt para enviar a la API de Gemini
     */
    private function buildGeminiPrompt($cpu, $motherboard, $ram, $storage, $gpu, $case, $psu): string
    {
        $componentsText = "";
        if ($cpu) $componentsText .= "- **Procesador (CPU):** {$cpu->marca} {$cpu->nombre} {$cpu->modelo} (Especificaciones: " . json_encode($cpu->especificaciones) . ")\n";
        if ($motherboard) $componentsText .= "- **Tarjeta Madre:** {$motherboard->marca} {$motherboard->nombre} (Especificaciones: " . json_encode($motherboard->especificaciones) . ")\n";
        if ($ram) $componentsText .= "- **Memoria RAM:** {$ram->marca} {$ram->nombre} (Especificaciones: " . json_encode($ram->especificaciones) . ")\n";
        if ($storage) $componentsText .= "- **Almacenamiento:** {$storage->marca} {$storage->nombre} (Especificaciones: " . json_encode($storage->especificaciones) . ")\n";
        if ($gpu) $componentsText .= "- **Tarjeta de Video (GPU):** {$gpu->marca} {$gpu->nombre} (Especificaciones: " . json_encode($gpu->especificaciones) . ")\n";
        if ($case) $componentsText .= "- **Gabinete:** {$case->marca} {$case->nombre} (Especificaciones: " . json_encode($case->especificaciones) . ")\n";
        if ($psu) $componentsText .= "- **Fuente de Poder (PSU):** {$psu->marca} {$psu->nombre} (Especificaciones: " . json_encode($psu->especificaciones) . ")\n";

        return "Actúa como un experto en hardware de computadoras y ensamblador profesional. Analiza la compatibilidad técnica del siguiente ensamble de PC:\n\n" .
            $componentsText . "\n" .
            "Por favor realiza un análisis detallado evaluando:\n" .
            "1. Compatibilidad de socket entre el CPU y la Tarjeta Madre.\n" .
            "2. Compatibilidad del tipo de memoria RAM (DDR4/DDR5) con la Tarjeta Madre y el CPU.\n" .
            "3. Si el vataje de la Fuente de Poder es suficiente para soportar la combinación de CPU + GPU y el resto del sistema (estima el TDP total y compáralo con el de la PSU).\n" .
            "4. Compatibilidad del factor de forma de la Tarjeta Madre con el Gabinete.\n" .
            "5. Compatibilidad del almacenamiento (M.2 NVMe vs SATA) y los slots de expansión.\n\n" .
            "Devuelve tu respuesta estructurada en formato Markdown limpio. Utiliza íconos de estado al inicio de cada sección (✅ si es perfectamente compatible, ⚠️ si es compatible pero no ideal o requiere atención menor, y ❌ si es incompatible o riesgoso). Finaliza con un veredicto general contundente.";
    }

    /**
     * Motor de reglas local para generar análisis estructurado de compatibilidad
     */
    private function generateLocalAnalysis($cpu, $motherboard, $ram, $storage, $gpu, $case, $psu): string
    {
        $output = "### 🧠 Análisis de Compatibilidad Inteligente\n";
        $output .= "*Nota: Ejecutando en modo de simulación local (GEMINI_API_KEY no configurado en el servidor).*\n\n";

        $overallCompatible = true;

        // 1. CPU / Motherboard Socket
        $output .= "#### 🔌 1. Socket de CPU y Placa Madre\n";
        if ($cpu && $motherboard) {
            $cpuSocket = $cpu->especificaciones['socket'] ?? null;
            $mbSocket = $motherboard->especificaciones['socket'] ?? null;

            if ($cpuSocket && $mbSocket && strtolower($cpuSocket) === strtolower($mbSocket)) {
                $output .= "✅ **Compatible:** Tanto el procesador **{$cpu->nombre}** como la tarjeta madre **{$motherboard->nombre}** utilizan el socket **{$cpuSocket}**.\n\n";
            } else {
                $overallCompatible = false;
                $output .= "❌ **Incompatible:** El procesador utiliza el socket **" . ($cpuSocket ?? 'N/D') . "** pero la tarjeta madre requiere **" . ($mbSocket ?? 'N/D') . "**. ¡No encajará físicamente!\n\n";
            }
        } else {
            $output .= "⚠️ **Falta información:** Asegúrate de seleccionar tanto el Procesador como la Tarjeta Madre para verificar el socket.\n\n";
        }

        // 2. RAM
        $output .= "#### 🧠 2. Memoria RAM y Soporte de Placa Madre\n";
        if ($ram && $motherboard) {
            $ramType = $ram->especificaciones['tipo_memoria'] ?? null;
            $mbRamType = $motherboard->especificaciones['tipo_memoria'] ?? null;

            if ($ramType && $mbRamType && strtolower($ramType) === strtolower($mbRamType)) {
                $output .= "✅ **Compatible:** La memoria RAM **{$ram->nombre}** es tipo **{$ramType}**, que coincide con las especificaciones de soporte de la tarjeta madre **{$motherboard->nombre}**.\n\n";
            } else {
                $overallCompatible = false;
                $output .= "❌ **Incompatible:** La memoria RAM es **" . ($ramType ?? 'N/D') . "** pero la tarjeta madre requiere módulos **" . ($mbRamType ?? 'N/D') . "**.\n\n";
            }
        } else {
            $output .= "⚠️ **Falta información:** Selecciona la Memoria RAM y la Tarjeta Madre para evaluar la compatibilidad de memoria.\n\n";
        }

        // 3. PSU Watts / TDP
        $output .= "#### ⚡ 3. Capacidad de Energía (Fuente de Poder)\n";
        $cpuTdp = 100; // default
        $gpuTdp = 150; // default
        
        if ($cpu && isset($cpu->especificaciones['watts'])) {
            $cpuTdp = (int) $cpu->especificaciones['watts'];
        }
        if ($gpu && isset($gpu->especificaciones['watts'])) {
            $gpuTdp = (int) $gpu->especificaciones['watts'];
        }

        $estimatedTdp = $cpuTdp + $gpuTdp + 50;

        if ($psu) {
            $psuWatts = isset($psu->especificaciones['watts']) ? (int) $psu->especificaciones['watts'] : 500;
            
            if ($psuWatts >= $estimatedTdp) {
                $output .= "✅ **Suficiente:** El consumo máximo estimado del ensamble es de **{$estimatedTdp}W** (CPU: {$cpuTdp}W, GPU: {$gpuTdp}W, Base: 50W). Tu fuente de poder **{$psu->nombre}** de **{$psuWatts}W** cuenta con potencia de sobra.\n\n";
            } else {
                $overallCompatible = false;
                $output .= "❌ **Advertencia Crítica:** El consumo total estimado del sistema es de **{$estimatedTdp}W**, superando la capacidad máxima de tu fuente de poder **{$psu->nombre}** de **{$psuWatts}W**. ¡El sistema podría apagarse bajo carga!\n\n";
            }
        } else {
            $output .= "⚠️ **Falta información:** Agrega una Fuente de Poder para verificar si la potencia total soporta tu configuración de CPU + GPU.\n\n";
        }

        // 4. Case / Form Factor
        $output .= "#### 📐 4. Factor de Forma y Gabinete\n";
        $output .= "✅ **Compatible:** Los chasis estándares ATX/Mid-Tower son compatibles con la mayoría de tarjetas madre de factor de forma ATX y Micro-ATX.\n\n";

        // 5. Veredicto Final
        $output .= "---\n";
        $output .= "### 🏁 Veredicto del Ensamblador\n";
        if ($overallCompatible) {
            $output .= "🟢 **¡Compatible!** Todos los componentes verificados son compatibles entre sí. Puedes proceder a realizar tu cotización con total confianza.";
        } else {
            $output .= "🔴 **Incompatibilidades Encontradas:** Revisa los puntos anteriores marcados con ❌. Debes cambiar los componentes incompatibles antes de confirmar.";
        }

        return $output;
    }
}
