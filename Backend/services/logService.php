<?php
require_once(__DIR__ . "/../models/Log.php");
require_once(__DIR__ . "/../models/User.php");
require_once(__DIR__ . "/../models/Habit.php");
require_once(__DIR__ . "/serviceHelper.php");

class LogService
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function getLogById(int $id)
    {
        try {
            $log = Log::find($this->connection, $id, 'id');
            return $log
                ? ['status' => 200, 'data' => $log->toArray()]
                : ['status' => 404, 'data' => ['error' => 'log not found']];
        } catch (Exception $e) {
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while getting log by id: ' . $e->getMessage()]];
        }
    }

    public function getLogsByOtherId($id, $key)
    {
        try {
            $validationResult = validateIdExists($this->connection, $id, $key);
            if (isset($validationResult['status']) && $validationResult['status'] !== 200) {
                return $validationResult;
            }

            $logs = Log::findAllByOtherId($this->connection, $id, $key);
            $data = array_map(fn($log) => $log->toArray(), $logs);

            return ['status' => 200, 'data' => $data];
        } catch (Exception $e) {
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while getting logs by other ids: ' . $e->getMessage()]];
        }
    }

    public function getAllLogs()
    {
        try {
            $logs = Log::findAll($this->connection);
            $data = array_map(fn($log) => $log->toArray(), $logs);

            return ['status' => 200, 'data' => $data];
        } catch (Exception $e) {
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while getting all logs: ' . $e->getMessage()]];
        }
    }

    public function createLog(array $data)
    {
        try {
            $validationResult = validateRequiredFields($data, $this->getRequiredFields());
            if ($validationResult !== true) {
                return $validationResult;
            }

            $date = $data['logged_at']?? date('Y-m-d') ;
            $data['logged_at'] = $date;

            $validationResult = $this->validateHabitOwnership($data);
            if (isset($validationResult['status']) && $validationResult['status'] !== 200) {
                return $validationResult;
            }

            $logId = Log::create($this->connection, $data);

            if ($logId == "Duplicate") {
                return $this->handleDuplicateLog($data);
            }

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
        } catch (Exception $e) {
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while creating a log: ' . $e->getMessage()]];
        }
    }

    public function createLogFromAiResponse(array $data)
    {
        $results = [];
        $successCount = 0;
        $errorCount = 0;
        if(!$data['array_data']){
            return ['status' => 400, 'data' => ['error' => 'No data provided for update']];
        }
        

        foreach ($data['array_data'] as $logData) {
            $result = $this->createLog($logData);
            $results[] = $result;
            if (isset($result['status']) && $result['status'] == 201) {
                $successCount++;
            } else {
                $errorCount++;
            }
        }

        return [
            'status' => $errorCount === 0 ? 201 : ($successCount > 0 ? 207 : 500), 
            'data' => [
                'message' => $errorCount === 0
                    ? 'All logs created successfully'
                    : ($successCount > 0
                        ? 'Some logs created successfully'
                        : 'Failed to create any logs'),
                'total_processed' => count($data),
                'successful' => $successCount,
                'failed' => $errorCount,
                'results' => $results
            ]
        ];
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

            $result = $log->update($this->connection, $data, "id");
            if ($result) {
                return ['status' => 200, 'data' => ['message' => 'log updated successfully']];
            }

            return ['status' => 500, 'data' => ['error' => 'Failed to update log']];
        } catch (Exception $e) {
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while updating a log: ' . $e->getMessage()]];
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
        } catch (Exception $e) {
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while deleting a log: ' . $e->getMessage()]];
        }
    }

    

    private function validateHabitOwnership(array $data)
    {
        $habit = Habit::find($this->connection, $data['habit_id'], "id");
        if (!$habit) {
            return [
                'status' => 500,
                'data' => ['error' => "no habit found"]
            ];
        }

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

        return ['status' => 200, 'data' => 'Valid'];
    }

    private function handleDuplicateLog(array $data)
    {
        $habit_id = $data['habit_id'];
        $date = $data['logged_at'];
        $log = Log::findByHabitIdAndDate($this->connection, $habit_id, $date);
        $log["value"] += $data["value"];
        return $this->updateLog($log["id"], $log);
    }

    private function getRequiredFields()
    {
        return ['habit_id', 'value', "user_id"];
    }
}
?>