<?php
require_once(__DIR__ . "/../models/Log.php");
require_once(__DIR__ . "/../models/User.php");
require_once(__DIR__ . "/../models/Habit.php");

class LogService
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function getLogById(int $id): array
    {
        try {
            $log = Log::find($this->connection, $id, 'id');
            return $log
                ? ['status' => 200, 'data' => $log->toArray()]
                : ['status' => 404, 'data' => ['error' => 'log not found']];
        } catch (Throwable $e) {
            error_log("LogService::getLogById error: " . $e->getMessage());
            return ['status' => 500, 'data' => ['error' => 'DB error while fetching log']];
        }
    }

    public function getLogsByUserId($id, $key): array
    {
        try {
            $data = "";
            if ($key == "user_id") {
                $data = User::find($this->connection, $id, 'id');
            } else {
                $data = Habit::find($this->connection, $id, $key);
            }

            if (!$data) {
                return ['status' => 404, 'data' => ['error' => 'Wrong id']];
            }

            $logs = Log::findAllByOtherId($this->connection, $id, $key);
            $data = array_map(fn($log) => $log->toArray(), $logs);

            return ['status' => 200, 'data' => $data];
        } catch (Throwable $e) {
            error_log("LogService::getLogsByOtherId error: " . $e->getMessage());
            return ['status' => 500, 'data' => ['error' => 'DB error while fetching user logs']];
        }
    }

    public function getAllLogs(): array
    {
        try {
            $logs = Log::findAll($this->connection);
            $data = array_map(fn($log) => $log->toArray(), $logs);

            return ['status' => 200, 'data' => $data];
        } catch (Throwable $e) {
            error_log("LogService::getAllLogs error: " . $e->getMessage());
            return ['status' => 500, 'data' => ['error' => 'DB error while fetching logs']];
        }
    }

    public function createLog(array $data): array
    {
        try {
            $requiredFields = ['habit_id', 'value', "user_id"];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || empty(trim($data[$field]))) {
                    return [
                        'status' => 400,
                        'data' => ['error' => "Missing or empty required field: {$field}"]
                    ];
                }
            }
            $data['logged_at'] =  date('Y-m-d');

            //check if the habit_id belongs to the user before creating the log
            $habit = Habit::find($this->connection, $data['habit_id'], "id");
            if (!$habit)
                return [
                    'status' => 500,
                    'data' => ['error' => "no habit found"]
                ];
            $habitArr = $habit->toArray();

            if ($habitArr["user_id"] != $data["user_id"]) {
                return [
                    'status' => 500,
                    'data' => [
                        'message' => 'No such habit for this user',
                        "data" => $habitArr
                    ]
                ];
            }

            $logId = Log::create($this->connection, $data);

            //if Duplicate update the log no need for creating new one
            if ($logId == "Duplicate") {
                $habit_id = $data['habit_id'];
                $date = $data['logged_at'];
                $log = Log::findByHabitIdAndDate($this->connection, $habit_id,$date);
                $log["value"] += $data["value"];
                return $this->updateLog($log["id"], $log);
            }

            //create new log
            if ($logId) {
                return [
                    'status' => 201,
                    'data' => [
                        'message' => 'log created successfully',
                        'id' => $logId
                    ]
                ];
            }

            return ['status' => 500, 'data' => ['error' => 'Failed to create log']];
        } catch (Throwable $e) {
            error_log("LogService::createLog error: " . $e->getMessage());
            return ['status' => 500, 'data' => ['error' => 'DB error occurred while creating log']];
        }
    }

    public function updateLog(int $id, array $data)
    {

        try {
            $log = Log::find($this->connection, $id, "id");
            if (!$log) {
                return ['status' => 404, 'data' => ['error' => 'log not found']];
            }

            if (empty($data)) {
                return ['status' => 400, 'data' => ['error' => 'No data provided for update']];
            }

            $result = $log->update($this->connection,  $data, "id");
            if ($result) {
                return ['status' => 200, 'data' => ['message' => 'log updated successfully']];
            }

            return ['status' => 500, 'data' => ['error' => 'Failed to update log']];
        } catch (Throwable $e) {
            error_log("LogService::updateLog error: " . $e->getMessage());
            return ['status' => 500, 'data' => ['error' => 'DB error occurred while updating log']];
        }
    }

    public function deleteLog(int $id)
    {
        try {
            $log = Log::find($this->connection, $id, 'id');
            if (!$log) {
                return ['status' => 404, 'data' => ['error' => 'log not found']];
            }

            $result = Log::deleteById($id, $this->connection, "id");
            if ($result) {
                return ['status' => 200, 'data' => ['message' => 'log deleted successfully']];
            }

            return ['status' => 500, 'data' => ['error' => 'Failed to delete log']];
        } catch (Throwable $e) {
            error_log("LogService::deleteLog error: " . $e->getMessage());
            return ['status' => 500, 'data' => ['error' => 'DB error occurred while deleting log']];
        }
    }
}
?>