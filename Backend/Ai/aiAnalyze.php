<?php
include("config.php");
require_once(__DIR__ . "/../connection/connection.php");
require_once(__DIR__ . "/../services/habitService.php");
require_once(__DIR__ . "/../services/userService.php");
require_once(__DIR__ . "/../services/logService.php");
require_once(__DIR__ . "/prompts.php");

$habitService = new HabitService($connection);
$userService = new UserService($connection);
$logService = new LogService($connection);

$input = json_decode(file_get_contents('php://input'), true);
$userId = $input['user_id'] ?? $_GET['user_id'] ?? null;

if (!$userId) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing user_id']);
    exit;
}

$user = $userService->getUsers($userId);
if ($user['status'] !== 200) {
    echo json_encode(["status" => 404, "message" => "no user found"]);
    exit;
}


$habitsResult = $habitService->getHabitsByUserId($userId);
if ($habitsResult['status'] !== 200) {
    echo json_encode(["status" => 404, "message" => "no habits found"]);
    exit;
}

$userHabits = $habitsResult['data'];
$text = trim($input['text'] ?? '');

if ($text === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Empty text']);
    exit;
}

$prompt = CreateLogsAiPrompt($userHabits, $text);


$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
];


$data = [
    'model' => 'gpt-4-turbo',
    'messages' => [
        ['role' => 'user', 'content' => $prompt]
    ],
    'max_tokens' => 1000,
    'temperature' => 0.0
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
$response = curl_exec($ch);

if (curl_errno($ch)) {
    http_response_code(500);
    echo json_encode(['error' => 'cURL error: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}
curl_close($ch);

$responseData = json_decode($response, true);

if (isset($responseData['error'])) {
    http_response_code(500);
    echo json_encode(['error' => 'OpenAI error: ' . ($responseData['error']['message'] ?? 'Unknown')]);
    exit;
}

if (!isset($responseData['choices'][0]['message']['content'])) {
    http_response_code(500);
    echo json_encode(['error' => 'Unexpected response from OpenAI']);
    exit;
}




$aiResponse = $responseData['choices'][0]['message']['content'];
$parsedLogs = json_decode($aiResponse, true);

if (!is_array($parsedLogs)) {
    http_response_code(500);
    echo json_encode(['error' => 'AI response is not a valid array']);
    exit;
}

$createdLogs = [];
$errors = [];

foreach ($parsedLogs as $logEntry) {
    if (!isset($logEntry['habit_id']) || !isset($logEntry['value'])) {
        continue;
    }

    $logData = [
        'habit_id' => $logEntry['habit_id'],
        'value' => $logEntry['value'],
        'user_id' => $userId
    ];

    $result = $logService->createLog($logData);

    if ($result['status'] === 201 || $result['status'] === 200) {
        $createdLogs[] = $logEntry;
    } else {
        $errors[] = $result['data']['error'] ?? 'Unknown error';
    }
}

if (!empty($createdLogs)) {
    echo json_encode([
        'status' => 200,
        'message' => 'AI logs processed successfully',
        'created_logs' => $createdLogs,
        'errors' => $errors
    ]);
} else {
    http_response_code(400);
    echo json_encode([
        'status' => 400,
        'message' => 'No valid logs created',
        'errors' => !empty($errors) ? $errors : ['No matching habits found']
    ]);
}

?>