<?php

require 'C:\wamp64\www\edukka\Slim\Slim.php'; //require '.././Slim/Slim.php';
require_once 'user-service.php';
require_once 'class-service.php';
require_once 'game-service.php';
require_once 'quiz-service.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->contentType('application/json');
$app->get('/', function () { echo 'Edukka Server ' . phpversion(); } );

// User Service
$app->get('/users', 'getAllUsers');
$app->get('/user/:id', 'getUser');
$app->get('/user/activity/:id', 'getUserActivity');
$app->post('/login', 'logIn');
$app->post('/signup', 'signUp');
$app->post('/user/edit', 'updateUser');
$app->post('/user/delete', 'deleteUser');

// Class Service
$app->get('/classes', 'getAllClasses');
$app->get('/class/:id', 'getClass');
$app->get('/myclass/:id', 'getUserClass');
$app->get('/class/activity/:id', 'getClassActivity');
$app->post('/class/new', 'createClass');
$app->post('/class/edit', 'updateClass');
$app->post('/class/delete', 'deleteClass');
$app->post('/class/adduser', 'addUserClass');
$app->post('/class/remuser', 'removeUserClass');

// Game Service
$app->get('/games', 'getAllGames');
$app->get('/game/:id', 'getGame');
$app->get('/games/:sub', 'getSubjectGames');
$app->get('/games/:sub?:str', 'searchGames');
$app->post('/game/new', 'createGame');
$app->post('/game/edit', 'updateGame');
$app->post('/game/delete', 'deleteGame');
$app->post('/game/finish', 'finishGame');
$app->post('/game/upvote', 'upvoteGame');
$app->post('/game/downvote', 'downvoteGame');

// Quiz Service
$app->get('/quizzes', 'getAllQuizzes');
$app->get('/quiz/:id', 'getQuiz');
$app->get('/play/:id', 'getGameQuiz');
$app->post('/quiz/new', 'createQuiz');
$app->post('/quiz/edit', 'updateQuiz');
$app->post('/quiz/delete', 'deleteQuiz');

$app->run();

function getDB() {
    $dbhost = 'localhost';
    $dbuser = 'root';       //$dbuser = 'id5255892_root';
    $dbpass = '';           //$dbpass = 'k4zGDiZJ6EqCKnkDOhAH';
    $dbname = 'edukka';     //$dbname = 'id5255892_edukka';

    $mysql_conn_string = "mysql:host=$dbhost;dbname=$dbname;charset=utf8mb4";
    $dbConnection = new PDO($mysql_conn_string, $dbuser, $dbpass);
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbConnection;
}

?>