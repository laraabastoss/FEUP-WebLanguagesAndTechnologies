<?php 

declare(strict_types = 1);

require_once(__DIR__ . '/../database/connection.php');
require_once(__DIR__ . '/../database/user.class.php');

$db = getDatabaseConnection();

if ($_GET['action'] === 'getDepartmentById') {
    $departmentID = $_GET['id'];
    $stmt = $db->prepare('SELECT d.name FROM Departments d WHERE department_id = ?');
    $stmt->execute([$departmentID]);
    $departmentName = $stmt->fetchColumn();
    echo json_encode($departmentName);
  }
else if ($_GET['action'] === 'getAllDepartments'){
  $stmt = $db->prepare('SELECT * FROM Departments d');
  $stmt->execute();
  $departments = $stmt->fetchAll();
  echo json_encode($departments);
}

?>