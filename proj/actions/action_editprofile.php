<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.php');
    require_once(__DIR__ . '/../database/user.class.php');

    if(!$session->isLoggedIn()) {
      $session->addMessage('error', "Unavailable action");
      die(header('Location: ../pages/accessdenied.php'));
    } 

    $db = getDatabaseConnection();

    $correctedEmail = strip_tags($_POST['email']);
    $correctedUsername = strip_tags($_POST['username']);
    $correctedBio = strip_tags($_POST['bio']);
    $password = $_POST['password'];

    $usernameExists = User::usernameAlreadyExists($db, htmlentities($correctedUsername));
    $emailExists = User::userEmailAlreadyExists($db, htmlentities($correctedEmail));

    $curr_user = User::getCurrentUser($db, $session->getId());
    if ($usernameExists) {
      InvalidUpdateInput($session, 'Username already in use!');
    }
    else if ($emailExists){
      InvalidUpdateInput($session, 'Email already in use!');
    }
    else if ($correctedEmail !== "" && !filter_var($correctedEmail, FILTER_VALIDATE_EMAIL)){
      InvalidUpdateInput($session, 'Email is invalid!');
    }
    else if ($correctedUsername !== "" && strlen($correctedUsername) < 3){
      InvalidUpdateInput($session, 'Username is too short.');
    }
    else if ($correctedUsername !== "" && strlen($correctedUsername) > 30){
      InvalidUpdateInput($session, 'Username is too long.');
    }
    else if ($correctedUsername !== "" && preg_match('/\s/', $correctedUsername)){
      InvalidUpdateInput($session, 'Username cannot contain white spaces.');
    }
    else if($password !== "" &&  strlen($password) < '6'){
      InvalidUpdateInput($session, 'Password too small.');
    }
    else if($password !== "" &&  !preg_match("#[a-z]+#",$password)){
      InvalidUpdateInput($session, 'Password must contain a lowercase letter.');
    } 
    else if($password !== "" &&  !preg_match("#[A-Z]+#",$password)){
      InvalidUpdateInput($session, 'Password must contain a capital letter.');
    }
    else if($password !== "" &&  !preg_match("#[0-9]+#", $password)){
      InvalidUpdateInput($session, 'Password must contain a number.');
    }
    else if ($_POST['password'] !== $_POST['confirm-password']){ 
      InvalidUpdateInput($session, 'Passwords don\'t match!');
    } 
    else if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == UPLOAD_ERR_OK) {
      $file_name = $_FILES['avatar']['name'];
      $file_tmp = $_FILES['avatar']['tmp_name'];
      $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

      $file_extensions = ['jpg', 'jpeg', 'png'];

      foreach($file_extensions as $old_extension){
        $old_file_path = "../images/$curr_user->user_id.$old_extension";
        if (file_exists($old_file_path)) {
          unlink($old_file_path);
        }
      }
      if (in_array($file_ext, ['jpg', 'jpeg', 'png'])) {
        move_uploaded_file($file_tmp, "../images/$curr_user->user_id.$file_ext");
      } 
      else {
        InvalidUpdateInput($session, 'Invalid image extension.');
      }
    }
    else {
      $session->addMessage('profile-edit-success', 'Changed profile successful!');
    }
    $curr_user->update($db, htmlentities($correctedUsername), htmlentities($correctedEmail), htmlentities($correctedBio), $password);
    
    $session->addMessage('success', "Profile edited");
    header('Location: ../pages/profile.php');
?>

<?php function InvalidUpdateInput(Session $session, string $message){
    $session->addMessage('error-profile-update', $message);
    header('Location: ../pages/edit_profile.php');
    die();
} ?>