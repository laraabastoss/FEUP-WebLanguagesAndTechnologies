<?php
  declare(strict_types = 1);
  require_once(__DIR__ . '/../database/connection.php');
  require_once(__DIR__ . '/../templates/common.tpl.php');
  require_once(__DIR__.'/../utils/session.php');
  
  $db = getDatabaseConnection();
  
  $session = new Session();
  $user = User::getCurrentUser($db, $session->getId());
  $departments = Department::getDepartments($db);


  drawHeader($db, array("style.css", "ticket.css"), $user, array());
  drawAcessDenied($session);
  drawFooter(); 
?>