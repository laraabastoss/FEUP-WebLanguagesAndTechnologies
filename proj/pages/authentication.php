<?php
  declare(strict_types = 1);

  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.php');

  require_once(__DIR__ . '/../templates/common.tpl.php');
  require_once(__DIR__ . '/../templates/authentication.tpl.php');

  $db = getDatabaseConnection();

  //$customer = Customer::getCustomer($db, $session->getId());

  if($session->isLoggedIn()){
    header('Location: index.php');
    die();
  }

  $user = null;

  drawHeader($db, array("authentication.css","responsive.css"), $user, array("authentication.js"));

  //if(logge in )
  drawLogIn($session);
  //else
  drawSignUp($session);
  ?>
  <?php
  drawFooter();
?>