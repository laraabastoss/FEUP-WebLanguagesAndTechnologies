<?php

  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.php');
  require_once(__DIR__ . '/../database/user.class.php');
  require_once(__DIR__ . '/../database/ticket.class.php');

  if(!$session->isLoggedIn()) {
    $session->addMessage('error', "Unavailable action");
    die(header('Location: ../pages/accessdenied.php'));
  } 

  $db = getDatabaseConnection();
  $status;
  if (isset($_POST['close'])==true){
    $status = $_GET['status']==='in progress'?'resolved':'closed';
  }
  else{
    $status='in progress';
  }

  
  $ticket_id=$_GET['ticket_id'];
  $ticket = Ticket::getSingleTicket($db, intval($ticket_id));
  
  $stmt = $db->prepare('UPDATE Tickets SET status = ? WHERE ticket_id = ?');
  $stmt->execute([$status,$ticket_id]);
  $updated_at = (new DateTime())->format('d-m-Y');
  $stmt = $db->prepare('INSERT INTO Updates 
  (ticket_id, user_id, updated_at, status_before, status_after, 
  department_before, department_after, agent_before, agent_after,priority_before,priority_after,added_hashtag,removed_hashtag) 
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ? , ? , ? , ? ,?)');
  $stmt->execute([intval($ticket_id),  $session->getId(), $updated_at, $ticket->status,
  $status, $ticket->department_id, $ticket->department_id,
  $ticket->agent_id, $ticket->agent_id,$ticket->priority, $ticket->priority, null,null]);



$session->addMessage('success', "Status changed");
header('Location: /../pages/ticket.php?ticket_id=' . $ticket_id);
exit();

?>