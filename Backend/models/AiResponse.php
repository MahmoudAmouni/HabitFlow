<?php
require_once __DIR__ . '/Model.php';

class AiResponse extends Model
{
    private int $id;
    private string $title;
    private string $type;
    private string $suggestion;
    private string $summary;
    private string $created_at;
    private string $user_id;

    protected static string $table = "airesponses";

    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->title = $data["title"];
        $this->type = $data["type"];
        $this->suggestion = $data["suggestion"];
        $this->summary = $data["summary"];
        $this->created_at = $data["created_at"];
        $this->user_id = $data["user_id"];
    }




    public function __toString()
    {
        return $this->id . " | " . $this->title . " | " . $this->type . " | " . $this->suggestion . " | " . $this->summary .  "|" . $this->created_at . "|" . $this->user_id;
    }

    public function toArray()
    {
        return ["id" => $this->id, "user_id" => $this->user_id, "title" => $this->title, "type" => $this->type, "summary" => $this->summary,"created_at" => $this->created_at, "suggestion" => $this->suggestion];
    }

}


?>