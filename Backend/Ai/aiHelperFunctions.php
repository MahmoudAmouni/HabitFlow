<?php

function processMealResponse($aiResponse, $aiMealService, $userId = null)
{
    $parsedMeal = json_decode($aiResponse, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(500);
        echo json_encode(['error' => 'Invalid JSON from AI response: ' . json_last_error_msg()]);
        exit;
    }

    if (!is_array($parsedMeal)) {
        http_response_code(500);
        echo json_encode(['error' => 'AI response is not a valid array']);
        exit;
    }

    if (!isset($parsedMeal['title']) || !isset($parsedMeal['summary']) || !isset($parsedMeal['url'])) {
        http_response_code(400);
        echo json_encode([
            'status' => 400,
            'message' => 'Missing required fields in AI response',
        ]);
        exit;
    }
    $parsedMeal['user_id'] = $userId;


    $result = $aiMealService->createAiMeal($parsedMeal);

    if ($result['status'] === 201) {
        echo json_encode([
            'status' => 200,
            'message' => 'AI meal processed successfully'
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            'status' => 400,
            'message' => 'Failed to create aiMeal',
            'error' => $result['data']['error'] ?? 'Unknown error'
        ]);
    }
}

function processSummaryResponse($aiResponse, $aiResponseService, $type )
{
    $parsedSummary = json_decode($aiResponse, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(500);
        echo json_encode(['error' => 'Invalid JSON from AI response: ' . json_last_error_msg()]);
        exit;
    }

    if (!is_array($parsedSummary)) {
        http_response_code(500);
        echo json_encode(['error' => 'AI response is not a valid array']);
        exit;
    }

    if (!isset($parsedSummary['title']) || !isset($parsedSummary['summary']) || !isset($parsedSummary['suggestion'])) {
        http_response_code(400);
        echo json_encode([
            'status' => 400,
            'message' => 'Missing required fields in AI response',
        ]);
        exit;
    }

    $parsedSummary['type'] = $type;

    $result = $aiResponseService->createAiResponse($parsedSummary);

    if ($result['status'] === 201) {
        echo json_encode([
            'status' => 200,
            'message' => 'AI summary processed successfully'
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            'status' => 400,
            'message' => 'Failed to create aiResponse',
            'error' => $result['data']['error'] ?? 'Unknown error'
        ]);
    }
}

function processLogResponse($aiResponse, $logService, $userId)
{
    $parsedLogs = json_decode($aiResponse, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(500);
        echo json_encode(['error' => 'Invalid JSON from AI response: ' . json_last_error_msg()]);
        exit;
    }

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
}

?>