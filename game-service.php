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

function createGame() {
    $app = \Slim\Slim::getInstance();
    $subject = $app->request()->post('subject');
    $title = $app->request()->post('title');
    $description = $app->request()->post('description');
    $difficulty = $app->request()->post('difficulty');
    $rating = $app->request()->post('rating');
    $teacher_id = $app->request()->post('teacher_id');
    $sql = "INSERT INTO game (subject,title,description,difficulty,rating,teacher_id) VALUES (:subject,:title,:description,:difficulty,:rating,:teacher_id)";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':subject', subject);
        $stmt->bindParam(':title', title);
        $stmt->bindParam(':description', description);
        $stmt->bindParam(':difficulty', difficulty);
        $stmt->bindParam(':rating', rating);
        $stmt->bindParam(':teacher_id', teacher_id);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $output = array('status'=>true, 'message'=>"create success");
        } else {
            $output = array('status'=>false, 'message'=>"create fail");            
        }
        $db = null;
        echo json_encode($output);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function deleteGame($id) {
    $sql = "DELETE FROM game WHERE id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam('id', $id);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $output = array('status'=>true, 'message'=>"delete success");
        } else {
            $output = array('status'=>false, 'message'=>"delete fail");
        }
        $db = null;
        echo json_encode($output);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function updateGame($id) {
    $app = \Slim\Slim::getInstance();
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
            $output = array('status'=>"1", 'message'=>"update success");
        } else {
            $output = array('status'=>"0", 'message'=>"update fail");
        }
        $db = null;
        echo json_encode($output);
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

?>