<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.php');
  require_once(__DIR__ . '/../database/department.class.php');


  $db = getDatabaseConnection();
  $departmentid=$_GET['department'];
  $department=Department::getSingleDepartment($db,intval($departmentid));
  echo json_encode($department->name);
?>