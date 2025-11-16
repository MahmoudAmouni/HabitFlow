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
        $id = $id = isset($_GET["id"]) ? $_GET["id"] : null;
        $result = $this->logService->getLogs($id);
        echo ResponseService::response($result['status'], $result['data']);
    }


    public function deleteLog()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $id =$input["id"];
        if (!$id) {
            echo ResponseService::response(400, ['error' => 'ID is required']);
            return;
        }

        $result = $this->logService->deleteLog($id);
        echo ResponseService::response($result['status'], $result['data']);
    }

    public function createLog()
    {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input) {
            echo ResponseService::response(400, ['error' => 'No data provided']);
            return;
        }

        $result = $this->logService->createLog($input);
        echo ResponseService::response($result['status'], $result['data']);
    }

    public function updateLog()
    {
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
    }
}
?>