<?php
require_once(__DIR__ . "/../models/AiMeal.php");
require_once(__DIR__ . "/../models/User.php");
require_once(__DIR__ . "/serviceHelper.php");

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
            $data = array_map(fn($aiMeal) => $aiMeal->toArray(), $aiMeals);
            return ['status' => 200, 'data' => $data];
        } catch (Exception $e) {
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while getting aiMeals: ' . $e->getMessage()]];
        }
    }

    public function getAiMealsByUserId($id, $key): array
    {
        try {
            $data = validateIdExists($this->connection, $id, $key);
            if (isset($data['status']) && $data['status'] !== 200) {
                return $data;
            }

            $aiMeals = AiMeal::findAllByOtherId($this->connection, $id, $key);
            $data = array_map(fn($aiMeal) => $aiMeal->toArray(), $aiMeals);

            return ['status' => 200, 'data' => $data];
        } catch (Exception $e) {
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while getting aiMeals by user id: ' . $e->getMessage()]];
        }
    }

    public function createAiMeal(array $data): array
    {
        try {
            $validationResult = validateRequiredFields($data, $this->getRequiredFields());
            if ($validationResult !== true) {
                return $validationResult;
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
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while creating aiMeal: ' . $e->getMessage()]];
        }
    }

    public function updateAiMeal(int $id, array $data)
    {
        try {
            $aiMeal = AiMeal::find($this->connection, $id, "id");
            if (!$aiMeal) {
                return ['status' => 404, 'data' => ['error' => 'aiMeal not found']];
            }

            if (empty($data)) {
                return ['status' => 400, 'data' => ['error' => 'No data provided for update']];
            }

            $result = $aiMeal->update($this->connection, $data, "id");

            if ($result) {
                return ['status' => 200, 'data' => ['message' => 'aiMeal updated successfully']];
            }

            return ['status' => 500, 'data' => ['error' => 'Failed to update aiMeal']];
        } catch (Exception $e) {
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while updating aiMeal: ' . $e->getMessage()]];
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
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while deleting aiMeal: ' . $e->getMessage()]];
        }
    }



    private function getRequiredFields()
    {
        return ['title', 'url', 'summary'];
    }
}
?>