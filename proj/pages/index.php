<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../templates/common.tpl.php');
  require_once(__DIR__ . '/../templates/departments.tpl.php');

  require_once(__DIR__ . '/../database/user.class.php');


  $db = getDatabaseConnection();

  $user = User::getCurrentUser($db, $session->getId());

  if(!$session->isLoggedIn()) {
    header('Location: authentication.php');
    die();
}

header('Location: homepage.php');
?>