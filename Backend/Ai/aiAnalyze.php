<?php
include("config.php");
//api key and url are in the config.php 


$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
];


$input = json_decode(file_get_contents('php://input'), true);
$text = trim($input['text'] ?? '');

if ($text === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Empty text']);
    exit;
}


$prompt = "You are a silent JSON-only parser.  
The user will type a free-text daily log.  
Extract every quantifiable health habit you see 
for example:
    - the user mentiond the food he ate calculate the calories referring for the avg calories for the food he ate
    - he said that he walked for 25mins calculate the number of steps referring to the speed of an avarege man in walking 
 return ONE flat JSON object with the keys below.  
If a key is missing from the text, set it to null.  
Use units exactly as specified.  
Never add text before or after the JSON.

Keys (all lowercase, snake_case):
- steps: integer
- walking_min: integer
- running_min: integer
- cycling_min: integer
- sleep_time: string in 24-hour \"HH:MM\" format (assume same day unless \"am\"/\"pm\" or \"yesterday\" is written)
- sleep_hours: decimal (hours actually slept if stated, else null)
- caffeine_mg: integer (assume 80 mg per coffee, 40 mg per tea, 50 mg per soda)
- alcohol_units: decimal (1 unit = 1 beer / 1 glass wine / 1 shot)
- water_glasses: integer (250 ml each)
- calories: integer (only if a number followed by \"kcal\" / \"cal\" is written)
- mood: string, one of [\"great\", \"good\", \"neutral\", \"bad\", \"awful\"] (pick closest word mentioned)
- notes: string, any extra text that does not fit above (max 200 chars)
User Text:" . 
$text
;


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

echo $responseData['choices'][0]['message']['content'];
?>