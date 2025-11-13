<?php
require_once(__DIR__ . "/../models/AiRev.php");

class AiRevServices
{

    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }
    public function getAiReviews($id)
    {
        if ($id) {
            $aiRev = AiRev::find($this->connection, $id);
            if ($aiRev) {
                return ['status' => 200, 'data' => $aiRev->toArray()];
            }
            return ['status' => 404, 'data' => ['error' => 'aiRev not found']];
        }

        $aiRevs = AiRev::findAll($this->connection);
        $data = [];
        foreach ($aiRevs as $aiRev) {
            $data[] = $aiRev->toArray();
        }
        return ['status' => 200, 'data' => $data];
    }

    public function createAiReview(array $data): array
    {
        $requiredFields = ['name', 'email', 'password', 'height', 'weight', 'gender'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty(trim($data[$field]))) {
                return [
                    'status' => 400,
                    'data' => ['error' => "Missing or empty required field: {$field}"]
                ];
            }
        }

        $aiRevId = AiRev::create($this->connection, $data);
        if ($aiRevId) {
            return [
                'status' => 201,
                'data' => [
                    'message' => 'aiRev created successfully',
                    'id' => $aiRevId
                ]
            ];
        }

        return ['status' => 500, 'data' => ['error' => 'Failed to create aiRev']];
    }

    public function updateAiReview(int $id, array $data)
    {
        $aiRev = AiRev::find($this->connection, $id);
        if (!$aiRev) {
            return ['status' => 404, 'data' => ['error' => 'aiRev not found']];
        }

        if (empty($data)) {
            return ['status' => 400, 'data' => ['error' => 'No data provided for update']];
        }

        $result = AiRev::update($this->connection, $id, $data);
        if ($result) {
            return ['status' => 200, 'data' => ['message' => 'aiRev updated successfully']];
        }

        return ['status' => 500, 'data' => ['error' => 'Failed to update aiRev']];
    }

    public function deleteAiReview(int $id)
    {
        $aiRev = AiRev::find($this->connection, $id);
        if (!$aiRev) {
            return ['status' => 404, 'data' => ['error' => 'aiRev not found']];
        }

        $result = AiRev::deleteById($id, $this->connection);
        if ($result) {
            return ['status' => 200, 'data' => ['message' => 'aiRev deleted successfully']];
        }

        return ['status' => 500, 'data' => ['error' => 'Failed to delete aiRev']];
    }



    public function validateAiReviewData(array $data, bool $isUpdate = false): array
    {
        $errors = [];
        $fields = ['name', 'email', 'password', 'height', 'weight', 'gender'];

        foreach ($fields as $field) {
            if (!$isUpdate) {
                if (!isset($data[$field]) || empty(trim($data[$field]))) {
                    $errors[] = ucfirst($field) . ' is required';
                }
            } else {
                if (isset($data[$field]) && empty(trim($data[$field]))) {
                    $errors[] = ucfirst($field) . ' cannot be empty';
                }
            }
        }
        return $errors;
    }
}
?>