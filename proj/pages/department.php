<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.php');
  require_once(__DIR__ . '/../database/department.class.php');
  require_once(__DIR__ . '/../database/ticket.class.php');
  require_once(__DIR__ . '/../database/user.class.php');

  require_once(__DIR__ . '/../templates/common.tpl.php');
  require_once(__DIR__ . '/../templates/departments.tpl.php');
  require_once(__DIR__ . '/../templates/tickets.tpl.php');

  $db = getDatabaseConnection();

  if(!$session->isLoggedIn()) {
    header('Location: authentication.php');
    die();
  }

  $user = User::getCurrentUser($db, $session->getId());

  $department_id = $_GET['department_id'];
  $departments = Department::getDepartments($db);
  $tickets = Ticket::getTickets($db);
  $department_name=Department::getSingleDepartment($db,intval($department_id))->name;

  drawHeader($db,array("departments.css","tickets.css","responsive.css"), $user, array("search.js","departments.js"));  
  drawSearchBar();
  drawDepartments($departments);
  drawDepartmentTickets($tickets,$db,$department_id,$department_name);
  drawFooter();
?>