<?php
require_once __DIR__ . '/Model.php';

class AiMeal extends Model
{
    private int $id;
    private string $title;
    private string $url;
    private string $summary;

    protected static string $table = "aimeals";

    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->title = $data["title"];
        $this->url = $data["url"];
        $this->summary = $data["summary"];
    }




    public function __toString()
    {
        return $this->id . " | " . $this->title . " | "  . $this->url . " | " . $this->summary;
    }

    public function toArray()
    {
        return ["id" => $this->id, "title" => $this->title,  "summary" => $this->summary, "url" => $this->url];
    }

}


?>