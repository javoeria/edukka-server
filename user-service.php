<?php

function getAllUsers() {
    $sql = 'SELECT * FROM user ORDER BY role, score DESC';
    try {
        $db = getDB();
        $stmt = $db->query($sql);
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($users === []) {
            $users = [['id'=>null]];
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
    $sql = "SELECT * FROM activity WHERE student_id = ? ORDER BY str_to_date(date,'%d-%m-%Y') DESC";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $student_id);
        $stmt->execute();
        $activity = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($activity === []) {
            $activity = [['student_id'=>null, 'game_id'=>null]]; 
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
    $username = $app->request()->post('username');
    $password = $app->request()->post('password');
    $role = $app->request()->post('role');
    $image = $app->request()->post('image');
    $class_id = $app->request()->post('class_id');
    $sql1 = 'SELECT count(*) FROM user WHERE username = ?';
    $sql2 = 'SELECT count(*) FROM class WHERE id = ?';
    try {
        $db = getDB();
        $stmt1 = $db->prepare($sql1);
        $stmt1->bindValue(1, $username);
        $stmt1->execute();
        $stmt2 = $db->prepare($sql2);
        $stmt2->bindValue(1, $class_id);
        $stmt2->execute();
        if ($stmt1->fetchColumn()>0 || $stmt2->fetchColumn() === '0') {
            $output = ['id'=>null];
            echo json_encode($output);
        } else {
            $sql = 'INSERT INTO user (name, username, password, role, image, score, class_id) VALUES (?, ?, ?, ?, ?, 0, ?)';
            $encrypt = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare($sql);
            $stmt->bindValue(1, $name);
            $stmt->bindValue(2, $username);
            $stmt->bindValue(3, $encrypt);
            $stmt->bindValue(4, $role);
            $stmt->bindValue(5, $image);
            $stmt->bindValue(6, $class_id);
            $stmt->execute();
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
    $name = $app->request()->post('name');
    $password = $app->request()->post('password');
    $image = $app->request()->post('image');
    $class_id = $app->request()->post('class_id');
    $id = $app->request()->post('id');
    $sql = 'SELECT count(*) FROM class WHERE id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $class_id);
        $stmt->execute();
        if ($stmt->fetchColumn() === '0') {
            $output = ['id'=>null];
            echo json_encode($output);
        } else {
            $sql1 = 'UPDATE user SET name = ?, image = ?, class_id = ? WHERE id = ?';
            $sql2 = 'UPDATE user SET name = ?, password = ?, image = ?, class_id = ? WHERE id = ?';
            if ($password === '') {
                $stmt1 = $db->prepare($sql1);
                $stmt1->bindValue(1, $name);
                $stmt1->bindValue(2, $image);
                $stmt1->bindValue(3, $class_id);
                $stmt1->bindValue(4, $id);
                $stmt1->execute();
            } else {
                $encrypt = password_hash($password, PASSWORD_DEFAULT);
                $stmt2 = $db->prepare($sql2);
                $stmt2->bindValue(1, $name);
                $stmt2->bindValue(2, $encrypt);
                $stmt2->bindValue(3, $image);
                $stmt2->bindValue(4, $class_id);
                $stmt2->bindValue(5, $id);
                $stmt2->execute();
            }
            echo getUser($id);
        }
        $db = null;
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