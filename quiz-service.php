<?php

function getAllQuizzes() {
    $sql = "SELECT * FROM quiz";
    try {
        $db = getDB();
        $stmt = $db->query($sql);
        $quizzes = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($quizzes == false) {
            $quizzes = array('id'=>null);
        }
        $db = null;
        echo json_encode($quizzes);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function getQuizGame($id) {
    $sql = "SELECT * FROM quiz WHERE game_id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $quizzes = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($quizzes == false) {
            $quizzes = array('id'=>null);
        }
        $db = null;
        echo json_encode($quizzes);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function getQuiz($id) {
    $sql = "SELECT * FROM quiz WHERE id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $quiz = $stmt->fetchObject();
        if ($quiz == false) {
            $quiz = array('id'=>null);
        }
        $db = null;
        echo json_encode($quiz);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function createQuiz() {
    $app = \Slim\Slim::getInstance();
    $type = $app->request()->post('type');
    $question = $app->request()->post('question');
    $answer = $app->request()->post('answer');
    $option = $app->request()->post('option');
    $hint = $app->request()->post('hint');
    $game_id = $app->request()->post('game_id');
    $sql = "INSERT INTO quiz (type,question,answer,option,hint,game_id) VALUES (:type,:question,:answer,:option,:hint,:game_id)";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':question', $question);
        $stmt->bindParam(':answer', $answer);
        $stmt->bindParam(':option', $option);
        $stmt->bindParam(':hint', $hint);
        $stmt->bindParam(':game_id', $game_id);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $output = array('status'=>true, 'message'=>"quiz create success");
        } else {
            $output = array('status'=>false, 'message'=>"quiz create fail");            
        }
        $db = null;
        echo json_encode($output);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function updateQuiz() {
    $app = \Slim\Slim::getInstance();
    $id = $app->request()->post('id');
    $type = $app->request()->post('type');
    $question = $app->request()->post('question');
    $answer = $app->request()->post('answer');
    $option = $app->request()->post('option');
    $hint = $app->request()->post('hint');
    $sql = "UPDATE quiz SET type=:type,question=:question,answer=:answer,option=:option,hint=:hint WHERE id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':question', $question);
        $stmt->bindParam(':answer', $answer);
        $stmt->bindParam(':option', $option);
        $stmt->bindParam(':hint', $hint);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $output = array('status'=>true, 'message'=>"quiz update success");
        } else {
            $output = array('status'=>false, 'message'=>"quiz update fail");
        }
        $db = null;
        echo json_encode($output);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function deleteQuiz() {
    $app = \Slim\Slim::getInstance();
    $id = $app->request()->post('id');
    $sql = "DELETE FROM quiz WHERE id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam('id', $id);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $output = array('status'=>true, 'message'=>"quiz delete success");
        } else {
            $output = array('status'=>false, 'message'=>"quiz delete fail");
        }
        $db = null;
        echo json_encode($output);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

?>