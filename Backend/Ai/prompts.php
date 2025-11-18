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



function CreateSummaryAiPrompt($userHabits, $logs, $type)
{
    $habitsList = "";
    if (!empty($userHabits)) {
        foreach ($userHabits as $habit) {
            $name = $habit['name'] ?? 'Unknown';
            $unit = $habit['unit'] ?? '';
            $habitsList .= "- habit_id: {$habit['id']}, habit_name: \"{$name}\", unit: \"{$unit}\"\n";
        }
    }

    $logsList = "";
    if (!empty($logs)) {
        foreach ($logs as $log) {
            $loggedAt = $log['logged_at'] ?? 'unknown date';
            $habitId = $log['habit_id'] ?? 'unknown';
            $value = $log['value'] ?? 'unknown value';
            $unit = $log['unit'] ?? ''; // This comes from the enriched log
            $logsList .= "- Date: {$loggedAt}, Habit ID: {$habitId}, Value: {$value} {$unit}\n";
        }
    }

    $timeFrame = match ($type) {
        'daily' => "for today",
        'weekly' => "for the past 7 days",
        'monthly' => "for the past 30 days",
        default => "for the specified period"
    };

    $prompt = "You are a silent JSON-only parser.
Generate a summary and suggestions based on the user's log data.

" . ($habitsList ? "User's habits:\n{$habitsList}\n" : "") .
        ($logsList ? "User's logs {$timeFrame}:\n{$logsList}\n" : "")
        . "
Instructions:
1. Match logs to habits using habit_id from both sections.
2. Analyze the logs and generate a concise summary of overall performance {$timeFrame}.
3. If logs are empty, say 'No data available.' in summary.
4. Provide one clear suggestion for improvement based on patterns or gaps.
5. Return ONLY a JSON object with these exact fields:
-title:\"Daily Summary or Weekly Summary or Monthly Summary(depends on the type)\"
- summary: write a small summary and say overall average for example also DONT restate the data in logs just talk in general about it string (summary of overall performance — good, average, poor, or 'No data available.')
- suggestion: string (what the user should focus on more — empty string if no data)
6. NEVER add text before or after the JSON object.
7. Do NOT invent habits, values, or dates.

Response:";

    return $prompt;
}

function CreateMealAiPrompt($userHabits, $logs)
{
    $habitsList = "";
    if (!empty($userHabits)) {
        foreach ($userHabits as $habit) {
            $name = $habit['name'] ?? 'Unknown';
            $unit = $habit['unit'] ?? '';
            $habitsList .= "- habit_id: {$habit['id']}, habit_name: \"{$name}\", unit: \"{$unit}\"\n";
        }
    }

    $logsList = "";
    if (!empty($logs)) {
        foreach ($logs as $log) {
            $loggedAt = $log['logged_at'] ?? 'unknown date';
            $habitId = $log['habit_id'] ?? 'unknown';
            $value = $log['value'] ?? 'unknown value';
            $unit = $log['unit'] ?? '';
            $logsList .= "- Date: {$loggedAt}, Habit ID: {$habitId}, Value: {$value} {$unit}\n";
        }
    }




    $prompt = "You are a silent JSON-only parser.
Generate a globally inspired, nutritious meal suggestion tailored to the user's activity level.

" . ($habitsList ? "User's habits:\n{$habitsList}\n" : "") .
        ($logsList ? "User's recent activity logs:\n{$logsList}\n" : "")
        . "
Instructions:
1. Match logs to habits using habit_id from both sections.
2. Based on activity level:
   - If HIGH activity (gym, running, long walks >5000 steps): 
     Suggest a meal from a global cuisine that is naturally rich in PROTEIN and COMPLEX CARBS.
     Instead, choose one of these or similar global dishes:
   - If LOW activity: 
     Suggest a light, nutrient-dense global meal :
3. In your summary, EXPLICITLY state what nutrients the meal is rich in — e.g., 'This dish is rich in plant-based protein, iron, and omega-3 fatty acids.'
4. Return ONLY a JSON object with these exact fields:
- title: string (name of the global dish — e.g., 'Chana Masala with Quinoa')
- summary: string (a short, motivational explanation that includes nutrient benefits — e.g., 'Your active lifestyle needs recovery fuel — this Indian dish delivers plant protein, iron, and fiber to rebuild muscle and boost energy.')
- url: string (a direct URL to a high-quality image of the meal — use https://images.unsplash.com/... or similar. Must be a direct image link — no blogs, pages, or YouTube.)
5. NEVER repeat 'grilled chicken' or any generic Western protein combo.
6. NEVER invent habits, values, or dates.
7. The meal must be real, culturally authentic, and visually appealing.

Response:";

    return $prompt;
}
?>