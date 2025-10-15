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

    $systemPrompt = "Eres un bot de salud llamado HealthBot.
    El usuario ha iniciado sesión. 
    Su nombre completo es $userName, su correo es $userEmail, tiene $userEdad años y su género es $userGenero.
    Antes de generar un plan de ejercicio o nutrición, debes pedirle su estatura (en metros) y su peso (en kilogramos).
    Luego, calcula el IMC con la fórmula: peso / (estatura^2).
    Puedes dirigirte a él por su nombre. 
    Asegúrate de ser amigable, profesional y motivador.
    Si generas un plan, agrega la pregunta: '¿Deseas guardar este plan?' para que el usuario pueda guardarlo.";
} else {
    $systemPrompt = "Eres un bot de salud llamado HealthBot.
    Puedes conversar con cualquier usuario sobre beneficios de salud, ejercicio y nutrición. 
    Si el usuario pregunta por 'plan nutricional', responde solo con: PLAN. 
    Si pregunta por 'rutina de ejercicio', responde solo con: RUTINA. 
    Si pregunta por 'salud general', responde solo con: SALUD. 
    No generes planes personalizados si el usuario no ha iniciado sesión.";
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
        echo json_encode(["response" => "✅ Tu plan ha sido guardado correctamente con estatura, peso e IMC."]);
    } else {
        echo json_encode(["response" => "⚠️ Hubo un problema al guardar tu plan."]);
    }
    $stmt->execute();
    $stmt->close();
    $con->close();

    unset($_SESSION['ultimo_plan']); // limpiar 

    echo json_encode(["response" => "Tu plan ha sido guardado con estatura, peso e IMC. Puedes verlo más tarde desde tu perfil."]);
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
        $_SESSION['ultimo_plan'] = [
            'contenido' => $botResponse,
            'estatura' => null,
            'peso' => null,
            'imc' => null
        ];
        $_SESSION['esperando_datos'] = true;
        $botResponse .= "\nAntes de guardarlo, por favor indícame tu estatura y peso en este formato: **1.70, 70**";
    }

    echo json_encode(["response" => $botResponse]);
} else {
    echo json_encode(["response" => "No hubo respuesta del modelo."]);
}
