<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.php');
  require_once(__DIR__ . '/../database/user.class.php');
  require_once(__DIR__ . '/../database/ticket.class.php');

  require_once(__DIR__ . '/../templates/common.tpl.php');
  require_once(__DIR__ . '/../templates/tickets.tpl.php');

  require_once(__DIR__ . '/../database/user.class.php');
  

  $db = getDatabaseConnection();

  if(!$session->isLoggedIn()) {
    header('Location: authentication.php');
    die();
}

  $user = User::getCurrentUser($db, $session->getId());

  $ticket_id = $_GET['ticket_id'];
  $ticket = Ticket::getSingleTicket($db,intval($ticket_id));

  drawHeader($db,array("tickets.css","responsive.css"), $user, array());
  drawTicketPage( $ticket,$db,  $user);
  drawTicketHistory($ticket,$db);
  drawFooter();
?>