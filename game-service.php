<?php

function getAllGames() {
    $sql = "SELECT * FROM game";
    try {
        $db = getDB();
        $stmt = $db->query($sql);
        $games = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($games == false) {
            $games = array('id'=>null);
        }
        $db = null;
        echo json_encode($games);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function getGame($id) {
    $sql = "SELECT * FROM game WHERE id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $game = $stmt->fetchObject();
        if ($game == false) {
            $game = array('id'=>null); 
        }
        $db = null;
        echo json_encode($game);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function getGameSubject($sub) {
    $sql = "SELECT * FROM game WHERE subject=:sub";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':sub', $sub);
        $stmt->execute();
        $games = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($games == false) {
            $games = array('id'=>null); 
        }
        $db = null;
        echo json_encode($games);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function searchGames($sub,$str) {
    $sql = "SELECT * FROM game WHERE subject=:sub AND title LIKE CONCAT('%',:str,'%')";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':sub', $sub);
        $stmt->bindParam(':str', $str);
        $stmt->execute();
        $games = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($games == false) {
            $games = array('id'=>null); 
        }
        $db = null;
        echo json_encode($games);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function createGame() {
    $app = \Slim\Slim::getInstance();
    $subject = $app->request()->post('subject');
    $title = $app->request()->post('title');
    $description = $app->request()->post('description');
    $difficulty = $app->request()->post('difficulty');
    $teacher_id = $app->request()->post('teacher_id');
    $sql = "INSERT INTO game (subject,title,description,difficulty,rating,teacher_id) VALUES (:subject,:title,:description,:difficulty,0,:teacher_id)";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':difficulty', $difficulty);
        $stmt->bindParam(':teacher_id', $teacher_id);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $id = $db->lastInsertId();
            echo getGame($id);
        } else {
            $output = array('id'=>null);
            echo json_encode($output);
        }
        $db = null;
        echo json_encode($output);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function updateGame() {
    $app = \Slim\Slim::getInstance();
    $id = $app->request()->post('id');
    $subject = $app->request()->post('subject');
    $title = $app->request()->post('title');
    $description = $app->request()->post('description');
    $difficulty = $app->request()->post('difficulty');
    $sql = "UPDATE game SET subject=:subject,title=:title,description:description,difficulty=:difficulty WHERE id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':difficulty', $difficulty);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            echo getGame($id);
        } else {
            $output = array('id'=>null);
            echo json_encode($output);
        }
        $db = null;
        echo json_encode($output);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function deleteGame() {
    $app = \Slim\Slim::getInstance();
    $id = $app->request()->post('id');
    $sql = "DELETE FROM game WHERE id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam('id', $id);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $output = array('id'=>1);
        } else {
            $output = array('id'=>0);
        }
        $db = null;
        echo json_encode($output);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function finishGame() {
    $app = \Slim\Slim::getInstance();
    $student_id = $app->request()->post('student_id');
    $game_id = $app->request()->post('game_id');
    $sql = "INSERT INTO student_game (student_id,game_id) VALUES (:student_id,:game_id)";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':game_id', $game_id);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $output = array('id'=>1);
        } else {
            $output = array('id'=>0);
        }
        $db = null;
        echo json_encode($output);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function upvoteGame() {
    $app = \Slim\Slim::getInstance();
    $id = $app->request()->post('id');
    $sql = "UPDATE game SET rating=rating+1 WHERE id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $output = array('id'=>1);
        } else {
            $output = array('id'=>0);
        }
        $db = null;
        echo json_encode($output);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function downvoteGame() {
    $app = \Slim\Slim::getInstance();
    $id = $app->request()->post('id');
    $sql = "UPDATE game SET rating=rating-1 WHERE id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $output = array('id'=>1);
        } else {
            $output = array('id'=>0);
        }
        $db = null;
        echo json_encode($output);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

?>