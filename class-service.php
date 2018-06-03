<?php

function getAllClasses() {
    $sql = 'SELECT * FROM class';
    try {
        $db = getDB();
        $stmt = $db->query($sql);
        $classes = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($classes === []) {
            $classes = [['id'=>null]];
        }
        $db = null;
        echo json_encode($classes);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function getClass($id) {
    $sql = 'SELECT * FROM class WHERE id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $class = $stmt->fetchObject();
        if ($class === false) {
            $class = ['id'=>null];
        }
        $db = null;
        echo json_encode($class);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function getUserClass($class_id) {
    $sql = 'SELECT * FROM user WHERE class_id = ? ORDER BY role, score DESC';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $class_id);
        $stmt->execute();
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

function getClassActivity($class_id) {
    $sql1 = 'SELECT * FROM user WHERE class_id = ?';
    try {
        $db = getDB();
        $stmt1 = $db->prepare($sql1);
        $stmt1->bindValue(1, $class_id);
        $stmt1->execute();
        $users = $stmt1->fetchAll(PDO::FETCH_OBJ);
        if ($users === []) {
            $activity = [['student_id'=>null, 'game_id'=>null]]; 
        } else {
            $json = json_encode($users);
            $array = json_decode($json, TRUE);
            $sql2 = 'SELECT * FROM activity WHERE student_id IN (' . implode(',', array_column($array,'id')) 
                    . ") ORDER BY str_to_date(date,'%d-%m-%Y') DESC";
            $stmt2 = $db->prepare($sql2);
            $stmt2->execute();
            $activity = $stmt2->fetchAll(PDO::FETCH_OBJ);
            if ($activity === []) {
                $activity = [['student_id'=>null, 'game_id'=>null]]; 
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
    $sql = 'INSERT INTO class (name, information, teacher_id) VALUES (?, ?, ?)';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $name);
        $stmt->bindValue(2, $information);
        $stmt->bindValue(3, $teacher_id);
        $stmt->execute();
        $id = $db->lastInsertId();
        $db = null;
        addTeacherClass($id, $teacher_id);
        echo getClass($id);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function updateClass() {
    $app = \Slim\Slim::getInstance();
    $name = $app->request()->post('name');
    $information = $app->request()->post('information');
    $id = $app->request()->post('id');
    $sql = 'UPDATE class SET name = ?, information = ? WHERE id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $name);
        $stmt->bindValue(2, $information);
        $stmt->bindValue(3, $id);
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
    setDefaultClass($id);
    $sql = 'DELETE FROM class WHERE id = ?';
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

function addUserClass() {
    $app = \Slim\Slim::getInstance();
    $id = $app->request()->post('id');
    $class_id = $app->request()->post('class_id');
    $sql1 = 'SELECT count(*) FROM class WHERE id = ?';
    try {
        $db = getDB();
        $stmt1 = $db->prepare($sql1);
        $stmt1->bindValue(1, $class_id);
        $stmt1->execute();
        if ($stmt1->fetchColumn() === '0') {
            $output = ['id'=>null];
            echo json_encode($output);
        } else {
            $sql2 = 'UPDATE user SET class_id = ? WHERE id = ?';
            $stmt2 = $db->prepare($sql2);
            $stmt2->bindValue(1, $class_id);
            $stmt2->bindValue(2, $id);
            $stmt2->execute();
            echo getClass($class_id);
        }
        $db = null;
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function removeUserClass() {
    $app = \Slim\Slim::getInstance();
    $id = $app->request()->post('id');
    $sql = 'UPDATE user SET class_id = 1 WHERE id = ?';
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

function addTeacherClass($class_id, $user_id) {
    $sql = 'UPDATE user SET class_id = ? WHERE id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $class_id);
        $stmt->bindValue(2, $user_id);
        $stmt->execute();
        $db = null;
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function setDefaultClass($class_id) {
    $sql = 'UPDATE user SET class_id = 1 WHERE class_id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $class_id);
        $stmt->execute();
        $db = null;
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

?>