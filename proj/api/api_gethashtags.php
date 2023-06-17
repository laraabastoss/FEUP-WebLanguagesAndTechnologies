<?php 
  declare(strict_types = 1); 
  require_once(__DIR__ . '/../database/connection.php');
  require_once(__DIR__ . '/../database/ticket.class.php');

  $db = getDatabaseConnection();
  $search_term = isset($_GET['term'])?$_GET['term']:"";

  $stmt = $db->prepare('SELECT * FROM ( SELECT DISTINCT json_each.value as hashtag FROM tickets, json_each(Tickets.hashtags) WHERE json_valid(Tickets.hashtags)) WHERE (hashtag  LIKE "%'. $search_term .'%") OR (hashtag==?) ');
  $stmt->execute([$search_term]);
  $hashtags = array();
  while ($hashtag = $stmt->fetch()) {
  $hashtags[] = $hashtag;
  } 
 
   echo json_encode($hashtags);
   exit;
?>
