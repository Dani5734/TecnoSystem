<?php
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

// Endpoint de OpenAI
$url = "https://api.openai.com/v1/chat/completions";

$data = [
    "model" => "gpt-3.5-turbo", 
    "messages" => [
        ["role" => "system", "content" => "Eres un asistente amigable de salud llamado HealthBot."],
        ["role" => "user", "content" => $userMessage]
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
