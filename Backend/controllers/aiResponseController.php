<?php

require_once(__DIR__ . "/../models/AiResponse.php");
require_once(__DIR__ . "/../connection/connection.php");
require_once(__DIR__ . "/../services/ResponseService.php");
require_once(__DIR__ . "/../services/AiResponseService.php");

class AiResponseController
{
    private AiResponseService $aiResponseService;

    public function __construct()
    {
        global $connection;
        $this->aiResponseService = new AiResponseService($connection);
    }

    public function getAiResponses()
    {
        try {
            $input = json_decode(file_get_contents("php://input"), true);
            if ($input['user_id']) {
                $user_id = $input['user_id'];
                $result = $this->aiResponseService->getAiResponsesByUserId($user_id, 'user_id');
            } else {
                $result = $this->aiResponseService->getAiResponse();
            }
            echo ResponseService::response($result['status'], $result['data']);
        } catch (Exception $e) {
            echo ResponseService::response(500, ['error' => 'An error occurred while getting AI responses: ' . $e->getMessage()]);
        }
    }

    public function deleteAiResponse()
    {
        try {
            $input = json_decode(file_get_contents("php://input"), true);
            $id = $input["id"];
            if (!$id) {
                echo ResponseService::response(400, ['error' => 'ID is required']);
                return;
            }

            $result = $this->aiResponseService->deleteAiResponse($id);
            echo ResponseService::response($result['status'], $result['data']);
        } catch (Exception $e) {
            echo ResponseService::response(500, ['error' => 'An error occurred while deleting the AI response: ' . $e->getMessage()]);
        }
    }

    public function createAiResponse()
    {
        try {
            $input = json_decode(file_get_contents("php://input"), true);

            if (!$input) {
                echo ResponseService::response(400, ['error' => 'No data provided']);
                return;
            }

            $result = $this->aiResponseService->createAiResponse($input);
            echo ResponseService::response($result['status'], $result['data']);
        } catch (Exception $e) {
            echo ResponseService::response(500, ['error' => 'An error occurred while creating the AI response: ' . $e->getMessage()]);
        }
    }

    public function updateAiResponse()
    {
        try {
            $input = json_decode(file_get_contents("php://input"), true);
            $id = $input["id"];
            if (!$id) {
                echo ResponseService::response(400, ['error' => 'ID is required']);
                return;
            }

            if (!$input) {
                echo ResponseService::response(400, ['error' => 'provide data to update']);
                return;
            }
            $result = $this->aiResponseService->updateAiResponse($id, $input);
            echo ResponseService::response($result['status'], $result['data']);
        } catch (Exception $e) {
            echo ResponseService::response(500, ['error' => 'An error occurred while updating the AI response: ' . $e->getMessage()]);
        }
    }
}
?>