# 🔧 Criterios de Compatibilidad - PC Master Builder

## 1. SOCKET CPU ↔ MOTHERBOARD

### Intel (LGA - Land Grid Array)
- **LGA1700** → Socket Z790, H770, B760, Z690, H670, B660 (Raptor Lake / Alder Lake)
- **LGA1200** → Socket Z490, H470, B460 (Comet Lake / Rocket Lake)
- **LGA1150** → Socket H97, Z97 (Haswell / Broadwell)
- **HEDT - LGA2011-v3** → Socket X99 (Core i7 Extreme)

### AMD (AM Socket)
- **AM5** → Socket AM5 (Ryzen 7000/8000 series - DDR5, TDP até 170W)
  - **Requisito obligatorio:** Motherboard con chipset X870-E, X870, X770, X670-E, X670, B850
  - **Compatible con:** RAM DDR5 5200+ MHz
  - ❌ **NO compatible con:** DDR4, Motherboards AM4

- **AM4** → Socket AM4 (Ryzen 5000/3000 series - DDR4, TDP até 105W)
  - **Requisito obligatorio:** Motherboard con chipset X570, B550, X470, B450, X370, B350
  - **Compatible con:** RAM DDR4 3200-3600+ MHz
  - ❌ **NO compatible con:** DDR5, AM5 boards

- **AM3+** → Socket AM3+ (FX Series - DEPRECATED)

---

## 2. MEMORIA RAM (DDR - Double Data Rate)

### DDR5 (Generación 2022+)
- **Sockets compatibles:** AM5, LGA1700 (solo Z790/B760), LGA1851
- **Voltaje:** 1.25V (JEDEC) / 1.4V (XMP)
- **Velocidades estándar:** 4800-6400+ MHz
- **Latencia:** CAS 24-40
- **Capacidad:** 8GB, 16GB, 32GB, 48GB, 96GB
- ❌ **Incompatible con:** AM4, LGA1200, cualquier socket DDR4

### DDR4 (Generación 2014-2022)
- **Sockets compatibles:** AM4, LGA1200, LGA1150, HEDT
- **Voltaje:** 1.2V (JEDEC) / 1.35V (XMP)
- **Velocidades estándar:** 2400-3600+ MHz
- **Latencia:** CAS 14-18
- **Capacidad:** 4GB, 8GB, 16GB, 32GB, 64GB
- ❌ **Incompatible con:** AM5 nuevos, LGA1700 (excepto algunas boards con hybrid support)

---

## 3. REGLAS DE VALIDACIÓN STRICT

### 🔴 Incompatibilidades Críticas (BLOQUEAR)

1. **CPU AM5 + Motherboard AM4** → ❌ BLOQUEAR
   - El procesador Ryzen 7000+ no encaja físicamente en AM4
   
2. **CPU AM5 + RAM DDR4** → ❌ BLOQUEAR
   - Los Ryzen 7000+ solo funcionan con DDR5
   
3. **AM4 Motherboard + RAM DDR5** → ❌ BLOQUEAR
   - Las placas AM4 carecen de soporte para DDR5
   
4. **CPU LGA1700 + RAM DDR4** → ⚠️ ADVERTENCIA
   - Algunas placas B660 pueden soportar DDR4, pero es raro (verificar modelo específico)
   
5. **CPU LGA1200 + Motherboard LGA1700** → ❌ BLOQUEAR
   - Sockets incompatibles (distintas generaciones)

### 🟡 Advertencias (PERMITIR pero alertar)

1. **CPU AM5 de gama baja + Motherboard X870-E** → ⚠️
   - Placa muy potente para CPU de entrada
   
2. **RAM de velocidad baja en placa de gama alta** → ⚠️
   - Ejemplo: DDR5 4800 en X870-E (espera 5600+)

---

## 4. CHIPSETS CPU POR GENERACIÓN

### Intel - Chipset Compatibility Matrix

| Socket | Chipset | Generación | RAM Soportada |
|--------|---------|-----------|---------------|
| LGA1700 | Z790 | 13ª gen | DDR5 |
| LGA1700 | B760 | 12-13ª gen | DDR5 |
| LGA1700 | H770 | 13ª gen | DDR5 |
| LGA1200 | Z490 | 10ª gen | DDR4 |
| LGA1200 | B460 | 10ª gen | DDR4 |

### AMD - Chipset Compatibility Matrix

| Socket | Chipset | Generación | RAM Soportada | TDP Máx |
|--------|---------|-----------|---------------|---------|
| AM5 | X870-E | 9000 series | DDR5 | 170W |
| AM5 | X870 | 9000 series | DDR5 | 170W |
| AM5 | X770 | 7000-9000 | DDR5 | 170W |
| AM5 | X670-E | 7000-9000 | DDR5 | 105W |
| AM5 | B850 | 7000-9000 | DDR5 | 170W |
| AM4 | X570 | 5000 series | DDR4 | 105W |
| AM4 | B550 | 3000-5000 | DDR4 | 105W |

---

## 5. CPU ↔ REFRIGERACIÓN

### Soportes Intel
- **LGA1700:** RaptorLake, AlderLake
- **LGA1200:** CometLake, RocketLake
- **Compatibilidad:** Verificar TDP máximo (95W, 125W, 150W)

### Soportes AMD
- **AM5:** Ryzen 7000/8000 series (PGA)
- **AM4:** Ryzen 3000/5000 series (PGA)
- **Compatibilidad:** Verificar TDP máximo (65W, 105W, 170W)

---

## 6. FUENTE DE PODER (PSU ↔ CPU + GPU)

### Cálculo de TDP Total
```
TDP Total = TDP CPU + TDP GPU + 50W (sistema base)
```

### Recomendaciones PSU
- **RTX 4090 + i9-13900K:** 1000W mínimo
- **RTX 4070 Ti + i7-13700K:** 800W mínimo
- **RTX 4070 + i5-13600K:** 700W mínimo
- **RTX 4060 Ti + i5-13400:** 600W mínimo

---

## 7. FORMA DEL FACTOR (Form Factor)

### Motherboard
- **E-ATX:** 305mm x 330mm (muy grande)
- **ATX:** 305mm x 244mm (estándar)
- **Micro-ATX:** 244mm x 244mm
- **Mini-ITX:** 170mm x 170mm (compacta)

### Gabinete (Case)
Debe soportar tamaño de placa:
- **Full Tower:** ATX, Micro-ATX, Mini-ITX
- **Mid Tower:** ATX, Micro-ATX, Mini-ITX
- **Mini Tower:** Micro-ATX, Mini-ITX

❌ **No compatible:** E-ATX en casos Mid/Mini

### GPU (Tarjeta Gráfica)
- **Largo típico:** 250mm - 330mm
- **Altura:** 2-3 slots
- **Verificar clearance:** Profundidad interna del gabinete - espacio PSU

---

## 8. ALMACENAMIENTO

### M.2 NVMe
- **Interfaz:** PCIe 3.0, 4.0, 5.0
- **Compatibilidad:** Verificar que la placa tenga slots M.2
- **Form Factor:** 2280 (22mm x 80mm) - estándar
- **Compatible con todos los sockets modernos** (salvo restricciones por generación)

### SATA SSD/HDD
- **Interfaz:** SATA 3.0 (6 Gbps)
- **Compatible con todas las placas modernas**
- **Verificar conectores SATA en la placa**

---

## 9. CONECTORES POWER (ATX 24-pin + CPU 8/4-pin)

### Estándar ATX 24-pin
- Voltaje: +12V, +5V, +3.3V, GND
- Compatible con todas las placas ATX/Micro-ATX modernas

### CPU Power (8-pin / 4-pin)
- **LGA1700:** 8-pin EPS o 4+4 pin dual
- **AM5:** 8-pin EPS
- **AM4:** 4+4 pin dual (algunos modelos 8-pin)
- La PSU debe proporcionar el conector requerido

---

## 10. REGLAS DE FILTRADO EN BASE DE DATOS

```sql
-- No permitir AM5 CPU + AM4 Motherboard
SELECT * FROM components 
WHERE category_id = 'CPU_AM5' 
AND especificaciones->'socket' = 'AM5' 
-- FILTRAR motherboards donde especificaciones->'socket' = 'AM4'

-- No permitir DDR4 RAM + AM5 CPU
SELECT * FROM components 
WHERE category_id = 'RAM' 
AND especificaciones->'tipo' = 'DDR4' 
-- FILTRAR si hay CPU AM5 en la cotización

-- No permitir incompatibilidad de voltaje
SELECT * FROM components 
WHERE category_id = 'MOTHERBOARD' 
AND especificaciones->'chipset' = 'X870-E'
-- FILTRAR si hay DDR4 RAM
```

---

## 11. ESPECIFICACIONES MÍNIMAS RECOMENDADAS

### Gaming 1080p High (60fps)
- CPU: Ryzen 5 5600X / i5-12400
- RAM: 16GB DDR4/DDR5
- GPU: RTX 3060 Ti / RX 6700 XT
- PSU: 650W

### Gaming 1440p Ultra (60fps)
- CPU: Ryzen 7 5800X / i7-12700K
- RAM: 32GB DDR5
- GPU: RTX 4070 / RX 7800 XT
- PSU: 850W

### Gaming 4K Ultra (60fps)
- CPU: Ryzen 9 9950X / i9-13900K
- RAM: 32GB DDR5 6000MHz
- GPU: RTX 4090 / RX 7900 XTX
- PSU: 1000W+

---

## 12. CASOS DE USO PARA TESTING

### ✅ CASO VÁLIDO
```json
{
  "cpu": "Ryzen 7 9700X (AM5, DDR5)",
  "motherboard": "X870-E",
  "ram": "32GB DDR5 6000MHz",
  "gpu": "RTX 4070 Ti",
  "psu": "850W",
  "case": "Mid-Tower ATX"
}
```
Resultado: ✅ COMPATIBLE

### ❌ CASO INVÁLIDO 1
```json
{
  "cpu": "Ryzen 7 9700X (AM5, DDR5)",
  "motherboard": "X570 (AM4)",
  "ram": "32GB DDR5 6000MHz",
}
```
Resultado: ❌ ERROR - CPU AM5 no encaja en AM4

### ❌ CASO INVÁLIDO 2
```json
{
  "cpu": "Ryzen 7 5800X (AM4)",
  "motherboard": "B550",
  "ram": "32GB DDR5",
}
```
Resultado: ❌ ERROR - AM4 no soporta DDR5

---

## 📋 PENDIENTE DE IMPLEMENTACIÓN

- [ ] Tabla `compatibility_rules` en DB
- [ ] Servicio `CompatibilityValidator`
- [ ] Endpoint POST `/api/validate-compatibility`
- [ ] Frontend: mostrar advertencias/errores en tiempo real
- [ ] Gemini AI: verificar especificaciones contra BD de reglas

