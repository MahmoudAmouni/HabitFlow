<?php
include("Model.php");

class AiRev extends Model
{
    private int $id;
    private string $user_id;
    private string $cal_burned;
    private string $cal_need;
    private string $cal_ate;
    private string $suggestions;
    private string $weekly_summary;

    protected static string $table = "aireviews";

    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->user_id = $data["user_id"];
        $this->cal_burned = $data["cal_burned"];
        $this->cal_need = $data["cal_need"];
        $this->cal_ate = $data["cal_ate"];
        $this->suggestions = $data["suggestions"];
        $this->weekly_summary = $data["weekly_summary"];
    }


    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): string
    {
        return $this->user_id;
    }

    public function getCalBurned(): string
    {
        return $this->cal_burned;
    }

    public function getCalNeed(): string
    {
        return $this->cal_need;
    }

    public function getCalAte(): string
    {
        return $this->cal_ate;
    }

    public function getSuggestions(): string
    {
        return $this->suggestions;
    }

    public function getWeeklySummary(): string
    {
        return $this->weekly_summary;
    }

    // Setters
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setUserId(string $user_id): self
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function setCalBurned(string $cal_burned): self
    {
        $this->cal_burned = $cal_burned;
        return $this;
    }

    public function setCalNeed(string $cal_need): self
    {
        $this->cal_need = $cal_need;
        return $this;
    }

    public function setCalAte(string $cal_ate): self
    {
        $this->cal_ate = $cal_ate;
        return $this;
    }

    public function setSuggestions(string $suggestions): self
    {
        $this->suggestions = $suggestions;
        return $this;
    }

    public function setWeeklySummary(string $weekly_summary): self
    {
        $this->weekly_summary = $weekly_summary;
        return $this;
    }




    public function __toString()
    {
        return $this->id . " | " . $this->user_id . " | " . $this->cal_burned . " | " . $this->cal_need . " | " . $this->cal_ate . " | " . $this->suggestions . " | " . $this->weekly_summary;
    }

    public function toArray()
    {
        return [
            "id" => $this->id,
            "user_id" => $this->user_id,
            "cal_burned" => $this->cal_burned,
            "cal_need" => $this->cal_need,
            "cal_ate" => $this->cal_ate,
            "suggestions" => $this->suggestions,
            "weekly_summary" => $this->weekly_summary
        ];
    }

}


?>