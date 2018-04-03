<?php

function getAllUsers() {
    $sql = 'SELECT * FROM user';
    try {
        $db = getDB();
        $stmt = $db->query($sql);
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($users === []) {
            $users = ['id'=>null];
        }
        $db = null;
        echo json_encode($users);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function getUser($id) {
    $sql = 'SELECT * FROM user WHERE id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $user = $stmt->fetchObject();
        if ($user === false) {
            $user = ['id'=>null]; 
        }
        $db = null;
        echo json_encode($user);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function getUserActivity($student_id) {
    $sql = 'SELECT * FROM activity WHERE student_id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $student_id);
        $stmt->execute();
        $activity = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($activity === []) {
            $activity = ['student_id'=>null, 'game_id'=>null]; 
        }
        $db = null;
        echo json_encode($activity);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function logIn() {
    $app = \Slim\Slim::getInstance();
    $username = $app->request()->post('username');
    $password = $app->request()->post('password');
    $sql = 'SELECT * FROM user WHERE username = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $username);
        $stmt->execute();
        $user = $stmt->fetchObject();
	if ($user === false || !password_verify($password, $user->password)) {
            $user = ['id'=>null]; 
        }
        $db = null;
        echo json_encode($user);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function signUp() {
    $app = \Slim\Slim::getInstance();
    $name = $app->request()->post('name');
    $surname = $app->request()->post('surname');
    $username = $app->request()->post('username');
    $password = $app->request()->post('password');
    $encrypt = password_hash($password, PASSWORD_DEFAULT);
    $role = $app->request()->post('role');
    $class_id = $app->request()->post('class_id');
    $sql1 = 'SELECT count(*) FROM user WHERE username = ?';
    try {
        $db = getDB();
        $stmt1 = $db->prepare($sql1);
        $stmt1->bindValue(1, $username);
        $stmt1->execute();
        if ($stmt1->fetchColumn() > 0) {
            $output = ['id'=>null];
            echo json_encode($output);
        } else {
            $sql2 = 'INSERT INTO user (name, surname, username, password, role, score, class_id) VALUES (?, ?, ?, ?, ?, 0, ?)';
            $stmt2 = $db->prepare($sql2);
            $stmt2->bindValue(1, $name);
            $stmt2->bindValue(2, $surname);
            $stmt2->bindValue(3, $username);
            $stmt2->bindValue(4, $encrypt);
            $stmt2->bindValue(5, $role);
            $stmt2->bindValue(6, $class_id);
            $stmt2->execute();
            $id = $db->lastInsertId();
            echo getUser($id);
        }
        $db = null;
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function updateUser() {
    $app = \Slim\Slim::getInstance();
    $id = $app->request()->post('id');
    $name = $app->request()->post('name');
    $surname = $app->request()->post('surname');
    $password = $app->request()->post('password');
    $encrypt = password_hash($password, PASSWORD_DEFAULT);
    $sql = 'UPDATE user SET name = ?, surname = ?, password = ? WHERE id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $name);
        $stmt->bindValue(2, $surname);
        $stmt->bindValue(3, $encrypt);
        $stmt->bindValue(4, $id);
        $stmt->execute();
        $db = null;
        echo getUser($id);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function deleteUser() {
    $app = \Slim\Slim::getInstance();
    $id = $app->request()->post('id');
    $role = $app->request()->post('role');
    if ($role === 'teacher') {
        deleteTeacherClass($id);
        deleteTeacherGame($id);
    } else {
        deleteStudentActivity($id);
    }
    $sql = 'DELETE FROM user WHERE id = ?';
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

// Private Functions 

function deleteTeacherClass($teacher_id) {
    $sql = 'DELETE FROM class WHERE teacher_id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $teacher_id);
        $stmt->execute();
        $db = null;
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function deleteTeacherGame($teacher_id) {
    $sql = 'SELECT * FROM game WHERE teacher_id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql1);
        $stmt->bindValue(1, $teacher_id);
        $stmt->execute();
        $games = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($games !== []) {
            $json = json_encode($games);
            $array = json_decode($json, TRUE);
            foreach ($array as $item) {
                deleteGameBy($item['id']);
            }
        }
        $db = null;
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function deleteGameBy($id) {
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

function deleteStudentActivity($student_id) {
    $sql = 'DELETE FROM activity WHERE student_id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $student_id);
        $stmt->execute();
        $db = null;
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

?>