<?php
require_once __DIR__ . '/../connection/connection.php';

$sql = "CREATE TABLE IF NOT EXISTS habits (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    user_id  INT NOT NULL,
    name     VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
    unit     VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
    target   VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
    
    UNIQUE KEY unique_user_habitname (user_id, name),
    KEY        idx_user            (user_id),
    CONSTRAINT fk_habits_user 
        FOREIGN KEY (user_id) REFERENCES users(id) 
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if ($connection->query($sql) === TRUE) {
    echo json_encode(["message" => "Table 'habits' created successfully."]);
} else {
    echo json_encode(["error" => "Error creating table: " . $connection->error]);
}

$connection->close();
?>