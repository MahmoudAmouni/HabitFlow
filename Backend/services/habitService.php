<?php
require_once( __DIR__ . "/../models/Habit.php");

class HabitService
{

    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }
    public function getHabits($id)
    {
        if ($id) {
            $habit = Habit::find($this->connection, $id);
            if ($habit) {
                return ['status' => 200, 'data' => $habit->toArray()];
            }
            return ['status' => 404, 'data' => ['error' => 'habit not found']];
        }

        $habits = Habit::findAll($this->connection);
        $data = [];
        foreach ($habits as $habit) {
            $data[] = $habit->toArray();
        }
        return ['status' => 200, 'data' => $data];
    }

    public function createHabit(array $data): array
    {
        $requiredFields = ['name', 'unit', 'target','user_id'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty(trim($data[$field]))) {
                return [
                    'status' => 400,
                    'data' => ['error' => "Missing or empty required field: {$field}"]
                ];
            }
        }

        $habitId = Habit::create($this->connection, $data);
        if ($habitId == 1062) {
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
    }

    public function updateHabit(int $id, array $data)
    {
        $habit = Habit::find($this->connection, $id);
        if (!$habit) {
            return ['status' => 404, 'data' => ['error' => 'habit not found']];
        }

        if (empty($data)) {
            return ['status' => 400, 'data' => ['error' => 'No data provided for update']];
        }

        $result = Habit::update($this->connection, $id, $data);
        if ($result) {
            return ['status' => 200, 'data' => ['message' => 'habit updated successfully']];
        }

        return ['status' => 500, 'data' => ['error' => 'Failed to update habit']];
    }

    public function deleteHabit(int $id)
    {
        $habit = Habit::find($this->connection, $id);
        if (!$habit) {
            return ['status' => 404, 'data' => ['error' => 'habit not found']];
        }

        $result = Habit::deleteById($id, $this->connection);
        if ($result) {
            return ['status' => 200, 'data' => ['message' => 'habit deleted successfully']];
        }

        return ['status' => 500, 'data' => ['error' => 'Failed to delete habit']];
    }

}
?>