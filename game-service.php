<?php

function getAllGames() {
    $sql = 'SELECT * FROM game';
    try {
        $db = getDB();
        $stmt = $db->query($sql);
        $games = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($games === []) {
            $games = ['id'=>null];
        }
        $db = null;
        echo json_encode($games);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function getGame($id) {
    $sql = 'SELECT * FROM game WHERE id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $game = $stmt->fetchObject();
        if ($game === false) {
            $game = ['id'=>null]; 
        }
        $db = null;
        echo json_encode($game);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function getSubjectGames($subject) {
    $sql = 'SELECT * FROM game WHERE subject = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $subject);
        $stmt->execute();
        $games = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($games === []) {
            $games = ['id'=>null]; 
        }
        $db = null;
        echo json_encode($games);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function searchGames($subject, $string) {
    $sql = 'SELECT * FROM game WHERE subject = ? AND title LIKE ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $subject);
        $stmt->bindValue(2, concat('%', $string, '%'));
        $stmt->execute();
        $games = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($games === []) {
            $games = ['id'=>null]; 
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
    $sql = 'INSERT INTO game (subject, title, description, difficulty, vote, teacher_id) VALUES (?, ?, ?, ?, 0, ?)';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $subject);
        $stmt->bindValue(2, $title);
        $stmt->bindValue(3, $description);
        $stmt->bindValue(4, $difficulty);
        $stmt->bindValue(5, $teacher_id);
        $stmt->execute();
        $id = $db->lastInsertId();
        $db = null;
        echo getGame($id);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function updateGame() {
    $app = \Slim\Slim::getInstance();
    $subject = $app->request()->post('subject');
    $title = $app->request()->post('title');
    $description = $app->request()->post('description');
    $difficulty = $app->request()->post('difficulty');
    $id = $app->request()->post('id');
    $sql = 'UPDATE game SET subject = ?, title = ?, description = ?, difficulty = ? WHERE id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $subject);
        $stmt->bindValue(2, $title);
        $stmt->bindValue(3, $description);
        $stmt->bindValue(4, $difficulty);
        $stmt->bindValue(5, $id);
        $stmt->execute();
        $db = null;
        echo getGame($id);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function deleteGame() {
    $app = \Slim\Slim::getInstance();
    $id = $app->request()->post('id');
    deleteGameActivity($id);
    deleteGameQuiz($id);
    $sql = 'DELETE FROM game WHERE id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $db = null;
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function finishGame() {
    $app = \Slim\Slim::getInstance();
    $student_id = $app->request()->post('student_id');
    $game_id = $app->request()->post('game_id');
    $result = $app->request()->post('result');
    $sql = 'INSERT INTO activity (student_id, game_id, result) VALUES (?, ?, ?)';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $student_id);
        $stmt->bindValue(2, $game_id);
        $stmt->bindValue(3, $result);
        $stmt->execute();
        $db = null;
        echo getActivity($student_id,$game_id);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function upvoteGame() {
    $app = \Slim\Slim::getInstance();
    $id = $app->request()->post('id');
    $sql = 'UPDATE game SET vote = vote+1 WHERE id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $db = null;
        echo getGame($id);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function downvoteGame() {
    $app = \Slim\Slim::getInstance();
    $id = $app->request()->post('id');
    $sql = 'UPDATE game SET vote = vote-1 WHERE id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $db = null;
        echo getGame($id);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

// Private Functions

function deleteGameActivity($game_id) {
    $sql = 'DELETE FROM activity WHERE game_id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $game_id);
        $stmt->execute();
        $db = null;
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function deleteGameQuiz($game_id) {
    $sql = 'DELETE FROM quiz WHERE game_id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $game_id);
        $stmt->execute();
        $db = null;
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function getActivity($student_id, $game_id) {
    $sql = 'SELECT * FROM activity WHERE student_id = ? AND game_id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $student_id);
        $stmt->bindValue(2, $game_id);
        $stmt->execute();
        $activity = $stmt->fetchObject();
        if ($activity === false) {
            $activity = ['student_id'=>null,'game_id'=>null]; 
        }
        $db = null;
        echo json_encode($activity);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

?>