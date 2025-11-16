<?php

require_once(__DIR__ . "/../models/Habit.php");
require_once(__DIR__ . "/../connection/connection.php");
require_once(__DIR__ . "/../services/ResponseService.php");
require_once(__DIR__ . "/../services/HabitService.php");

class HabitController
{
    private HabitService $habitService;

    public function __construct()
    {
        global $connection;
        $this->habitService = new HabitService($connection);
    }

    public function getHabits()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input["id"] ?? null;
        if ($id) {
            $result = $this->habitService->getHabitById($id);
            echo ResponseService::response($result['status'], $result['data']);
            exit;
        }
        $user_id = $input["user_id"] ?? null;
        if ($user_id) {
            $result = $this->habitService->getHabitsByUserId($user_id);
            echo ResponseService::response($result['status'], $result['data']);
            exit;
        }
        $result = $this->habitService->getAllHabits();
        echo ResponseService::response($result['status'], $result['data']);
    }


    public function deleteHabit()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $id =$input["id"];
        if (!$id) {
            echo ResponseService::response(400, ['error' => 'ID is required']);
            return;
        }

        $result = $this->habitService->deleteHabit($id);
        echo ResponseService::response($result['status'], $result['data']);
    }

    public function createHabit()
    {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input) {
            echo ResponseService::response(400, ['error' => 'No data provided']);
            return;
        }

        $result = $this->habitService->createHabit($input);
        echo ResponseService::response($result['status'], $result['data']);
    }

    public function updateHabit()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input["id"]??null;
        

        if (!$id) {
            echo ResponseService::response(400, ['error' => 'ID is required']);
            return;
        }
        if (!$input) {
            echo ResponseService::response(400, ['error' => 'provide data to update']);
            return;
        }

        $result = $this->habitService->updateHabit($id, $input);
        echo ResponseService::response($result['status'], $result['data']);
    }
}
?>