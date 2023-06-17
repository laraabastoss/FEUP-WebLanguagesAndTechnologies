<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.php');
  require_once(__DIR__ . '/../database/user.class.php');
 
  require_once(__DIR__ . '/../database/faq.class.php');

  require_once(__DIR__ . '/../templates/common.tpl.php');
  require_once(__DIR__ . '/../templates/faqs.tpl.php');

  

  $db = getDatabaseConnection();

  if(!$session->isLoggedIn()) {
    header('Location: authentication.php');
    die();
}

  $faqs = FAQ::getFAQ($db); 
  $user = User::getCurrentUser($db, $session->getId());

  drawHeader($db, array("style.css","faq.css", "responsive.css"), $user, array("faq.js",));
  drawFAQS($session, $faqs);
  drawFooter();
?>