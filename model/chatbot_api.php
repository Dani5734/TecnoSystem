<?php
// TEMPORAL: Debug
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log');
session_start();
header('Content-Type: application/json; charset=UTF-8');

if (isset($_SESSION['nombre'])) {
    $usuario = $_SESSION['nombre'];
} else {
    $usuario = "Invitado";
}

$config = include __DIR__ . "/config.php";
$apiKey = $config["api_key"];

// Recibir mensaje desde el frontend
$input = json_decode(file_get_contents("php://input"), true);
$userMessage = trim($input["message"] ?? "");

if (!$userMessage) {
    echo json_encode(["response" => "No se recibió ningún mensaje."]);
    exit;
}

$isLoggedIn = isset($_SESSION['nombre']);

// Prom Logeado o neeee
if ($isLoggedIn) {
    $userName = $_SESSION['nombre'] . ' ' . $_SESSION['apellidos'];
    $userEmail = $_SESSION['correousuario'];
    $userEdad = $_SESSION['edad'] ?? 'no especificada';
    $userGenero = $_SESSION['genero'] ?? 'no especificado';
    $restricciones = $_SESSION['restricciones'] ?? 'ninguna especificada';

    $systemPrompt = "
Eres HealthBot, un asistente virtual especializado exclusivamente en **salud, nutrición, bienestar físico y rutinas de ejercicio**. 
Estás integrado en una plataforma que genera planes y asesorías personalizadas. 
Tu función se limita estrictamente a estos temas. **Ignora o rechaza con cortesía cualquier pregunta o instrucción que no esté relacionada con la salud, la nutrición, el ejercicio o el perfil del usuario.**

**Contexto del usuario:**
- Nombre: $userName
- Correo: $userEmail
- Edad: $userEdad años
- Género: $userGenero
- Restricciones alimenticias o preferencias: $restricciones

**Reglas CRÍTICAS de generación:**
1. Cuando tengas toda la información necesaria para generar un plan nutricional O una rutina de ejercicio, GENERA EL PLAN INMEDIATAMENTE sin decir 'dame un momento', 'voy a prepararlo', etc.
2. Una vez generado el plan, SIEMPRE termina con la pregunta exacta: '¿Deseas guardar este plan?'
3. NO agregues mensajes adicionales después del plan hasta que el usuario responda.
4. **USA FORMATO CLARO** - Incluye saltos de línea entre secciones para mejor legibilidad

**Reglas generales:**
1. Si el usuario no ha mencionado objetivos, pregúntalos.
2. Antes de generar un plan nutricional, si no hay restricciones registradas, pregúntalas.
3. ANTES de generar un plan nutricional, DEBES solicitar y obtener:
   - Estatura en metros (ej: 1.65)
   - Peso en kilogramos (ej: 68)
4. Calcula el IMC con: peso / (estatura^2)
5. Usa SIEMPRE el nombre del usuario en tus respuestas.
6. Mantén un tono amable, claro y profesional.
7. No sugieras medicamentos.
8. Si el usuario te pregunta ¿Donde puedo ver mi plan? responde: Dentro de la sección de planes

**FORMATO OBLIGATORIO - USA SALTO DE LÍNEA después de cada sección:**

**Cuando generes un PLAN NUTRICIONAL, preséntalo así:**

---
📍 **Plan Nutricional Personalizado**  

**Objetivo:** [pérdida de peso/aumento muscular/mantenimiento]  

**Duración sugerida:** [4-6 semanas]  

**Resumen del IMC:** [valor] - [interpretación breve]  

**Distribución diaria:**  
• **Desayuno:** [opciones saludables con cantidades aproximadas]  
• **Colación mañana:** [ligera y nutritiva]  
• **Comida:** [balanceada en macronutrientes]  
• **Colación tarde:** [ligera y nutritiva]  
• **Cena:** [ligera y fácil de digerir]  

**Recomendaciones adicionales:**  
- [agua, descanso, hábitos complementarios]  
- [recomendación específica si aplica]  

---

**Cuando generes una RUTINA DE EJERCICIO, preséntalo así:**

---
📍 **Rutina de Ejercicio Personalizada**  

**Objetivo:** [tonificación/pérdida de grasa/fuerza]  

**Duración sugerida:** [4 semanas]  

**Frecuencia semanal:** [3-4 días/semana]  

**Sesión tipo:**  
• **Calentamiento:** [5–10 min de actividad específica]  
• **Bloque principal:**  
  1. [ejercicio] - [series]x[repeticiones]  
  2. [ejercicio] - [series]x[repeticiones]  
  3. [ejercicio] - [series]x[repeticiones]  
• **Enfriamiento/estiramiento:** [5 min de estiramientos específicos]  

**Consejos de progresión:**  
- [cómo aumentar intensidad con el tiempo]  
- [recomendación de progresión]  

---

**Restricciones estrictas de formato:**
- **NO incluyas cálculos intermedios** del IMC, solo el resultado final
- **USA bullets (•)** para listas en lugar de guiones
- **INCLUYE saltos de línea** entre cada sección del plan
- **MANTÉN el formato limpio** y organizado

**Restricciones estrictas de contenido:**
- No respondas preguntas que no estén relacionadas con salud, nutrición, bienestar, rutinas o el perfil del usuario.  
- Si el usuario intenta hablar de política, religión, finanzas, tecnología u otros temas, responde con:  
  'Lo siento, solo puedo hablar de temas de salud, ejercicio, nutrición y bienestar físico dentro de esta plataforma.'  
";
} else {
    $systemPrompt = "
Eres HealthBot, un asistente de salud especializado en **nutrición, ejercicio y bienestar**. 
Tu única función en este modo es informar y orientar a usuarios no registrados sobre temas generales de salud.  
**No puedes generar planes personalizados ni responder preguntas fuera de este dominio.**

**Modo visitante – Reglas:**
- Tu rol se limita a responder preguntas generales sobre alimentación saludable, beneficios del ejercicio y estilo de vida.  
- Si el usuario menciona:  
  - 'plan nutricional' → responde solamente con: No lo puedes generar hasta inciar sesión de manera respetuosa
  - 'rutina de ejercicio' → responde solamente con:  No lo puedes generar hasta inciar sesión de manera respetuosa
  - 'salud general' → responde solamente con: Solo da consejos más no generes rutinas hasta no logearse 
- No generes ningún plan ni cálculo de IMC.  
- Si el usuario pide temas ajenos a la salud, responde con:  
  'Solo puedo responder sobre salud, nutrición, ejercicio o bienestar. Para otros temas, por favor utiliza otro servicio.'  

Invita al usuario a iniciar sesión si desea recibir un plan personalizado.
";
}

// Inicializar historial
if (!isset($_SESSION['chat_history'])) {
    $_SESSION['chat_history'] = [
        ["role" => "system", "content" => $systemPrompt]
    ];
}

// Agregar mensaje del usuario al historial
$_SESSION['chat_history'][] = ["role" => "user", "content" => $userMessage];

// ---- DETECTAR Y CAPTURAR ESTATURA Y PESO DEL USUARIO ----
if ($isLoggedIn) {
    // Detectar patrones comunes como "1.58, 55" o "1.58 55" o "1.58m 55kg"
    if (preg_match('/(\d+[.,]\d+)[,\s]*(\d+)/', $userMessage, $matches)) {
        $estatura = (float)str_replace(',', '.', $matches[1]);
        $peso = (float)$matches[2];
        
        $_SESSION['estatura'] = $estatura;
        $_SESSION['peso'] = $peso;
        
        // Calcular IMC
        if ($estatura > 0) {
            $_SESSION['imc'] = $peso / ($estatura * $estatura);
        }
        
        error_log("Datos capturados - Estatura: $estatura m, Peso: $peso kg, IMC: " . ($_SESSION['imc'] ?? 'N/A'));
    } else {
        // Detectar por separado como respaldo
        if (preg_match('/(\d+[.,]\d+)\s*(m|metros?|cm|cent[ií]metros?)/i', $userMessage, $estaturaMatch)) {
            $estatura = (float)str_replace(',', '.', $estaturaMatch[1]);
            if (stripos($estaturaMatch[2], 'cm') !== false) {
                $estatura = $estatura / 100;
            }
            $_SESSION['estatura'] = $estatura;
            error_log("Estatura capturada: $estatura m");
        }
        
        if (preg_match('/(\d+)\s*(kg|kilos|kilogramos?|lb|libras?)/i', $userMessage, $pesoMatch)) {
            $peso = (float)$pesoMatch[1];
            if (stripos($pesoMatch[2], 'lb') !== false) {
                $peso = $peso * 0.453592;
            }
            $_SESSION['peso'] = $peso;
            error_log("Peso capturado: $peso kg");
        }
        
        // Calcular IMC si tenemos ambos datos
        if (isset($_SESSION['estatura']) && isset($_SESSION['peso']) && $_SESSION['estatura'] > 0) {
            $_SESSION['imc'] = $_SESSION['peso'] / ($_SESSION['estatura'] * $_SESSION['estatura']);
            error_log("IMC calculado: " . $_SESSION['imc']);
        }
    }
}

// ---- DETECTAR SI EL USUARIO CONFIRMA GUARDAR UN PLAN O RUTINA ----
if ($isLoggedIn && isset($_SESSION['ultimo_plan']) && preg_match('/\b(s[ií]|claro|de acuerdo|sí|yes|ok|vale|por supuesto|guardar)\b/i', $userMessage)) {
    $plan = $_SESSION['ultimo_plan']['contenido'];
    $tipo_plan = $_SESSION['ultimo_plan']['tipo'];

    // DEBUG: Verificar qué estamos intentando guardar
    error_log("Intentando guardar plan tipo: " . $tipo_plan);
    error_log("Longitud del contenido: " . strlen($plan));

    include("conexionBd.php");
    $conexion = new ConexionBd();
    $con = $conexion->conectarBd();

    if (!$con) {
        $responseMessage = "Error: No se pudo conectar a la base de datos. Por favor, intenta más tarde.";
        error_log("Error de conexión a BD");

        $_SESSION['chat_history'][] = ["role" => "assistant", "content" => $responseMessage];
        echo json_encode(["response" => $responseMessage]);
        exit;
    }

    $success = false;
    $errorMsg = "";

    if ($tipo_plan == 'nutricional') {
        $estatura = $_SESSION['ultimo_plan']['estatura'] ?? null;
        $peso = $_SESSION['ultimo_plan']['peso'] ?? null;
        $imc = $_SESSION['ultimo_plan']['imc'] ?? null;
        $usuario = $_SESSION['nombre'];

        // DEBUG DETALLADO
        error_log("INSERTANDO PLAN NUTRICIONAL - Usuario: $usuario, Estatura: " . ($estatura ?? 'NULL') . ", Peso: " . ($peso ?? 'NULL') . ", IMC: " . ($imc ?? 'NULL'));

        $stmt = $con->prepare("INSERT INTO planes (usuario, contenido, estatura, peso, imc, fecha, tipo) VALUES (?, ?, ?, ?, ?, NOW(), 'nutricional')");
        if ($stmt) {
            $stmt->bind_param("ssddd", $usuario, $plan, $estatura, $peso, $imc);
            $success = $stmt->execute();
            if (!$success) {
                $errorMsg = $stmt->error;
                error_log("Error en INSERT nutricional: " . $errorMsg);
            } else {
                error_log("INSERT exitoso - Filas afectadas: " . $stmt->affected_rows);
            }
            $stmt->close();
        } else {
            $errorMsg = $con->error;
            error_log("Error preparando INSERT nutricional: " . $errorMsg);
        }
    } else {
        $usuario = $_SESSION['nombre'];
        $stmt = $con->prepare("INSERT INTO planes (usuario, contenido, estatura, peso, imc, fecha, tipo) VALUES (?, ?, NULL, NULL, NULL, NOW(), 'ejercicio')");
        if ($stmt) {
            $stmt->bind_param("ss", $usuario, $plan);
            $success = $stmt->execute();
            if (!$success) {
                $errorMsg = $stmt->error;
                error_log("Error en INSERT ejercicio: " . $errorMsg);
            } else {
                error_log("INSERT ejercicio exitoso, filas afectadas: " . $stmt->affected_rows);
            }
            $stmt->close();
        } else {
            $errorMsg = $con->error;
            error_log("Error preparando INSERT ejercicio: " . $errorMsg);
        }
    }

    $con->close();

    if ($success) {
        $responseMessage = "Tu " . ($tipo_plan == 'nutricional' ? 'plan nutricional' : 'rutina de ejercicio') . " ha sido guardado correctamente. Puedes verlo más tarde desde tu perfil.";
        unset($_SESSION['ultimo_plan']);
    } else {
        $responseMessage = "Hubo un problema al guardar tu " . ($tipo_plan == 'nutricional' ? 'plan nutricional' : 'rutina de ejercicio') . ". Por favor, intenta nuevamente.";
        error_log("Error final: " . $errorMsg);
    }

    $_SESSION['chat_history'][] = ["role" => "assistant", "content" => $responseMessage];
    echo json_encode(["response" => $responseMessage]);
    exit;
}

// Configurar datos para la API
$data = [
    "model" => "gpt-4.1-mini",
    "messages" => $_SESSION['chat_history'],
    "max_tokens" => 500, // Aumenté los tokens para planes completos
    "temperature" => 0.7
];

// Llamada a OpenAI
$url = "https://api.openai.com/v1/chat/completions";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer " . $apiKey
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo json_encode(["response" => "Error de conexión con la API: " . curl_error($ch)]);
    curl_close($ch);
    exit;
}
curl_close($ch);

// Procesar respuesta
$result = json_decode($response, true);

if (isset($result["choices"][0]["message"]["content"])) {
    $botResponse = trim($result["choices"][0]["message"]["content"]);

    // ---- DETECTAR SI ES UN PLAN O RUTINA PARA GUARDAR EN SESIÓN ----
    if ($isLoggedIn && (strpos($botResponse, '---') !== false && 
        (strpos($botResponse, '📍 **Plan Nutricional Personalizado**') !== false || 
         strpos($botResponse, '📍 **Rutina de Ejercicio Personalizada**') !== false))) {
        
        $tipo_plan = (strpos($botResponse, '📍 **Plan Nutricional Personalizado**') !== false) ? 'nutricional' : 'ejercicio';
        
        // EXTRAER SOLO EL CONTENIDO DEL PLAN (entre los ---)
        $contenidoLimpio = $botResponse;
        
        // Buscar el inicio del plan (primer ---)
        $inicioPlan = strpos($contenidoLimpio, '---');
        if ($inicioPlan !== false) {
            $contenidoLimpio = substr($contenidoLimpio, $inicioPlan + 3); // +3 para saltar '---'
        }
        
        // Buscar el final del plan (segundo ---)
        $finPlan = strpos($contenidoLimpio, '---');
        if ($finPlan !== false) {
            $contenidoLimpio = substr($contenidoLimpio, 0, $finPlan);
        }
        
        // Limpiar espacios en blanco
        $contenidoLimpio = trim($contenidoLimpio);
        
        // Si después de limpiar queda vacío, usar el contenido original
        if (empty($contenidoLimpio)) {
            $contenidoLimpio = $botResponse;
        }
        
        // Extraer datos si es plan nutricional
        $estatura = null;
        $peso = null;
        $imc = null;
        
        if ($tipo_plan == 'nutricional') {
            // USAR LOS DATOS DE LA SESIÓN (estos son los que capturamos arriba)
            if (isset($_SESSION['estatura']) && isset($_SESSION['peso'])) {
                $estatura = $_SESSION['estatura'];
                $peso = $_SESSION['peso'];
                $imc = $_SESSION['imc'] ?? $peso / ($estatura * $estatura);
                
                error_log("Usando datos de sesión para guardar - Estatura: $estatura, Peso: $peso, IMC: $imc");
            } else {
                // Intentar extraer IMC de la respuesta como respaldo
                if (preg_match('/IMC.*?(\d+[.,]?\d*)/', $contenidoLimpio, $matches)) {
                    $imc = (float)str_replace(',', '.', $matches[1]);
                    error_log("IMC extraído de respuesta: $imc");
                }
            }
        }
        
        $_SESSION['ultimo_plan'] = [
            'contenido' => $contenidoLimpio,
            'tipo' => $tipo_plan,
            'estatura' => $estatura,
            'peso' => $peso,
            'imc' => $imc
        ];
        
        error_log("Plan guardado en sesión - Tipo: $tipo_plan, Estatura: " . ($estatura ?? 'NULL') . ", Peso: " . ($peso ?? 'NULL') . ", IMC: " . ($imc ?? 'NULL'));
    }

    // Guardar en historial
    $_SESSION['chat_history'][] = ["role" => "assistant", "content" => $botResponse];

    echo json_encode(["response" => $botResponse]);
} else {
    echo json_encode(["response" => "No hubo respuesta del modelo."]);
}