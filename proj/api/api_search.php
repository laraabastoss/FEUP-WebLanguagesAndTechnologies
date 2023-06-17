<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.php');
  require_once(__DIR__ . '/../database/ticket.class.php');

  $db = getDatabaseConnection();
  $tickets = Ticket::searchTickets($db, $_GET['search'], intval($_GET['department']));
  $reversed_tickets = array_reverse($tickets);
  echo json_encode($reversed_tickets);

?>