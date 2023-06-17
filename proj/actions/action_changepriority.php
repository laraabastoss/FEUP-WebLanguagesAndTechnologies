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
  $priority = htmlentities($_POST['priority']);
  $ticket_id = htmlentities($_POST['ticket_id']);
  $old_ticket = Ticket::getSingleTicket($db, intval($ticket_id));

  $stmt = $db->prepare("SELECT agent_id FROM Tickets WHERE ticket_id = ?");
  $stmt->execute([$_POST['ticket_id']]);
  $agent_id = $stmt->fetch();

  if ($session->getID()==$agent_id['agent_id']){

    $stmt = $db->prepare('UPDATE Tickets SET priority = ? WHERE ticket_id = ?');
    $stmt->execute([ $priority, $ticket_id]);
  if ($old_ticket->priority!=$priority){
    $updated_at = (new DateTime())->format('d-m-Y');
    $stmt = $db->prepare('INSERT INTO Updates 
    (ticket_id, user_id, updated_at, status_before, status_after, 
    department_before, department_after, agent_before, agent_after, priority_before, priority_after,added_hashtag,removed_hashtag) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ? , ? , ? , ? , ?)');
    $stmt->execute([intval($ticket_id), $session->getId(), $updated_at, $old_ticket->status,
    $old_ticket->status, $old_ticket->department_id, $old_ticket->department_id,
    $old_ticket->department_id, $old_ticket->department_id, $old_ticket->priority, $priority,null,null]);
  }
    $session->addMessage('success', "Priority changed");
    header('Location: /../pages/ticket.php?ticket_id=' . $ticket_id);
    exit();
  }

  else{
    $session->addMessage('error', "Only the assigned agent can change the ticket's priority ");
    die(header('Location: ../pages/accessdenied.php'));

  }

 
  

  


