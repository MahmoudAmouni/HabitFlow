<?php
require_once(__DIR__ . "/../models/AiResponse.php");
require_once(__DIR__ . "/../models/User.php");
require_once(__DIR__ . "/serviceHelper.php");

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
            $data = array_map(fn($aiResponse) => $aiResponse->toArray(), $aiResponses);
            return ['status' => 200, 'data' => $data];
        } catch (Exception $e) {
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while getting ai response: ' . $e->getMessage()]];
        }
    }

    public function getAiResponsesByUserId($id, $key): array
    {
        try {
            $data = validateIdExists($this->connection, $id, $key);
            if (isset($data['status']) && $data['status'] !== 200) {
                return $data;
            }

            $aiResponses = AiResponse::findAllByOtherId($this->connection, $id, $key);
            $data = array_map(fn($aiResponse) => $aiResponse->toArray(), $aiResponses);

            return ['status' => 200, 'data' => $data];
        } catch (Exception $e) {
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while getting response by user id: ' . $e->getMessage()]];
        }
    }

    public function createAiResponse(array $data): array
    {
        try {
            $validationResult = validateRequiredFields($data, $this->getRequiredFields());
            if ($validationResult !== true) {
                return $validationResult;
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
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while creating ai response: ' . $e->getMessage()]];
        }
    }

    public function updateAiResponse(int $id, array $data)
    {
        try {
            $aiResponse = AiResponse::find($this->connection, $id, "id");
            if (!$aiResponse) {
                return ['status' => 404, 'data' => ['error' => 'aiResponse not found']];
            }

            if (empty($data)) {
                return ['status' => 400, 'data' => ['error' => 'No data provided for update']];
            }

            $result = $aiResponse->update($this->connection, $data, "id");

            if ($result) {
                return ['status' => 200, 'data' => ['message' => 'aiResponse updated successfully']];
            }

            return ['status' => 500, 'data' => ['error' => 'Failed to update aiResponse']];
        } catch (Exception $e) {
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while updating ai response: ' . $e->getMessage()]];
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
            return ['status' => 500, 'data' => ['error' => 'Database error occurred while deleting ai response: ' . $e->getMessage()]];
        }
    }



    private function getRequiredFields()
    {
        return ['title', 'type', 'suggestion', 'summary'];
    }
}
?>