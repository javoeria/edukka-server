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

function getUsersClass($id) {
    $sql = "SELECT * FROM user WHERE class_id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $users = $stmt->fetchObject();
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

function deleteClass($id) {
    $sql = "DELETE FROM class WHERE id=:id";
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