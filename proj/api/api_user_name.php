<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.php');
  require_once(__DIR__ . '/../database/user.class.php');

  $db = getDatabaseConnection();
  $stmt = $db->prepare('SELECT * FROM Users u WHERE u.user_id=?');
  $stmt->execute([$_GET['user']]);
  $user=$stmt->fetch();
  echo json_encode($user);
?>