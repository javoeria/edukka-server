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

function getUserClass($class_id) {
    $sql = "SELECT * FROM user WHERE class_id=:class_id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':class_id', $class_id);
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

function getClassActivity($class_id) {
    $sql1 = "SELECT * FROM user WHERE class_id=:class_id";
    try {
        $db = getDB();
        $stmt1 = $db->prepare($sql1);
        $stmt1->bindParam(':class_id', $class_id);
        $stmt1->execute();
        $users = $stmt1->fetchAll(PDO::FETCH_OBJ);
        if ($users == false) {
            $activity = array('id'=>null); 
        } else {
            $json = json_encode($users);
            $array = json_decode($json, TRUE);
            $sql2 = "SELECT * FROM activity WHERE student_id IN (".implode(',',array_column($array, 'id')).")";
            $stmt2 = $db->prepare($sql2);
            $stmt2->execute();
            $activity = $stmt2->fetchAll(PDO::FETCH_OBJ);
            if ($activity == false ) {
                $activity = array('student_id'=>null); 
            }
        }
        $db = null;
        echo json_encode($activity);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function createClass() {
    $app = \Slim\Slim::getInstance();
    $name = $app->request()->post('name');
    $information = $app->request()->post('information');
    $teacher_id = $app->request()->post('teacher_id');
    $sql = "INSERT INTO class (name,information,teacher_id) VALUES (:name,:information,:teacher_id)";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':information', $information);
        $stmt->bindParam(':teacher_id', $teacher_id);
        $stmt->execute();
        $id = $db->lastInsertId();
        $db = null;
        addUserClass($id, $teacher_id);
        echo getClass($id);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function updateClass() {
    $app = \Slim\Slim::getInstance();
    $id = $app->request()->post('id');
    $name = $app->request()->post('name');
    $information = $app->request()->post('information');
    $sql = "UPDATE class SET name=:name,information=:information WHERE id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':information', $information);
        $stmt->execute();   
        $db = null;
        echo getClass($id);
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
        $db = null;
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function deleteTeacherClass($teacher_id) {
    $sql = "DELETE FROM class WHERE teacher_id=:teacher_id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam('teacher_id', $teacher_id);
        $stmt->execute();
        $db = null;
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function addUserClass($class,$user) {
    $sql = "UPDATE user SET class_id=:class WHERE id=:user";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':class', $class);
        $stmt->bindParam(':user', $user);
        $stmt->execute();
        $db = null;
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function removeUserClass($user) {
    $sql = "UPDATE user SET class_id=:class WHERE id=:user";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':class', null);
        $stmt->bindParam(':user', $user);
        $stmt->execute();
        $db = null;
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

?>