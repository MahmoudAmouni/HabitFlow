<?php
require_once __DIR__ . '/../connection/connection.php';

$sql = "
CREATE TABLE IF NOT EXISTS users (
    id          INT(11) AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(255) NOT NULL,
    email       VARCHAR(255) NOT NULL UNIQUE,
    password    TEXT         NOT NULL,
    gender      VARCHAR(255) NOT NULL,
    weight      FLOAT        NOT NULL,
    height      FLOAT        NOT NULL,
    role        VARCHAR(255) NOT NULL DEFAULT 'client'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";

if ($connection->query($sql) === TRUE) {
    echo json_encode(["message" => "Table 'users' created successfully."]);
} else {
    echo json_encode(["error" => "Error creating table: " . $connection->error]);
}

$connection->close();
?>