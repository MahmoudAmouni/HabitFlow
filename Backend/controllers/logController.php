<?php

require_once(__DIR__ . "/../models/Log.php");
require_once(__DIR__ . "/../connection/connection.php");
require_once(__DIR__ . "/../services/ResponseService.php");
require_once(__DIR__ . "/../services/LogService.php");

class LogController
{
    private LogService $logService;

    public function __construct()
    {
        global $connection;
        $this->logService = new LogService($connection);
    }

    public function getLogs()
    {
        try {
            $input = json_decode(file_get_contents("php://input"), true);
            $id = $input["id"] ?? null;
            if ($id) {
                $result = $this->logService->getLogById($id);
                echo ResponseService::response($result['status'], $result['data']);
                exit;
            }
            $user_id = $input["user_id"] ?? null;
            $habit_id = $input["habit_id"] ?? null;
            if ($user_id) {
                $result = $this->logService->getLogsByOtherId($user_id, "user_id");
                echo ResponseService::response($result['status'], $result['data']);
                exit;
            }
            if ($habit_id) {
                $result = $this->logService->getLogsByOtherId($habit_id, "habit_id");
                echo ResponseService::response($result['status'], $result['data']);
                exit;
            }

            $result = $this->logService->getAllLogs();
            echo ResponseService::response($result['status'], $result['data']);
        } catch (Exception $e) {
            echo ResponseService::response(500, ['error' => 'An error occurred while retrieving logs: ' . $e->getMessage()]);
        }
    }

    public function deleteLog()
    {
        try {
            $input = json_decode(file_get_contents("php://input"), true);
            $id = $input["id"];
            if (!$id) {
                echo ResponseService::response(400, ['error' => 'ID is required']);
                return;
            }

            $result = $this->logService->deleteLog($id);
            echo ResponseService::response($result['status'], $result['data']);
        } catch (Exception $e) {
            echo ResponseService::response(500, ['error' => 'An error occurred while deleting the log: ' . $e->getMessage()]);
        }
    }

    public function createLog()
    {
        try {
            $input = json_decode(file_get_contents("php://input"), true);

            if (!$input) {
                echo ResponseService::response(400, ['error' => 'No data provided']);
                return;
            }

            $result = $this->logService->createLog($input);
            echo ResponseService::response($result['status'], $result['data']);
        } catch (Exception $e) {
            echo ResponseService::response(500, ['error' => 'An error occurred while creating the log: ' . $e->getMessage()]);
        }
    }

    public function createLogFromAiResponse()
    {
        try {
            $input = json_decode(file_get_contents("php://input"), true);

            if (!$input) {
                echo ResponseService::response(400, ['error' => 'No data provided']);
                return;
            }

            $result = $this->logService->createLogFromAiResponse($input);
            echo ResponseService::response($result['status'], $result['data']);
        } catch (Exception $e) {
            echo ResponseService::response(500, ['error' => 'An error occurred while creating the log: ' . $e->getMessage()]);
        }
    }

    public function updateLog()
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

            $result = $this->logService->updateLog($id, $input);
            echo ResponseService::response($result['status'], $result['data']);
        } catch (Exception $e) {
            echo ResponseService::response(500, ['error' => 'An error occurred while updating the log: ' . $e->getMessage()]);
        }
    }
}
?>
