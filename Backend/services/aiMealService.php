<?php
require_once(__DIR__ . "/../models/AiMeal.php");

class AiMealService
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function getAiMeal()
    {
        try {
            $aiMeals = AiMeal::findAll($this->connection);
            $data = [];
            foreach ($aiMeals as $aiMeal) {
                $data[] = $aiMeal->toArray();
            }
            return ['status' => 200, 'data' => $data];
        } catch (Exception $e) {
            error_log("aiMealService::getaiMeals error: " . $e->getMessage());
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while fetching aiMeals']];
        }
    }


    public function createAiMeal(array $data): array
    {
        try {
            $requiredFields = ['title','url','summary'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || empty(trim($data[$field]))) {
                    return [
                        'status' => 400,
                        'data' => ['error' => "Missing or empty required field: {$field}"]
                    ];
                }
            }

            $aiMealId = AiMeal::create($this->connection, $data);
            if ($aiMealId) {
                return [
                    'status' => 201,
                    'data' => [
                        'message' => 'aiMeal created successfully',
                        'id' => $aiMealId
                    ]
                ];
            }

            return ['status' => 500, 'data' => ['error' => 'Failed to create aiMeal']];
        } catch (Exception $e) {
            error_log("aiMealService::createaiMeal error: " . $e->getMessage());
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while creating aiMeal']];
        }
    }

    public function updateAiMeal(int $id, array $data)
    {
        try {
            $aiMeal = AiMeal::find($this->connection, $id, "id");
            if (!$aiMeal)
                return ['status' => 404, 'data' => ['error' => 'aiMeal not found']];

            if (empty($data))
                return ['status' => 400, 'data' => ['error' => 'No data provided for update']];

            $result = AiMeal::update($this->connection, $id, $data, "id");

            if ($result)
                return ['status' => 200, 'data' => ['message' => 'aiMeal updated successfully']];

            return ['status' => 500, 'data' => ['error' => 'Failed to update aiMeal']];
        } catch (Exception $e) {
            error_log("aiMealService::updateaiMeal error: " . $e->getMessage());
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while updating aiMeal']];
        }
    }

    public function deleteAiMeal(int $id)
    {
        try {
            $aiMeal = AiMeal::find($this->connection, $id, "id");
            if (!$aiMeal) {
                return ['status' => 404, 'data' => ['error' => 'aiMeal not found']];
            }

            $result = AiMeal::deleteById($id, $this->connection, "id");
            if ($result) {
                return ['status' => 200, 'data' => ['message' => 'aiMeal deleted successfully']];
            }

            return ['status' => 500, 'data' => ['error' => 'Failed to delete aiMeal']];
        } catch (Exception $e) {
            error_log("aiMealService::deleteaiMeal error: " . $e->getMessage());
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while deleting aiMeal']];
        }
    }
}
?>