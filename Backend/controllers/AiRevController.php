<?php

require_once(__DIR__ . "/../models/AiRev.php");
require_once(__DIR__ . "/../connection/connection.php");
require_once(__DIR__ . "/../services/ResponseService.php");
require_once(__DIR__ . "/../services/AiRevServices.php");

class AiRevController
{
    private AiRevServices $aiRevService;

    public function __construct()
    {
        global $connection;
        $this->aiRevService = new AiRevServices($connection);
    }

    public function getAiReviews()
    {
        $id = $id = isset($_GET["id"]) ? $_GET["id"] : null;
        $result = $this->aiRevService->getAiReviews($id);
        echo ResponseService::response($result['status'], $result['data']);
    }

    public function deleteAiReview()
    {
        $id = $id = isset($_GET["id"]) ? $_GET["id"] : null;

        if (!$id) {
            echo ResponseService::response(400, ['error' => 'ID is required']);
            return;
        }

        $result = $this->aiRevService->deleteAiReview($id);
        echo ResponseService::response($result['status'], $result['data']);
    }

    public function createAiReview()
    {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input) {
            echo ResponseService::response(400, ['error' => 'No data provided']);
            return;
        }

        $validErrors = $this->aiRevService->validateAiReviewData($input);
        if (!empty($validErrors)) {
            echo ResponseService::response(400, ['errors' => $validErrors]);
            return;
        }

        $result = $this->aiRevService->createAiReview($input);
        echo ResponseService::response($result['status'], $result['data']);
    }

    public function updateAiReview()
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

        $validErrors = $this->aiRevService->validateAiReviewData($input, true);
        if (!empty($validErrors)) {
            echo ResponseService::response(400, ['errors' => $validErrors]);
            return;
        }

        $result = $this->aiRevService->updateAiReview($id, $input);
        echo ResponseService::response($result['status'], $result['data']);
    }
}
?>