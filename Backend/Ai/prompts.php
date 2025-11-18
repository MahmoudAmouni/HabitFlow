<?php

function CreateLogsAiPrompt($userHabits, $text)
{

    $habitsList = "";
    if (!empty($userHabits)) {
        foreach ($userHabits as $habit) {
            $name = $habit['name'] ?? 'Unknown';
            $unit = $habit['unit'] ?? ''; 
            $habitsList .= "- habit_id: {$habit['id']}, habit_name: \"{$name}\", unit: \"{$unit}\"\n";
        }
    } else {
        $habitsList = "No habits defined.";
    }

    $prompt = "You are a silent JSON-only parser.
The user logs activities in free text.
You have access to the user's tracked habits below.

User's habits:
{$habitsList}

Instructions:
1. Parse the user's text and extract values that match ANY of the habits above DONT CREATE A HABIT AND USE THE UNITS SENT BY THE USERS HABIT
DONT CREATE UNIT BUT CONVERT .
2. Convert values to the correct unit using the world rules for example 60 minutes = 1hr and for example these rules:
- If user says 'walked for X minutes', assume 120 steps/minute → steps = X * 120
- If user says 'ran for X minutes', assume 600 steps/minute → steps = X * 600
- If user mentions food, estimate calories (e.g., apple ≈ 80 kcal, banana ≈ 100 kcal, coffee ≈ 2 kcal)
- For mood words: map to nearest ['great','good','neutral','bad','awful']
3. Return ONLY a JSON array of objects with these exact fields:
- habit_id: integer
- habit_name: string
- value: number (integer or decimal)
- unit: string (as defined in user's habits)
4. Skip habits not mentioned in the text.
5. NEVER add text before or after the JSON array.

User Text: {$text}";

    return $prompt;
}

?>