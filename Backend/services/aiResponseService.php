<?php
require_once(__DIR__ . "/../models/AiResponse.php");
require_once(__DIR__ . "/../models/User.php");

class AiResponseService
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function getAiResponse()
    {
        try {
            $aiResponses = AiResponse::findAll($this->connection);
            $data = [];
            foreach ($aiResponses as $aiResponse) {
                $data[] = $aiResponse->toArray();
            }
            return ['status' => 200, 'data' => $data];
        } catch (Exception $e) {
            error_log("aiResponseService::getaiResponses error: " . $e->getMessage());
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while fetching aiResponses']];
        }
    }

    public function getAiResponsesByUserId($id, $key): array
    {
        try {
            $data = "";
            if ($key == "user_id") {
                $data = User::find($this->connection, $id, 'id');
            } 
            if (!$data) {
                return ['status' => 404, 'data' => ['error' => 'Wrong id']];
            }
            $aiResponses = AiResponse::findAllByOtherId($this->connection, $id, $key);
            $data = array_map(fn($aiResponses) => $aiResponses->toArray(), $aiResponses);

            return ['status' => 200, 'data' => $data];
        } catch (Throwable $e) {
            error_log("AiResponseService::getLogsByOtherId error: " . $e->getMessage());
            return ['status' => 500, 'data' => ['error' => 'DB error while fetching user aiResponses' . $e->getMessage() ]];
        }
    }


    public function createAiResponse(array $data): array
    {
        try {
            $requiredFields = ['title','type','suggestion','summary'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || empty(trim($data[$field]))) {
                    return [
                        'status' => 400,
                        'data' => ['error' => "Missing or empty required field: {$field}"]
                    ];
                }
            }

            $aiResponseId = AiResponse::create($this->connection, $data);
            if ($aiResponseId) {
                return [
                    'status' => 201,
                    'data' => [
                        'message' => 'aiResponse created successfully',
                        'id' => $aiResponseId
                    ]
                ];
            }

            return ['status' => 500, 'data' => ['error' => 'Failed to create aiResponse']];
        } catch (Exception $e) {
            error_log("aiResponseService::createaiResponse error: " . $e->getMessage());
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while creating aiResponse']];
        }
    }

    public function updateAiResponse(int $id, array $data)
    {
        try {
            $aiResponse = AiResponse::find($this->connection, $id, "id");
            if (!$aiResponse)
                return ['status' => 404, 'data' => ['error' => 'aiResponse not found']];

            if (empty($data))
                return ['status' => 400, 'data' => ['error' => 'No data provided for update']];

            $result = $aiResponse->update($this->connection, $data, "id");

            if ($result)
                return ['status' => 200, 'data' => ['message' => 'aiResponse updated successfully']];

            return ['status' => 500, 'data' => ['error' => 'Failed to update aiResponse']];
        } catch (Exception $e) {
            error_log("aiResponseService::updateaiResponse error: " . $e->getMessage());
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while updating aiResponse']];
        }
    }

    public function deleteAiResponse(int $id)
    {
        try {
            $aiResponse = AiResponse::find($this->connection, $id, "id");
            if (!$aiResponse) {
                return ['status' => 404, 'data' => ['error' => 'aiResponse not found']];
            }

            $result = AiResponse::deleteById($id, $this->connection, "id");
            if ($result) {
                return ['status' => 200, 'data' => ['message' => 'aiResponse deleted successfully']];
            }

            return ['status' => 500, 'data' => ['error' => 'Failed to delete aiResponse']];
        } catch (Exception $e) {
            error_log("aiResponseService::deleteaiResponse error: " . $e->getMessage());
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while deleting aiResponse']];
        }
    }
}
?>