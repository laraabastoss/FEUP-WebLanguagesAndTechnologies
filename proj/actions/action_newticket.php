<?php

  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.php');
  require_once(__DIR__ . '/../database/user.class.php');
  require_once(__DIR__ . '/../database/ticket.class.php');

  require_once(__DIR__ . '/../database/department.class.php');

  if(!$session->isLoggedIn()) {
    $session->addMessage('error', "Unavailable action");
    die(header('Location: ../pages/accessdenied.php'));
  } 

  $db = getDatabaseConnection();

  $department_name = isset($_POST['department']) && $_POST['department'] !== "" ? htmlentities($_POST['department']) : "Other";
  $department = Department::getDepartmentByName($db, $department_name);
  $current_date = (new DateTime())->format('d-m-Y');

  $ticket = new Ticket(
    0,
    $session->getId(),
    null,
    $department->department_id,
    htmlentities($_POST['title']),
    htmlentities($_POST['ticket']),
    "open",
    "low",
    $current_date,
    $current_date,
    json_decode($_POST['hashtags'])===null?[]:json_decode($_POST['hashtags'])
  );

  $ticket->save($db);  

  if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
    $file_name = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $file_extensions = ['jpg', 'jpeg', 'png','pdf', 'txt'];

    foreach($file_extensions as $old_extension){
      $old_file_path = "../files/$ticket->ticket_id.$old_extension";
      if (file_exists($old_file_path)) {
        unlink($old_file_path);
      }
    }

    if (in_array($file_ext, ['jpg', 'jpeg', 'png','pdf', 'txt'])) {
      move_uploaded_file($file_tmp, "../files/$ticket->ticket_id.$file_ext");
    } else {
      $session->addMessage('warning', 'Invalid file');
      header('Location: /../pages/new_ticket.php?');
    }}

  $session->addMessage('success', 'Ticket created with success');
  header('Location: /../pages/new_ticket.php?popup=true');
 
?>