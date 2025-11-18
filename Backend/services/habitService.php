<?php
require_once(__DIR__ . "/../models/Habit.php");
require_once(__DIR__ . "/../models/User.php");

class HabitService
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function getHabitById(int $id): array
    {
        try {
            $habit = Habit::find($this->connection, $id, 'id');
            return $habit
                ? ['status' => 200, 'data' => $habit->toArray()]
                : ['status' => 404, 'data' => ['error' => 'habit not found']];
        } catch (Throwable $e) {
            error_log("HabitService::getHabitById error: " . $e->getMessage());
            return ['status' => 500, 'data' => ['error' => 'DB error while fetching habit']];
        }
    }

    public function getHabitsByUserId(int $userId): array
    {
        try {
            $user = User::find($this->connection, $userId, 'id');
            if (!$user) {
                return ['status' => 404, 'data' => ['error' => 'No user with this id']];
            }

            $habits = Habit::findAllByOtherId($this->connection, $userId, 'user_id');
            $data = array_map(fn($habit) => $habit->toArray(), $habits);

            return ['status' => 200, 'data' => $data];
        } catch (Throwable $e) {
            error_log("HabitService::getHabitsByUserId error: " . $e->getMessage());
            return ['status' => 500, 'data' => ['error' => 'DB error while fetching user habits']];
        }
    }

    public function getAllHabits(): array
    {
        try {
            $habits = Habit::findAll($this->connection);
            $data = array_map(fn($habit) => $habit->toArray(), $habits);

            return ['status' => 200, 'data' => $data];
        } catch (Throwable $e) {
            error_log("HabitService::getAllHabits error: " . $e->getMessage());
            return ['status' => 500, 'data' => ['error' => 'DB error while fetching habits']];
        }
    }

    public function createHabit(array $data): array
    {
        try {
            $requiredFields = ['name', 'unit', 'target', 'user_id'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || empty(trim($data[$field]))) {
                    return [
                        'status' => 400,
                        'data' => ['error' => "Missing or empty required field: {$field}"]
                    ];
                }
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
        } catch (Throwable $e) {
            error_log("HabitService::createHabit error: " . $e->getMessage());
            return ['status' => 500, 'data' => ['error' => 'DB error occurred while creating habit']];
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
            if ($result == "Duplicate")
                return ['status' => 500, 'data' => ['message' => 'Habit name already exists']];

            if ($result) {
                return ['status' => 200, 'data' => ['message' => 'habit updated successfully']];
            }

            return ['status' => 500, 'data' => ['error' => 'Failed to update habit']];
        } catch (Throwable $e) {
            error_log("HabitService::updateHabit error: " . $e->getMessage());
            return ['status' => 500, 'data' => ['error' => 'DB error occurred while updating habit']];
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
        } catch (Throwable $e) {
            error_log("HabitService::deleteHabit error: " . $e->getMessage());
            return ['status' => 500, 'data' => ['error' => 'DB error occurred while deleting habit']];
        }
    }
}
?>