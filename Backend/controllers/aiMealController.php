<?php

require_once(__DIR__ . "/../models/AiMeal.php");
require_once(__DIR__ . "/../connection/connection.php");
require_once(__DIR__ . "/../services/ResponseService.php");
require_once(__DIR__ . "/../services/AiMealService.php");

class AiMealController
{
    private AiMealService $aiMealService;

    public function __construct()
    {
        global $connection;
        $this->aiMealService = new AiMealService($connection);
    }

    public function getAiMeals()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        if($input['user_id']){
            $user_id = $input['user_id'];
            $result = $this->aiMealService->getAiMealsByUserId($user_id,'user_id');
        }else{

            $result = $this->aiMealService->getAiMeal();
        } 
        echo ResponseService::response($result['status'], $result['data']);
    }



    public function deleteAiMeal()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input["id"];

        if (!$id) {
            echo ResponseService::response(400, ['error' => 'ID is required']);
            return;
        }

        $result = $this->aiMealService->deleteAiMeal($id);
        echo ResponseService::response($result['status'], $result['data']);
    }

    public function createAiMeal()
    {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input) {
            echo ResponseService::response(400, ['error' => 'No data provided']);
            return;
        }

        $result = $this->aiMealService->createAiMeal($input);
        echo ResponseService::response($result['status'], $result['data']);
    }

    public function updateAiMeal()
    {
        $input = json_decode(file_get_contents("php://input"), true);
         $id= $input["id"] ;
        if (!$id) {
            echo ResponseService::response(400, ['error' => 'ID is required']);
            return;
        }

        if (!$input) {
            echo ResponseService::response(400, ['error' => 'provide data to update']);
            return;
        }
        $result = $this->aiMealService->updateAiMeal($id, $input);
        echo ResponseService::response($result['status'], $result['data']);
    }
}
?>