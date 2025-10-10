<?php
session_start();

header('Content-Type: application/json; charset=UTF-8');


$config = include __DIR__ . "/config.php";
$apiKey = $config["api_key"];

// Recibir frontend
$input = json_decode(file_get_contents("php://input"), true);
$userMessage = $input["message"] ?? "";

if (!$userMessage) {
    echo json_encode(["response" => "No se recibió ningún mensaje."]);
    exit;
}

$afirmatives = ['Si', 'sí', 'claro', 'afirmativo', 'por su puesto', 'Adelante', 'de acuerdo'];
// Endpoint de OpenAI
$url = "https://api.openai.com/v1/chat/completions";

$data = [
    "model" => "gpt-3.5-turbo",
    "messages" => [
        [
            "role" => "system",
            "content" => "Eres un bot de salud llamado HealthBot. 
            Puedes conversar con cualquier usuario sobre beneficios de salud, ejercicio y nutrición. 
            
            Si el usuario pregunta por 'plan nutricional', responde solo con: PLAN. 
            Si pregunta por 'rutina de ejercicio', responde solo con: RUTINA. 
            Si pregunta por 'salud general', responde solo con: SALUD. 

            - Si el usuario pregunta qué datos se necesitan para generar un plan o rutina, responde con una lista de datos generales (edad, peso, altura, nivel de actividad, historial médico). 
            - Sin embargo, aclara siempre que para generar un plan personalizado necesita iniciar sesión.
            - Nunca generes el plan ni la rutina si el usuario no ha iniciado sesión. 
            - Tu objetivo en modo invitado es solo informar, dar ejemplos y beneficios, pero NO crear planes reales.
            - Depende del plan que te pida, Crea frases que animen o emocinen al usuario a empezar una rutina o plan."

            
        ],
        [
            "role" => "user", 
            "content" => $userMessage
        ]
    ],
    "max_tokens" => 200,
    "temperature" => 0.7
];



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

$result = json_decode($response, true);

if (isset($result["choices"][0]["message"]["content"])) {
    echo json_encode(["response" => trim($result["choices"][0]["message"]["content"])]); 
} else {
    echo json_encode(["response" => "No hubo respuesta del modelo."]);
}
