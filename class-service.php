<?php

function getAllClasses() {
    $sql = "SELECT * FROM class";
    try {
        $db = getDB();
        $stmt = $db->query($sql);
        $classes = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($classes == false) {
            $classes = array('id'=>null);
        }
        $db = null;
        echo json_encode($classes);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function getClass($id) {
    $sql = "SELECT * FROM class WHERE id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $class = $stmt->fetchObject();
        if ($class == false) {
            $class = array('id'=>null);
        }
        $db = null;
        echo json_encode($class);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function getUserClass($id) {
    $sql = "SELECT * FROM user WHERE class_id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
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

function createClass() {
    $app = \Slim\Slim::getInstance();
    $name = $app->request()->post('name');
    $school = $app->request()->post('school');
    $teacher_id = $app->request()->post('teacher_id');
    $sql = "INSERT INTO class (name,school,teacher_id) VALUES (:name,:school,:teacher_id)";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':school', $school);
        $stmt->bindParam(':teacher_id', $teacher_id);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $output = array('status'=>true, 'message'=>"class create success");
        } else {
            $output = array('status'=>false, 'message'=>"class create fail");            
        }
        $db = null;
        echo json_encode($output);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function updateClass() {
    $app = \Slim\Slim::getInstance();
    $id = $app->request()->post('id');
    $name = $app->request()->post('name');
    $school = $app->request()->post('school');
    $sql = "UPDATE class SET name=:name,school=:school WHERE id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':school', $school);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $output = array('status'=>true, 'message'=>"class update success");
        } else {
            $output = array('status'=>false, 'message'=>"class update fail");
        }
        $db = null;
        echo json_encode($output);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function deleteClass() {
    $app = \Slim\Slim::getInstance();
    $id = $app->request()->post('id');
    $sql = "DELETE FROM class WHERE id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam('id', $id);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $output = array('status'=>true, 'message'=>"class delete success");
        } else {
            $output = array('status'=>false, 'message'=>"class delete fail");
        }
        $db = null;
        echo json_encode($output);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function addUserClass() {
    $app = \Slim\Slim::getInstance();
    $class_id = $app->request()->post('class');
    $user_id = $app->request()->post('user'); 
    $sql = "UPDATE user SET class_id=:class WHERE id=:user";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':class', $class_id);
        $stmt->bindParam(':user', $user_id);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $output = array('status'=>"1", 'message'=>"add user success");
        } else {
            $output = array('status'=>"0", 'message'=>"add user fail");
        }
        $db = null;
        echo json_encode($output);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function removeUserClass() {
    $app = \Slim\Slim::getInstance();
    $user_id = $app->request()->post('user'); 
    $sql = "UPDATE user SET class_id=:class WHERE id=:user";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':class', null);
        $stmt->bindParam(':user', $user_id);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $output = array('status'=>"1", 'message'=>"remove user success");
        } else {
            $output = array('status'=>"0", 'message'=>"remove user fail");
        }
        $db = null;
        echo json_encode($output);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

?>