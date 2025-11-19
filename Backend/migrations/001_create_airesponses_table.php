<?php
require_once __DIR__ . '/../connection/connection.php';

$sql = "CREATE TABLE IF NOT EXISTS airesponses (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    title       TEXT COLLATE utf8mb4_general_ci NOT NULL,
    summary     TEXT COLLATE utf8mb4_general_ci NOT NULL,
    suggestion  TEXT COLLATE utf8mb4_general_ci NOT NULL,
    type        TEXT COLLATE utf8mb4_general_ci NOT NULL,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    KEY idx_user (user_id),
    CONSTRAINT fk_airesponses_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci";

if ($connection->query($sql) === TRUE) {
    echo json_encode(["message" => "Table 'airesponses' created successfully."]);
} else {
    echo json_encode(["error" => "Error creating table: " . $connection->error]);
}

$connection->close();
?>