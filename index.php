<?php

require 'C:\wamp64\www\edukka\Slim\Slim.php';
require_once 'user-service.php';
require_once 'class-service.php';
require_once 'game-service.php';
require_once 'quiz-service.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->contentType('application/json');
$app->get('/', function () { echo "Hello World"; } );
$app->post('/test', 'test');

// User Service
$app->get('/users', 'getAllUsers');
$app->get('/user/:id', 'getUser');
$app->post('/login', 'logIn');
$app->post('/signup', 'signUp');
$app->get('/delete/:id', 'deleteUser');
$app->post('/update/:id', 'updateUser');

// Class Service
$app->get('/classes', 'getAllClasses');
$app->get('/class/:id', 'getClass');
$app->post('/new-c', 'createClass');
$app->get('/del-c/:id', 'deleteClass');
$app->post('/up-c/:id', 'updateClass');
$app->get('/myclass/:id', 'getUserClass');
$app->get('/add', 'addUserClass');
$app->get('/remove', 'removeUserClass');

// Game Service
$app->get('/games', 'getAllGames');
$app->get('/games/:sub/:str', 'searchGames');
$app->get('/games/:sub', 'getGameSubject');
$app->get('/game/:id', 'getGame');
$app->post('/new-g', 'createGame');
$app->get('/del-g/:id', 'deleteGame');
$app->post('/up-g/:id', 'updateGame');
$app->get('/playgame/:id', 'getQuizGame');

// Quiz Service
$app->get('/quizzes', 'getAllQuizzes');
$app->get('/quiz/:id', 'getQuiz');
$app->post('/new-q', 'createQuiz');
$app->get('/del-q/:id', 'deleteQuiz');
$app->post('/up-c/:id', 'updateQuiz');

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