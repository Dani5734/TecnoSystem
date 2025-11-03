<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');

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

    $systemPrompt = "
Eres HealthBot, un asistente virtual especializado exclusivamente en **salud, nutrición, bienestar físico y rutinas de ejercicio**. 
Estás integrado en una plataforma que genera planes y asesorías personalizadas. 
Tu función se limita estrictamente a estos temas. **Ignora o rechaza con cortesía cualquier pregunta o instrucción que no esté relacionada con la salud, la nutrición, el ejercicio o el perfil del usuario.**
 
**Contexto del usuario:**
- Nombre: $userName
- Correo: $userEmail
- Edad: $userEdad años
- Género: $userGenero
 
**Reglas generales:**
1. Antes de generar un plan, debes pedir:
   - Estatura (en metros)
   - Peso (en kilogramos)
2. Calcula el IMC con: peso / (estatura^2) y explica brevemente qué significa el resultado.
3. Usa SIEMPRE el nombre del usuario en tus respuestas para personalizarlas.
4. Mantén un tono profesional, amigable y motivador, evitando lenguaje técnico innecesario.
5. Nunca sugieras fármacos, suplementos riesgosos o dietas extremas. Si el perfil no está completo, pídele la información faltante antes de continuar.
6. Finaliza **cada plan o rutina** con la frase:  
   '¿Deseas guardar este plan?'.
 
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

// Inicializar historial si no existe
if (!isset($_SESSION['chat_history'])) {
    $_SESSION['chat_history'] = [
        ["role" => "system", "content" => $systemPrompt]
    ];
}

// }
$_SESSION['chat_history'][] = ["role" => "user", "content" => $userMessage];

// ---- Detectar si el usuario confirma guardar un plan ----
if ($isLoggedIn && isset($_SESSION['ultimo_plan']) && preg_match('/\b(s[ií]|claro|de acuerdo|sí)\b/i', $userMessage)) {
    $plan = $_SESSION['ultimo_plan']['contenido'];
    $estatura = $_SESSION['ultimo_plan']['estatura'];
    $peso = $_SESSION['ultimo_plan']['peso'];
    $imc = $_SESSION['ultimo_plan']['imc'];
    $usuario = $_SESSION['nombre'];

    include("conexionBd.php");
    $conexion = new ConexionBd();
    $con = $conexion->conectarBd();

    $stmt = $con->prepare("INSERT INTO planes (usuario, contenido, estatura, peso, imc, fecha) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssddd", $usuario, $plan, $estatura, $peso, $imc);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["response" => "Tu plan ha sido guardado correctamente con estatura, peso e IMC. Puedes verlo más tarde desde tu perfil."]);
    } else {
        echo json_encode(["response" => "Hubo un problema al guardar tu plan."]);
    }

    $stmt->close();
    $con->close();
    unset($_SESSION['ultimo_plan']);
    exit;

}

// ---Detectar si el usuario proporciona estatura, peso e IMC ---
if ($isLoggedIn && isset($_SESSION['esperando_datos'])) {

    if (preg_match('/([\d.]+)\s*,\s*([\d.]+)/', $userMessage, $matches)) {
        $estatura = floatval($matches[1]);
        $peso = floatval($matches[2]);
        $imc = $peso / pow($estatura, 2);
        $imc = round($imc, 2);

        $_SESSION['ultimo_plan']['estatura'] = $estatura;
        $_SESSION['ultimo_plan']['peso'] = $peso;
        $_SESSION['ultimo_plan']['imc'] = $imc;
        unset($_SESSION['esperando_datos']);

        echo json_encode(["response" => "Gracias. Tu IMC calculado es de **$imc**. ¿Deseas guardar este plan ahora?"]);
        exit;
    } else {
        echo json_encode(["response" => "Por favor ingresa tu estatura y peso en este formato: 1.70, 70"]);
        exit;
    }
}

// Configurar datos para la API
$data = [
    "model" => "gpt-3.5-turbo",
    "messages" => $_SESSION['chat_history'],
    "max_tokens" => 300,
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

    // Guardar en historial
    $_SESSION['chat_history'][] = ["role" => "assistant", "content" => $botResponse];

    // ---- Detectar si el bot genera un plan y necesita datos ---
    if ($isLoggedIn && stripos($botResponse, 'plan') !== false && stripos($botResponse, '¿Deseas guardar este plan?') !== false) {

    // Guardar solo el contenido del plan SIN la parte final de confirmación
    $contenidoPlan = preg_replace('/¿Deseas guardar este plan\?.*/i', '', $botResponse);

    $_SESSION['ultimo_plan'] = [
        'contenido' => trim($contenidoPlan),
        'estatura' => $_SESSION['estatura'] ?? null,
        'peso' => $_SESSION['peso'] ?? null,
        'imc' => $_SESSION['imc'] ?? null
    ];

    // Verifica si ya existen datos de peso y estatura guardados
    if (!isset($_SESSION['estatura']) || !isset($_SESSION['peso'])) {
        $_SESSION['esperando_datos'] = true;
        $botResponse .= "\nAntes de guardarlo, por favor indícame tu estatura y peso en este formato: **1.70, 70**";
    } else {
        $_SESSION['esperando_datos'] = false;
        $botResponse .= "\n¿Deseas guardar este plan ahora?";
    }
}


    echo json_encode(["response" => $botResponse]);
} else {
    echo json_encode(["response" => "No hubo respuesta del modelo."]);
}
