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
    '/logs/create' => ['controllers' => 'logController', 'method' => 'createLog'],
    '/logs/update' => ['controllers' => 'logController', 'method' => 'updateLog'],
    '/logs/delete' => ['controllers' => 'logController', 'method' => 'deleteLog'],


    '/AiAnalyze' => ['controllers' => 'AiAnalyze.php', 'method' => 'analyze'],
];