<?php
require_once(__DIR__ . "/../models/Habit.php");
require_once(__DIR__ . "/../models/User.php");
require_once(__DIR__ . "/serviceHelper.php");

class HabitService
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function getHabitById(int $id)
    {
        try {
            $habit = Habit::find($this->connection, $id, 'id');
            return $habit
                ? ['status' => 200, 'data' => $habit->toArray()]
                : ['status' => 404, 'data' => ['error' => 'habit not found']];
        } catch (Exception $e) {
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while getting habit by id: ' . $e->getMessage()]];
        }
    }

    public function getHabitsByUserId(int $userId)
    {
        try {
            $validationResult = validateIdExists($this->connection, $userId, "user_id");
            if (isset($validationResult['status']) && $validationResult['status'] !== 200) {
                return $validationResult;
            }

            $habits = Habit::findAllByOtherId($this->connection, $userId, 'user_id');
            $data = array_map(fn($habit) => $habit->toArray(), $habits);

            return ['status' => 200, 'data' => $data];
        } catch (Exception $e) {
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while get habit by user id: ' . $e->getMessage()]];
        }
    }

    public function getAllHabits()
    {
        try {
            $habits = Habit::findAll($this->connection);
            $data = array_map(fn($habit) => $habit->toArray(), $habits);

            return ['status' => 200, 'data' => $data];
        } catch (Exception $e) {
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while getting all habits: ' . $e->getMessage()]];
        }
    }

    public function createHabit(array $data)
    {
        try {
            $validationResult = validateRequiredFields($data, $this->getRequiredFields());
            if ($validationResult !== true) {
                return $validationResult;
            }

            $habitId = Habit::create($this->connection, $data);
            if ($habitId == "Duplicate") {
                return [
                    'status' => 500,
                    'data' => [
                        'message' => 'duplicate habit name',
                    ]
                ];
            }
            if ($habitId) {
                return [
                    'status' => 201,
                    'data' => [
                        'message' => 'habit created successfully',
                        'id' => $habitId
                    ]
                ];
            }

            return ['status' => 500, 'data' => ['error' => 'Failed to create habit']];
        } catch (Exception $e) {
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while creating habit: ' . $e->getMessage()]];
        }
    }

    public function updateHabit(int $id, array $data)
    {
        try {
            $habit = Habit::find($this->connection, $id, "id");
            if (!$habit) {
                return ['status' => 404, 'data' => ['error' => 'habit not found']];
            }

            if (empty($data)) {
                return ['status' => 400, 'data' => ['error' => 'No data provided for update']];
            }

            $result = $habit->update($this->connection, $data, "id");
            if ($result == "Duplicate") {
                return ['status' => 500, 'data' => ['message' => 'Habit name already exists']];
            }

            if ($result) {
                return ['status' => 200, 'data' => ['message' => 'habit updated successfully']];
            }

            return ['status' => 500, 'data' => ['error' => 'Failed to update habit']];
        } catch (Exception $e) {
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while updating habit: ' . $e->getMessage()]];
        }
    }

    public function deleteHabit(int $id)
    {
        try {
            $habit = Habit::find($this->connection, $id, "id");
            if (!$habit) {
                return ['status' => 404, 'data' => ['error' => 'habit not found']];
            }

            $result = Habit::deleteById($id, $this->connection, "id");
            if ($result) {
                return ['status' => 200, 'data' => ['message' => 'habit deleted successfully']];
            }

            return ['status' => 500, 'data' => ['error' => 'Failed to delete habit']];
        } catch (Exception $e) {
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while deleting habit: ' . $e->getMessage()]];
        }
    }

   

    private function getRequiredFields()
    {
        return ['name', 'unit', 'target', 'user_id'];
    }
}
?>