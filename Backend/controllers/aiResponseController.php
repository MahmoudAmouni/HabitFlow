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
        $input = json_decode(file_get_contents("php://input"), true);
        if ($input['user_id']) {
            $user_id = $input['user_id'];
            echo ''. $user_id .'';
            $result = $this->aiResponseService->getAiResponsesByUserId($user_id, 'user_id');
        }else{
            $result = $this->aiResponseService->getAiResponse();
        }
        echo ResponseService::response($result['status'], $result['data']);
    }


    public function deleteAiResponse()
    {
        $id = $id = isset($_GET["id"]) ? $_GET["id"] : null;

        if (!$id) {
            echo ResponseService::response(400, ['error' => 'ID is required']);
            return;
        }

        $result = $this->aiResponseService->deleteAiResponse($id);
        echo ResponseService::response($result['status'], $result['data']);
    }

    public function createAiResponse()
    {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input) {
            echo ResponseService::response(400, ['error' => 'No data provided']);
            return;
        }

        $result = $this->aiResponseService->createAiResponse($input);
        echo ResponseService::response($result['status'], $result['data']);
    }

    public function updateAiResponse()
    {
        $id = $_GET['id'] ?? 0;
        $input = json_decode(file_get_contents("php://input"), true);

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
    }
}
?>