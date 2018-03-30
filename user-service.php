<?php

function getAllUsers() {
    $sql = "SELECT * FROM user";
    try {
        $db = getDB();
        $stmt = $db->query($sql);
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($users == false) {
            $users = array('id'=>null);
        }
        $db = null;
        echo json_encode($users);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function getUser($id) {
    $sql = "SELECT * FROM user WHERE id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $user = $stmt->fetchObject();
        if ($user == false) {
            $user = array('id'=>null); 
        }
        $db = null;
        echo json_encode($user);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function getHistory($id) {
    $sql = "SELECT * FROM student_game WHERE student_id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $history = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($history == false) {
            $history = array('student_id'=>null); 
        }
        $db = null;
        echo json_encode($history);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function logIn() {
    $app = \Slim\Slim::getInstance();
    $username = $app->request()->post('username');
    $password = $app->request()->post('password');
    $sql = "SELECT * FROM user WHERE username=:username";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetchObject();
	if ($user == false || !password_verify($password, $user->password)) {
            $user = array('id'=>null); 
        }
        echo json_encode($user); 
        $db = null;
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
    $sql1 = "SELECT count(*) FROM user WHERE username=:username";
    try {
        $db = getDB();
        $stmt1 = $db->prepare($sql1);
        $stmt1->bindParam(':username', $username);
        $stmt1->execute();
        if ($stmt1->fetchColumn() > 0) {
            $output = array('id'=>null);
            echo json_encode($output);
        } else {
            $sql2 = "INSERT INTO user (name,surname,username,password,score) VALUES (:name,:surname,:username,:password,0)";
            $stmt2 = $db->prepare($sql2);
            $stmt2->bindParam(':name', $name);
            $stmt2->bindParam(':surname', $surname);
            $stmt2->bindParam(':username', $username);
            $stmt2->bindParam(':password', $encrypt);
            $stmt2->execute();
            if ($stmt2->rowCount() == 1) {
                $id = $db->lastInsertId();
                echo getUser($id);
            } else {
                $output = array('id'=>null);
                echo json_encode($output);
            }
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
    $sql = "UPDATE user SET name=:name,surname=:surname,password=:password WHERE id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':surname', $surname);
        $stmt->bindParam(':password', $encrypt);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            echo getUser($id);
        } else {
            $output = array('id'=>null);
            echo json_encode($output);
        }
        $db = null;
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function deleteUser() {
    $app = \Slim\Slim::getInstance();
    $id = $app->request()->post('id');
    $sql = "DELETE FROM user WHERE id=:id";
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

function createStudent() {
    $app = \Slim\Slim::getInstance();
    $user_id = $app->request()->post('user_id');
    $sql = "INSERT INTO student (user_id) VALUES (:user_id)";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
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

function createTeacher() {
    $app = \Slim\Slim::getInstance();
    $user_id = $app->request()->post('user_id');
    $sql = "INSERT INTO teacher (user_id) VALUES (:user_id)";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
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