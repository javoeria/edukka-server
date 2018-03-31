<?php

require 'C:\wamp64\www\edukka\Slim\Slim.php';
require_once 'user-service.php';
require_once 'class-service.php';
require_once 'game-service.php';
require_once 'quiz-service.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->contentType('application/json');
$app->get('/', function () { echo "Hello World " . phpversion(); } );
$app->get('/test', 'test');

// User Service
$app->get('/users', 'getAllUsers');
$app->get('/user/:id', 'getUser');
$app->get('/activity/user/:id', 'getUserActivity');
$app->post('/login', 'logIn');
$app->post('/signup', 'signUp');
$app->post('/edit/user', 'updateUser');
$app->post('/delete/user', 'deleteUser');

// Class Service
$app->get('/classes', 'getAllClasses');
$app->get('/class/:id', 'getClass');
$app->get('/myclass/:id', 'getUserClass');
$app->get('/activity/class/:id', 'getClassActivity');
$app->post('/new/class', 'createClass');
$app->post('/edit/class', 'updateClass');
$app->post('/delete/class', 'deleteClass');

// Game Service
$app->get('/games', 'getAllGames');
$app->get('/game/:id', 'getGame');
$app->get('/games/:sub', 'getSubjectGames');
$app->get('/games/:sub&:str', 'searchGames');
$app->post('/new/game', 'createGame');
$app->post('/edit/game', 'updateGame');
$app->post('/delete/game', 'deleteGame');
$app->post('/finish', 'finishGame');
$app->post('/upvote', 'upvoteGame');
$app->post('/downvote', 'downvoteGame');

// Quiz Service
$app->get('/quizzes', 'getAllQuizzes');
$app->get('/quiz/:id', 'getQuiz');
$app->get('/play/:id', 'getGameQuiz');
$app->post('/new/quiz', 'createQuiz');
$app->post('/edit/quiz', 'updateQuiz');
$app->post('/delete/quiz', 'deleteQuiz');

$app->run();

function test() {}

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