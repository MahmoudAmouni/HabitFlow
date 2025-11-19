<?php
require_once(__DIR__ . "/../models/User.php");
require_once(__DIR__ . "/../models/Habit.php");

function validateRequiredFields(array $data, array $requiredFields)
{
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            return [
                'status' => 400,
                'data' => ['error' => "Missing or empty required field: {$field}"]
            ];
        }
    }
    return true;
}


function validateIdExists($connection,$id, $key)
{
    $data = "";
    if ($key == "user_id") {
        $data = User::find($connection, $id, 'id');
    } else {
        $data = Habit::find($connection, $id, 'id');
    }

    if (!$data) {
        return ['status' => 404, 'data' => ['error' => 'Wrong id']];
    }

    return ['status' => 200, 'data' => 'Valid'];
}



?>