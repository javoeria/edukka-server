<?php

require 'C:\wamp64\www\edukka\Slim\Slim.php';
require_once 'user-service.php';
require_once 'class-service.php';
require_once 'game-service.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->contentType('application/json');
$app->get('/', function () { echo "Hello World"; } );

// User Service
$app->get('/users', 'getAllUsers');
$app->get('/user/:id', 'getUser');
$app->post('/login', 'logIn');
$app->post('/signup', 'signUp');
$app->get('/delete/:id', 'deleteUser');
$app->post('/update/:id', 'updateUser');

$app->get('/myclass/:id', 'getUsersClass');

// Class Service
$app->get('/classes', 'getAllClasses');
$app->get('/class/:id', 'getClass');
$app->post('/create-c', 'createClass');
$app->get('/delete-c/:id', 'deleteClass');

// Game Service
$app->get('/games', 'getAllGames');
$app->get('/search/:str', 'searchGames');
$app->get('/game/:id', 'getGame');
$app->post('/create-g', 'createGame');
$app->get('/delete-g/:id', 'deleteGame');

$app->run();

function getDB() {
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "server";

    $mysql_conn_string = "mysql:host=$dbhost;dbname=$dbname";
    $dbConnection = new PDO($mysql_conn_string, $dbuser, $dbpass);
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbConnection;
}

?>