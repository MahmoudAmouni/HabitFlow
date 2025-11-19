<?php

require_once(__DIR__ . "/../models/User.php");
require_once(__DIR__ . "/../connection/connection.php");
require_once(__DIR__ . "/../services/ResponseService.php");
require_once(__DIR__ . "/../services/UserService.php");

class UserController
{
    private UserService $userService;

    public function __construct()
    {
        global $connection;
        $this->userService = new UserService($connection);
    }

    public function getUsers()
    {
        try {
            $id = isset($_GET["id"]) ? $_GET["id"] : null;
            $result = $this->userService->getUsers($id);
            echo ResponseService::response($result['status'], $result['data']);
        } catch (Exception $e) {
            echo ResponseService::response(500, ['error' => 'An error occurred while getting users: ' . $e->getMessage()]);
        }
    }

    public function getUserByEmail()
    {
        try {
            $input = json_decode(file_get_contents("php://input"), true);
            $result = $this->userService->getUserByEmail($input["email"], $input["password"]);
            echo ResponseService::response($result['status'], $result['data']);
        } catch (Exception $e) {
            echo ResponseService::response(500, ['error' => 'An error occurred while retrieving user by email: ' . $e->getMessage()]);
        }
    }

    public function deleteUser()
    {
        try {
            $input = json_decode(file_get_contents("php://input"), true);
            $id = $input["id"];

            if (!$id) {
                echo ResponseService::response(400, ['error' => 'ID is required']);
                return;
            }

            $result = $this->userService->deleteUser($id);
            echo ResponseService::response($result['status'], $result['data']);
        } catch (Exception $e) {
            echo ResponseService::response(500, ['error' => 'An error occurred while deleting the user: ' . $e->getMessage()]);
        }
    }

    public function createUser()
    {
        try {
            $input = json_decode(file_get_contents("php://input"), true);

            if (!$input) {
                echo ResponseService::response(400, ['error' => 'No data provided']);
                return;
            }

            $result = $this->userService->createUser($input);
            echo ResponseService::response($result['status'], $result['data']);
        } catch (Exception $e) {
            echo ResponseService::response(500, ['error' => 'An error occurred while creating the user: ' . $e->getMessage()]);
        }
    }

    public function updateUser()
    {
        try {
            $input = json_decode(file_get_contents("php://input"), true);
            $id = isset($_GET["id"]) ? $_GET["id"] : null;

            if (!$id) {
                echo ResponseService::response(400, ['error' => 'ID is required']);
                return;
            }

            if (!$input) {
                echo ResponseService::response(400, ['error' => 'provide data to update']);
                return;
            }

            $result = $this->userService->updateUser($id, $input);
            echo ResponseService::response($result['status'], $result['data']);
        } catch (Exception $e) {
            echo ResponseService::response(500, ['error' => 'An error occurred while updating the user: ' . $e->getMessage()]);
        }
    }
}
?>