<?php
require_once __DIR__ . '/Model.php';
class Log extends Model
{
    private int $id;
    private int $user_id;
    private int $habit_id;
    private string $value;
    private string $logged_at;

    protected static string $table = "logs";

    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->user_id = $data["user_id"];
        $this->habit_id = $data["habit_id"];
        $this->value = $data["value"];
        $this->logged_at = $data["logged_at"];
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function gethabit_id(): string
    {
        return $this->habit_id;
    }


    public function getvalue(): string
    {
        return $this->value;
    }

    public function getlogged_at(): string
    {
        return $this->logged_at;
    }



    // Setters
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }



    public function setUser_id(int $user_id): self
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function setvalue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function setlogged_at(string $logged_at): self
    {
        $this->logged_at = $logged_at;
        return $this;
    }


    public static function findByHabitIdAndDate(mysqli $connection, int $habitId, string $date)
    {
        $sql = "SELECT * FROM logs WHERE habit_id = ? AND logged_at = ?";
        $query = $connection->prepare($sql);
        $query->bind_param("is", $habitId, $date);
        $query->execute();

        $data = $query->get_result()->fetch_assoc();

        return $data ?: null;
    }


    public function __toString()
    {
        return $this->id . " | " . $this->habit_id . " | "  . $this->logged_at ." | " . $this->value . " | " . $this->user_id;
    }

    public function toArray()
    {
        return ["id" => $this->id,"user_id" => $this->user_id,"habit_id" => $this->habit_id, "value" => $this->value, "logged_at" => $this->logged_at];
    }

}


?>