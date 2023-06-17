<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.php');
  require_once(__DIR__ . '/../database/user.class.php');
  require_once(__DIR__ . '/../database/ticket.class.php');
  require_once(__DIR__ . '/../database/comments.class.php');
  require_once(__DIR__ . '/../database/department.class.php');
  require_once(__DIR__ . '/../database/faq.class.php');


  require_once(__DIR__ . '/../templates/common.tpl.php');
  require_once(__DIR__ . '/../templates/tickets.tpl.php');
  require_once(__DIR__ . '/../templates/comments.tpl.php');


  require_once(__DIR__ . '/../database/user.class.php');
  

  $db = getDatabaseConnection();

  if(!$session->isLoggedIn()) {
    header('Location: authentication.php');
    die();
}

  $user = User::getCurrentUser($db, intval($session->getId()));

  $ticket_id = $_GET['ticket_id'];
  $ticket = Ticket::getSingleTicket($db,intval($ticket_id));
  $department=Department::getSingleDepartment($db,$ticket->department_id);
  $comments =Comment::getComments($db,intval($ticket_id));
  $department_agents= User::getAgentsFromDepartments($db, $department->department_id);
  $curr_agent = User::getCurrentUser($db, intval($ticket->agent_id));
  $faqs = FAQ::getFAQ($db); 
  drawHeader($db, array("tickets.css","faq.css","responsive.css"), $user, array("editticket.js","usefaq.js","edithashtags.js"));
  drawTicketPage( $ticket,$db,  $user);
  drawComments($db , $comments , $session , $department , $department_agents ,   $curr_agent , $ticket , $faqs);
  drawFooter();
?>