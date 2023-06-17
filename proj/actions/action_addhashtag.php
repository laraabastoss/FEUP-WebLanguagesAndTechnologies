<?php

  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.php');
  require_once(__DIR__ . '/../database/user.class.php');
  require_once(__DIR__ . '/../database/ticket.class.php');
  require_once(__DIR__ . '/../database/comments.class.php');
  require_once(__DIR__ . '/../database/department.class.php');

  if(!$session->isLoggedIn()) {
    $session->addMessage('error', "Unavailable action");
    die(header('Location: ../pages/accessdenied.php'));
  } 

  $db = getDatabaseConnection();
  $hashtag=htmlentities($_POST['hashtag']);

  $stmt = $db->prepare("SELECT user_id ,agent_id FROM Tickets WHERE ticket_id = ?");
  $stmt->execute([$_POST['ticket_id']]);
  $ids = $stmt->fetch();
  
  if ($session->getId()==$ids['agent_id'] || $session->getId()==$ids['user_id'] ){

    $old_ticket = Ticket::getSingleTicket($db, intval($_POST['ticket_id']));
    $stmt = $db->prepare("SELECT hashtags FROM Tickets WHERE ticket_id = ?");
    $stmt->execute([$_POST['ticket_id']]);
    $existing_hashtags = json_decode($stmt->fetchColumn(), true);
  
    if ($existing_hashtags!=null){
      if (!in_array($hashtag, $existing_hashtags)) {
      $existing_hashtags[] = $hashtag;
    
      }
    }
    else{
      $existing_hashtags = [$hashtag];
    }

  $stmt = $db->prepare("UPDATE Tickets SET hashtags = ? WHERE ticket_id = ?");
  $stmt->execute([json_encode($existing_hashtags), $_POST['ticket_id']]);
  
  
  $updated_at = (new DateTime())->format('d-m-Y');

  $stmt = $db->prepare('INSERT INTO Updates 
  (ticket_id, user_id, updated_at, status_before, status_after, 
  department_before, department_after, agent_before, agent_after,priority_before,priority_after,added_hashtag,removed_hashtag) 
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ? , ? , ? , ? , ?)');
  $stmt->execute([intval($_POST['ticket_id']), $session->getId(), $updated_at, $old_ticket->status,
  $old_ticket->status, $old_ticket->department_id, $old_ticket->department_id,
  $old_ticket->department_id, $old_ticket->department_id, $old_ticket->priority, $old_ticket->priority, $_POST['hashtag'] , null]);
    
  $session->addMessage('success', "Hashtag Added");
  header('Location: ../pages/ticket.php?ticket_id='.$_POST['ticket_id']);
  exit();

  }

  else{

    $session->addMessage('error', "Only the writer and the agent  of the ticket can add hashtags");
    die(header('Location: ../pages/accessdenied.php'));

  }

?>