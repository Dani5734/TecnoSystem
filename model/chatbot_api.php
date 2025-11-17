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

**Reglas generales:**
1. Si el usuario no ha mencionado objetivos, pregúntalos.
2. Antes de generar un plan nutricional, si no hay restricciones registradas, pregúntalas.
3. Antes de generar un plan nutricional debes pedir:
   - Estatura en metros
   - Peso en kilogramos
4. Calcula el IMC con: peso / (estatura^2)
5. Usa SIEMPRE el nombre del usuario en tus respuestas.
6. Mantén un tono amable, claro y profesional.
7. No sugieras medicamentos.
8. Si el usuario te pregunta ¿Donde puedo ver mi plan? responde: Dentro de la sección de planes

**Formato obligatorio para los planes y rutinas dentro del chat:**

Cuando generes un **plan nutricional**, preséntalo así:
---
📍 **Plan Nutricional Personalizado**  
- **Objetivo:** (ej. pérdida de peso, aumento muscular, mantenimiento)  
- **Duración sugerida:** (ej. 4 semanas)  
- **Resumen del IMC:** (valor + interpretación breve)  
- **Distribución diaria:**  
  - **Desayuno:** (opciones saludables con cantidades aproximadas)  
  - **Colación:** (ligera y nutritiva)  
  - **Comida:** (balanceada en macronutrientes)  
  - **Cena:** (ligera y fácil de digerir)  
- **Recomendaciones adicionales:** (agua, descanso, hábitos complementarios)

Cuando generes una **rutina de ejercicio**, preséntala así:
---
📍 **Rutina de Ejercicio Personalizada**  
- **Objetivo:** (ej. tonificación, pérdida de grasa, fuerza)  
- **Duración sugerida:** (ej. 4 semanas)  
- **Frecuencia semanal:** (ej. 4 días/semana)  
- **Sesión tipo:**  
  - **Calentamiento:** (5–10 min sugeridos)  
  - **Bloque principal:** (lista de ejercicios con series y repeticiones)  
  - **Enfriamiento/estiramiento:** (breve recomendación)  
- **Consejos de progresión:** (cómo aumentar intensidad con el tiempo)

**Restricciones estrictas:**
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

        $stmt = $con->prepare("INSERT INTO planes (usuario, contenido, estatura, peso, imc, fecha, tipo) VALUES (?, ?, ?, ?, ?, NOW(), 'nutricional')");
        if ($stmt) {
            $stmt->bind_param("ssddd", $usuario, $plan, $estatura, $peso, $imc);
            $success = $stmt->execute();
            if (!$success) {
                $errorMsg = $stmt->error;
                error_log("Error en INSERT nutricional: " . $errorMsg);
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
    if ($isLoggedIn && (strpos($botResponse, '📍 **Plan Nutricional Personalizado**') !== false || 
                       strpos($botResponse, '📍 **Rutina de Ejercicio Personalizada**') !== false)) {
        
        $tipo_plan = (strpos($botResponse, '📍 **Plan Nutricional Personalizado**') !== false) ? 'nutricional' : 'ejercicio';
        
        // Extraer datos si es plan nutricional
        $estatura = null;
        $peso = null;
        $imc = null;
        
        if ($tipo_plan == 'nutricional') {
            // Buscar IMC en la respuesta
            if (preg_match('/IMC.*?(\d+\.?\d*)/', $botResponse, $matches)) {
                $imc = $matches[1];
            }
            // También buscar estatura y peso si están disponibles en la sesión
            if (isset($_SESSION['estatura']) && isset($_SESSION['peso'])) {
                $estatura = $_SESSION['estatura'];
                $peso = $_SESSION['peso'];
            }
        }
        
        $_SESSION['ultimo_plan'] = [
            'contenido' => $botResponse,
            'tipo' => $tipo_plan,
            'estatura' => $estatura,
            'peso' => $peso,
            'imc' => $imc
        ];
    }

    // Guardar en historial
    $_SESSION['chat_history'][] = ["role" => "assistant", "content" => $botResponse];

    echo json_encode(["response" => $botResponse]);
} else {
    echo json_encode(["response" => "No hubo respuesta del modelo."]);
}