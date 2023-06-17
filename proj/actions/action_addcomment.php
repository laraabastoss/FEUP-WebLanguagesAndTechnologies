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

  $stmt = $db->prepare("SELECT user_id ,agent_id FROM Tickets WHERE ticket_id = ?");
  $stmt->execute([$_GET['ticket_id']]);
  $ids = $stmt->fetch();
  
  if ($session->getId()==$ids['agent_id'] || $session->getId()==$ids['user_id'] ){
    $comment_text=htmlentities($_POST['newComment']);
    $date = date('d F Y');

  
    $comment = new Comment(
      0,
      $session->getId(),
      intval(htmlentities($_GET['ticket_id'])),
      $comment_text,
      $date
    );
  
    $comment->save($db);
    $session->addMessage('success', "Comment Added");
    header('Location: ../pages/ticket.php?ticket_id='.$_GET['ticket_id']);
    exit();

  }

  else{

    $session->addMessage('error', "Only the writer and the assigned agent of the ticket can comment");
    die(header('Location: ../pages/accessdenied.php'));

  }
 

?>