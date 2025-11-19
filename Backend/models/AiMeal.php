<?php
require_once __DIR__ . '/Model.php';

class AiMeal extends Model
{
    private int $id;
    private string $title;
    private string $url;
    private string $summary;
    private string $created_at;
    private string $user_id;

    protected static string $table = "aimeals";

    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->title = $data["title"];
        $this->url = $data["url"];
        $this->summary = $data["summary"];
        $this->created_at = $data["created_at"];
        $this->user_id = $data["user_id"];
    }

    public function getId(): int
    {
        return $this->id;
    }




    public function __toString()
    {
        return $this->id . " | " . $this->title . " | "  . $this->url . " | " . $this->user_id ." | " . $this->summary . "|" . $this->created_at;
    }

    public function toArray()
    {
        return ["id" => $this->id, "user_id" => $this->user_id, "title" => $this->title,  "summary" => $this->summary, "created_at" => $this->created_at, "url" => $this->url];
    }

}


?>