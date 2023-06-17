<?php

  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.php');
  require_once(__DIR__ . '/../database/user.class.php');
  require_once(__DIR__ . '/../database/ticket.class.php');

  require_once(__DIR__ . '/../database/suggestedfaq.class.php');

  if(!$session->isLoggedIn()) {
    $session->addMessage('error', "Unavailable action");
    die(header('Location: ../pages/accessdenied.php'));
    } 


  if (!(isset($_POST['title'])) || $_POST['title']===""){
    InvalidInput($session,"No question submitted");
  }


  $db = getDatabaseConnection();
  $title= htmlentities($_POST['title']);
 

  $faq = new SuggestedFAQ(
    0,
    $title
  );

  $faq->save($db);

  $session->addMessage('success', "FAQ submitted.");
  header('Location: /../pages/faq.php');
  exit();
 
?>

<?php function InvalidInput(Session $session, string $message){
    $session->addMessage('error-submiting', $message);
    header('Location: ../pages/faq.php');
    die();
} ?>