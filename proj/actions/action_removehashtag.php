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
$ticket_id = $_GET['ticket_id'];
$hashtag=$_GET['hashtag'];
$old_ticket = Ticket::getSingleTicket($db, intval($ticket_id));

$stmt = $db->prepare("SELECT hashtags FROM Tickets WHERE ticket_id = ?");
$stmt->execute([$ticket_id]);
$existing_hashtags = json_decode($stmt->fetchColumn());

$finalArray = array();
foreach ($existing_hashtags as $currhashtag) {
  if ( $currhashtag!=$hashtag){
        $finalArray[]=$currhashtag;
      } 
}

$stmt = $db->prepare("SELECT user_id ,agent_id FROM Tickets WHERE ticket_id = ?");
$stmt->execute([$_GET['ticket_id']]);
$ids = $stmt->fetch();

if ($session->getId()==$ids['agent_id'] || $session->getId()==$ids['user_id'] ){

  $stmt = $db->prepare("UPDATE Tickets SET hashtags = ? WHERE ticket_id = ?");
  $stmt->execute([json_encode($finalArray), $ticket_id]);

  $updated_at = (new DateTime())->format('d-m-Y');
  $stmt = $db->prepare('INSERT INTO Updates 
  (ticket_id, user_id, updated_at, status_before, status_after, 
  department_before, department_after, agent_before, agent_after,priority_before, priority_after,added_hashtag,removed_hashtag) 
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ? , ? , ? , ? , ?)');
  $stmt->execute([intval($ticket_id), $session->getId(), $updated_at, $old_ticket->status,
  $old_ticket->status, $old_ticket->department_id, $old_ticket->department_id,
  $old_ticket->department_id, $old_ticket->department_id, $old_ticket->priority, $old_ticket->priority,null,$hashtag]);
  
  $session->addMessage('success', "Hashtag removed");
  header('Location: ../pages/ticket.php?ticket_id='.$ticket_id);
  exit();

}

else{

  $session->addMessage('error', "Unavailable action");
  header('Location: ../pages/accessdenied.php');
  
}

?>