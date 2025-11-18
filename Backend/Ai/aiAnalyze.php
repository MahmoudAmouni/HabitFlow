<?php
include("config.php");
require_once(__DIR__ . "/../connection/connection.php");
require_once(__DIR__ . "/../services/habitService.php");
require_once(__DIR__ . "/../services/userService.php");
require_once(__DIR__ . "/../services/logService.php");
require_once(__DIR__ . "/../services/AiResponseService.php");
require_once(__DIR__ . "/../services/AiMealService.php");
require_once(__DIR__ . "/prompts.php");
require_once(__DIR__ . "/aiHelperFunctions.php");

$habitService = new HabitService($connection);
$userService = new UserService($connection);
$logService = new LogService($connection);
$aiResponseService = new AiResponseService($connection);
$aiMealService = new AiMealService($connection);

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

$logsResult = $logService->getLogsByUserId($userId,'user_id');
if ($logsResult['status'] !== 200) {
    echo json_encode(["status" => 404, "message" => "no logs found"]);
    exit;
}
$userLogs= $logsResult["data"];
$userHabits = $habitsResult['data'];
$text = trim($input['text'] ?? '');
$type = trim($input['type'] ?? '');


if ($text === '' && $type==='') {
    http_response_code(400);
    echo json_encode(['error' => 'Empty text']);
    exit;
}
$prompt;

switch ($type) {
    case 'meal':
       $prompt = CreateMealAiPrompt($userHabits, $userLogs);
        break;
    case 'daily':
    case 'weekly':
    case 'monthly':
        $prompt =CreateSummaryAiPrompt($userHabits, $userLogs, $type);
        break;
    case 'log':
    default:
       $prompt= CreateLogsAiPrompt($userHabits,$text);
        break;
}

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
    'temperature' => $type == 'meal' ? 1.3 : 0.0
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
$aiResponse = trim($aiResponse);
if (substr($aiResponse, 0, 7) === '```json') {
    $aiResponse = substr($aiResponse, 7);
}
if (substr($aiResponse, -3) === '```') {
    $aiResponse = substr($aiResponse, 0, -3);
}
$aiResponse = trim($aiResponse);

switch ($type) {
    case 'meal':
        processMealResponse($aiResponse, $aiMealService, $userId);
        break;
    case 'daily':
    case 'weekly':
    case 'monthly':
        processSummaryResponse($aiResponse, $aiResponseService, $userId);
        break;
    case 'log':
    default:
        processLogResponse($aiResponse, $logService, $userId);
        break;
}
?>