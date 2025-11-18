<?php
require_once __DIR__ . '/Model.php';

class AiResponse extends Model
{
    private int $id;
    private string $title;
    private string $type;
    private string $suggestion;
    private string $summary;

    protected static string $table = "airesponses";

    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->title = $data["title"];
        $this->type = $data["type"];
        $this->suggestion = $data["suggestion"];
        $this->summary = $data["summary"];
    }




    public function __toString()
    {
        return $this->id . " | " . $this->title . " | " . $this->type . " | " . $this->suggestion . " | " . $this->summary;
    }

    public function toArray()
    {
        return ["id" => $this->id, "title" => $this->title, "type" => $this->type, "summary" => $this->summary, "suggestion" => $this->suggestion];
    }

}


?>