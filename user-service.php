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

function getUserActivity($student_id) {
    $sql = "SELECT * FROM activity WHERE student_id=:student_id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        $activity = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($activity == false) {
            $activity = array('student_id'=>null); 
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
            $sql2 = "INSERT INTO user (name,surname,username,password,role,score,class_id) VALUES (:name,:surname,:username,:password,:role,0,:class_id)";
            $stmt2 = $db->prepare($sql2);
            $stmt2->bindParam(':name', $name);
            $stmt2->bindParam(':surname', $surname);
            $stmt2->bindParam(':username', $username);
            $stmt2->bindParam(':password', $encrypt);
            $stmt2->bindParam(':role', $role);
            $stmt2->bindParam(':class_id', $class_id);
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
    $class_id = $app->request()->post('class_id');
    $sql = "UPDATE user SET name=:name,surname=:surname,password=:password,class_id=:class_id WHERE id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':surname', $surname);
        $stmt->bindParam(':password', $encrypt);
        $stmt->bindParam(':class_id', $class_id);
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
    if ($role == 'teacher') {
        deleteTeacherClass($id);
        deleteTeacherGame($id);
    } else {
        deleteStudentActivity($id);
    }
    $sql = "DELETE FROM user WHERE id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam('id', $id);
        $stmt->execute();
        $db = null;
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function deleteStudentActivity($student_id) {
    $sql = "DELETE FROM activity WHERE student_id=:student_id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam('student_id', $student_id);
        $stmt->execute();
        $db = null;
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function deleteTeacherGame($teacher_id) {
    $sql = "SELECT * FROM game WHERE teacher_id=:teacher_id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql1);
        $stmt->bindParam('teacher_id', $teacher_id);
        $stmt->execute();
        $games = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($games == true) {
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

?>