<?php
require_once __DIR__ . '/Model.php';

class AiMeal extends Model
{
    private int $id;
    private string $title;
    private string $url;
    private string $summary;
    private string $created_at;

    protected static string $table = "aimeals";

    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->title = $data["title"];
        $this->url = $data["url"];
        $this->summary = $data["summary"];
        $this->created_at = $data["created_at"];
    }

    public function getId(): int
    {
        return $this->id;
    }




    public function __toString()
    {
        return $this->id . " | " . $this->title . " | "  . $this->url . " | " . $this->summary . "|" . $this->created_at;
    }

    public function toArray()
    {
        return ["id" => $this->id, "title" => $this->title,  "summary" => $this->summary, "created_at" => $this->created_at, "url" => $this->url];
    }

}


?>