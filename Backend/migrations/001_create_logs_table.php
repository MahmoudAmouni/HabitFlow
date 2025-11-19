<?php
require_once __DIR__ . '/../connection/connection.php';

$sql = "CREATE TABLE IF NOT EXISTS logs (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    user_id   INT NOT NULL,
    habit_id  INT NOT NULL,
    value     INT NOT NULL,
    logged_at DATE  NOT NULL,

    UNIQUE KEY unique_user_habit_dateLog (user_id, habit_id, logged_at),
    KEY idx_user (user_id),

    CONSTRAINT fk_logs_user  FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE,
    CONSTRAINT fk_logs_habit FOREIGN KEY (habit_id) REFERENCES habits(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if ($connection->query($sql) === TRUE) {
    echo json_encode(["message" => "Table 'logs' created successfully."]);
} else {
    echo json_encode(["error" => "Error creating table: " . $connection->error]);
}

$connection->close();
?>