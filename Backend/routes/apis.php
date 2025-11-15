<?php
$routes = [
    '/users' => ['controllers' => 'userController', 'method' => 'getUsers'],
    '/users/byemail' => ['controllers' => 'userController', 'method' => 'getUserByEmail'],
    '/users/create' => ['controllers' => 'userController', 'method' => 'createUser'],
    '/users/update' => ['controllers' => 'userController', 'method' => 'updateUser'],
    '/users/delete' => ['controllers' => 'userController', 'method' => 'deleteUser'],
    '/AiReviews' => ['controllers' => 'AiRevController', 'method' => 'getAiReviews'],
    '/AiReviews/create' => ['controllers' => 'AiRevController', 'method' => 'createAiReview'],
    '/AiReviews/update' => ['controllers' => 'AiRevController', 'method' => 'updateAiReview'],
    '/AiReviews/delete' => ['controllers' => 'AiRevController', 'method' => 'deleteAiReview'],
    '/AiAnalyze' => ['controllers' => 'AiAnalyze.php', 'method' => 'analyze'],
];