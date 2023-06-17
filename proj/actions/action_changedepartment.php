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
  $department_id = htmlentities($_POST['new_department']);
  $ticket_id = htmlentities($_POST['ticket_id']);
  $ticket = Ticket::getSingleTicket($db, intval($ticket_id));

  if (isset($department_id) && User::userIsAgent($db,$session->getId()) && $ticket->agent_id===null){
    $stmt = $db->prepare('UPDATE Tickets SET department_id = ? WHERE ticket_id = ?');
    $stmt->execute([$department_id, $ticket_id]);

    $updated_at = (new DateTime())->format('d-m-Y');

    if ($ticket->department_id !=$department_id){
      $stmt = $db->prepare('INSERT INTO Updates 
      (ticket_id, user_id, updated_at, status_before, status_after, 
      department_before, department_after, agent_before, agent_after,priority_before,priority_after,added_hashtag,removed_hashtag) 
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ? , ? ,  ? , ? , ?)');
      $stmt->execute([intval($ticket_id), $session->getId(), $updated_at, $ticket->status,
      $ticket->status, $ticket->department_id, $department_id,
      $ticket->agent_id, $ticket->agent_id, $ticket->priority, $ticket->priority,null,null]);
    }

    $session->addMessage('success', "Department changed");
    header('Location: /../pages/ticket.php?ticket_id=' . $ticket_id);
    exit();

  }


  else{
    $session->addMessage('error', "Only agent can change the department of a ticket");
    die(header('Location: ../pages/accessdenied.php'));

  }
  



 

  
