<?php
require_once 'db.php';
require_once 'functions.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$message = $input['message'] ?? '';

if (empty($message)) {
    echo json_encode(['error' => 'Empty message']);
    exit;
}

// Get AI Settings from DB
$stmt = $pdo->prepare("SELECT key_value FROM settings WHERE key_name = 'ai_provider'");
$stmt->execute();
$provider = $stmt->fetchColumn() ?: 'gemini';

$stmt = $pdo->prepare("SELECT key_value FROM settings WHERE key_name = 'ai_api_key'");
$stmt->execute();
$apiKey = $stmt->fetchColumn();

if (!$apiKey) {
    // Fallback for legacy key name
    $stmt = $pdo->prepare("SELECT key_value FROM settings WHERE key_name = 'gemini_api_key'");
    $stmt->execute();
    $apiKey = $stmt->fetchColumn();
}

if (!$apiKey) {
    echo json_encode(['response' => 'AI API Key not configured.']);
    exit;
}

$botText = '';

if ($provider === 'nvidia') {
    $url = "https://integrate.api.nvidia.com/v1/chat/completions";
    $data = [
        "model" => "meta/llama-3.1-8b-instruct",
        "messages" => [
            ["role" => "system", "content" => "Anda adalah asisten AI untuk website portfolio Stormbreaker. Berikan jawaban yang ramah, singkat, dan profesional. Fokuslah pada layanan desain dan web development."],
            ["role" => "user", "content" => $message]
        ],
        "temperature" => 0.5,
        "max_tokens" => 500
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        $botText = 'Maaf, terjadi kesalahan koneksi ke NVIDIA AI.';
    } else {
        $resData = json_decode($response, true);
        $botText = $resData['choices'][0]['message']['content'] ?? 'Maaf, NVIDIA AI tidak memberikan respon.';
    }

} else {
    // Gemini Logic
    $url = "https://generativelanguage.googleapis.com/v1/models/gemini-pro:generateContent?key=" . $apiKey;
    $data = [
        "contents" => [
            [
                "parts" => [
                    ["text" => "Anda adalah asisten AI untuk website portfolio Stormbreaker. Berikan jawaban yang ramah, singkat, dan profesional. Fokuslah pada layanan desain dan web development. User bertanya: " . $message]
                ]
            ]
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        $botText = 'Maaf, terjadi kesalahan koneksi ke Gemini AI.';
    } else {
        $resData = json_decode($response, true);
        if (isset($resData['error'])) {
            $botText = 'API Error: ' . ($resData['error']['message'] ?? 'Unknown error');
        } else {
            $botText = $resData['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, Gemini AI tidak memberikan respon.';
        }
    }
}

echo json_encode(['response' => $botText]);
