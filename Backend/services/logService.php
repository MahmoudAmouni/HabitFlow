<?php
require_once( __DIR__ . "/../models/Log.php");

class LogService
{

    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }
    public function getLogs($id)
    {
        if ($id) {
            $log = Log::find($this->connection, $id);
            if ($log) {
                return ['status' => 200, 'data' => $log->toArray()];
            }
            return ['status' => 404, 'data' => ['error' => 'log not found']];
        }

        $logs = Log::findAll($this->connection);
        $data = [];
        foreach ($logs as $log) {
            $data[] = $log->toArray();
        }
        return ['status' => 200, 'data' => $data];
    }

    public function createLog(array $data): array
    {
        $requiredFields = ['habit_id', 'value'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty(trim($data[$field]))) {
                return [
                    'status' => 400,
                    'data' => ['error' => "Missing or empty required field: {$field}"]
                ];
            }
        }
        $data['logged_at']= date('Y-m-d');
        echo $data['logged_at'];
        $logId = Log::create($this->connection, $data);
        if ($logId == 1062) {
            return [
                'status' => 500,
                'data' => [
                    'message' => 'duplicate log name',
                ]
            ];
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
    }

    public function updateLog(int $id, array $data)
    {
        $log = Log::find($this->connection, $id);
        if (!$log) {
            return ['status' => 404, 'data' => ['error' => 'log not found']];
        }

        if (empty($data)) {
            return ['status' => 400, 'data' => ['error' => 'No data provided for update']];
        }

        $result = Log::update($this->connection, $id, $data);
        if ($result) {
            return ['status' => 200, 'data' => ['message' => 'log updated successfully']];
        }

        return ['status' => 500, 'data' => ['error' => 'Failed to update log']];
    }

    public function deleteLog(int $id)
    {
        $log = Log::find($this->connection, $id);
        if (!$log) {
            return ['status' => 404, 'data' => ['error' => 'log not found']];
        }

        $result = Log::deleteById($id, $this->connection);
        if ($result) {
            return ['status' => 200, 'data' => ['message' => 'log deleted successfully']];
        }

        return ['status' => 500, 'data' => ['error' => 'Failed to delete log']];
    }

}
?>