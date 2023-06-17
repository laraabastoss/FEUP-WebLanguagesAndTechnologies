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
  $department_id = htmlentities($_POST['department_id']);
  $ticket_id = htmlentities($_POST['ticket_id']);
  $old_ticket = Ticket::getSingleTicket($db, intval($ticket_id));
  $new_agent_id = intval($_POST['new_agent_id']);
  

  if (isset($_POST['new_agent_id']) && (User::userIsAgentOfDepartment($db, intval($session->getId()), intval($department_id))) && $old_ticket->agent_id !== $new_agent_id) {

    $stmt = $db->prepare('UPDATE Tickets SET agent_id = ? WHERE ticket_id = ?');
    $stmt->execute([$new_agent_id, $ticket_id]);

    $new_ticket = Ticket::getSingleTicket($db, intval($ticket_id));

    $updated_at = (new DateTime())->format('d-m-Y');
  
    if ($old_ticket->agent_id!=null){

      $stmt = $db->prepare('INSERT INTO Updates 
      (ticket_id, user_id, updated_at, status_before, status_after, 
      department_before, department_after, agent_before, agent_after,added_hashtag,removed_hashtag) 
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ? , ? , ?)');
      $stmt->execute([intval($ticket_id), $session->getId(), $updated_at, $old_ticket->status,
      $new_ticket->status, $old_ticket->department_id, $new_ticket->department_id,
      $old_ticket->agent_id, $new_ticket->agent_id,null,null]);
  
    }

    if ($old_ticket->status!='in progress'){

      $stmt = $db->prepare('UPDATE Tickets SET status = "in progress" WHERE ticket_id = ?');
      $stmt->execute([$ticket_id]);

      $stmt = $db->prepare('INSERT INTO Updates 
      (ticket_id, user_id, updated_at, status_before, status_after, 
      department_before, department_after, agent_before, agent_after,priority_before,priority_after,added_hashtag,removed_hashtag) 
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ? , ? , ? , ? , ?)');
      $stmt->execute([intval($ticket_id), $session->getId(), $updated_at, $old_ticket->status,
      'in progress', $old_ticket->department_id, $new_ticket->department_id,
      $old_ticket->agent_id, $new_ticket->agent_id, $old_ticket->priority, $new_ticket->priority,null,null]);

    }

    $session->addMessage('success', "Agent assined");
    header('Location: /../pages/ticket.php?ticket_id=' . $ticket_id);
    exit();
}

else{
  $session->addMessage('error', "Only agents of the department that are not current agents can assign an agent to a ticket");
  header('Location: /../pages/ticket.php?ticket_id=' . $ticket_id);
}



?>