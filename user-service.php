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
	if (password_verify($password, $user->password)) {
            echo json_encode($user); 
        }
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
        $stmt = $db->prepare($sql1);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            $output = array('status'=>false, 'message'=>"signup fail");
        } else {
            $sql2 = "INSERT INTO user (name,surname,username,password,score) VALUES (:name,:surname,:username,:password,0)";
            $stmt = $db->prepare($sql2);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':surname', $surname);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $encrypt);
            $stmt->execute();
            if ($stmt->rowCount() == 1) {
                $output = array('status'=>true, 'message'=>"signup success");
                echo login();
            } else {
                $output = array('status'=>false, 'message'=>"signup fail");
            }
        }
        $db = null;
        
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function updateUser($id) {
    $app = \Slim\Slim::getInstance();
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

function deleteUser($id) {
    $sql = "DELETE FROM user WHERE id=:id";
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

?>