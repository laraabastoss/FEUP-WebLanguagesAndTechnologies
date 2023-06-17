<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.php');
  require_once(__DIR__ . '/../database/ticket.class.php');
  require_once(__DIR__ . '/../database/department.class.php');
  require_once(__DIR__ . '/../database/user.class.php');

  require_once(__DIR__ . '/../templates/common.tpl.php');
  require_once(__DIR__ . '/../templates/tickets.tpl.php');


  if (!$session->isLoggedIn()){
    die(header('Location: /pages/authentication.php'));
  }

  $db = getDatabaseConnection();

  $user = User::getCurrentUser($db, $session->getId());
  $popup = isset($_GET['popup'])?$_GET['popup']:false;
  $departments = Department::getDepartments($db);


 drawHeader($db, array("tickets.css"), $user, array("hashtags.js", "newticket.js"));
 drawNewTicket($popup,$departments);
  
 drawFooter();

  ?>