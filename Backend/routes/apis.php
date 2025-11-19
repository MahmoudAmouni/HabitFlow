<?php
$routes = [
    '/users' => ['controllers' => 'userController', 'method' => 'getUsers'],
    '/users/byemail' => ['controllers' => 'userController', 'method' => 'getUserByEmail'],
    '/users/create' => ['controllers' => 'userController', 'method' => 'createUser'],
    '/users/update' => ['controllers' => 'userController', 'method' => 'updateUser'],
    '/users/delete' => ['controllers' => 'userController', 'method' => 'deleteUser'],


    '/habits' => ['controllers' => 'habitController', 'method' => 'getHabits'],
    '/habits/create' => ['controllers' => 'habitController', 'method' => 'createHabit'],
    '/habits/update' => ['controllers' => 'habitController', 'method' => 'updateHabit'],
    '/habits/delete' => ['controllers' => 'habitController', 'method' => 'deleteHabit'],


    '/logs' => ['controllers' => 'logController', 'method' => 'getLogs'],
    '/logs/createLogFromAiResponse' => ['controllers' => 'logController', 'method' => 'createLogFromAiResponse'],
    '/logs/create' => ['controllers' => 'logController', 'method' => 'createLog'],
    '/logs/update' => ['controllers' => 'logController', 'method' => 'updateLog'],
    '/logs/delete' => ['controllers' => 'logController', 'method' => 'deleteLog'],

    '/aiResponses' => ['controllers' => 'aiResponseController', 'method' => 'getAiResponses'],
    '/aiResponses/create' => ['controllers' => 'aiResponseController', 'method' => 'createAiResponse'],
    '/aiResponses/update' => ['controllers' => 'aiResponseController', 'method' => 'updateAiResponse'],
    '/aiResponses/delete' => ['controllers' => 'aiResponseController', 'method' => 'deleteAiResponse'],


    '/aiMeals' => ['controllers' => 'aiMealController', 'method' => 'getAiMeals'],
    '/aiMeals/create' => ['controllers' => 'aiMealController', 'method' => 'createAiMeal'],
    '/aiMeals/update' => ['controllers' => 'aiMealController', 'method' => 'updateAiMeal'],
    '/aiMeals/delete' => ['controllers' => 'aiMealController', 'method' => 'deleteAiMeal'],


    '/AiAnalyze' => ['controllers' => 'AiAnalyze.php', 'method' => 'analyze'],
];