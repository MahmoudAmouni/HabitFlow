<?php
$routes = [
    '/users' => ['controllers' => 'userController', 'method' => 'getUsers'],
    '/users/create' => ['controllers' => 'userController', 'method' => 'createUser'],
    '/users/update' => ['controllers' => 'userController', 'method' => 'updateUser'],
    '/users/delete' => ['controllers' => 'userController', 'method' => 'deleteUser'],
];