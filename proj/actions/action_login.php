<?php

  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.php');
  require_once(__DIR__ . '/../database/user.class.php');

  $db = getDatabaseConnection();

  if($session->isLoggedIn()){
    header('Location: index.php');
    die();
  }

  if (!User::userEmailAlreadyExists($db, htmlentities($_POST['email']))){
    $session->addMessage('error-login', 'Email does not exist!');
    header('Location: ../pages/authentication.php');
    die();
  }

  $user = User::getUserWithPassword($db, $_POST['email'], $_POST['password']);

  //echo($user->password);
  //echo(intval(password_verify($_POST['login_password'], $user->password)));

  if ($user) {
    $session->setId($user->user_id);
    $session->setName($user->username);
    $session->addMessage('success', 'Logged in!');
    header('Location: ../pages/homepage.php');
    die();
  } 
  
  else {
    $session->addMessage('error-login', 'Wrong password!');
  }

  header('Location: ../pages/authentication.php');
?>