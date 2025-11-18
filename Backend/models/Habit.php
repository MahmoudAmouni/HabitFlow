<?php
require_once __DIR__ . '/Model.php';
class Habit extends Model
{
    private int $id;
    private string $name;
    private int $user_id;
    private string $unit;
    private string $target;

    protected static string $table = "habits";

    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->name = $data["name"];
        $this->user_id = $data["user_id"];
        $this->unit = $data["unit"];
        $this->target = $data["target"];
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUser_id(): string
    {
        return $this->user_id;
    }

    public function getunit(): string
    {
        return $this->unit;
    }

    public function gettarget(): string
    {
        return $this->target;
    }



    // Setters
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setUser_id(string $user_id): self
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function setunit(string $unit): self
    {
        $this->unit = $unit;
        return $this;
    }

    public function settarget(string $target): self
    {
        $this->target = $target;
        return $this;
    }







    public function __toString()
    {
        return $this->id . " | " . $this->name . " | " . $this->user_id . " | " . $this->target . " | " . $this->unit;
    }

    public function toArray()
    {
        return ["id" => $this->id, "name" => $this->name, "user_id" => $this->user_id, "unit" => $this->unit];
    }

}


?>