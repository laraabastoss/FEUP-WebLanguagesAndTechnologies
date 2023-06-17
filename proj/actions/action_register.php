<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/connection.php');
require_once(__DIR__ . '/../database/user.class.php');

$db = getDatabaseConnection();

$correctedEmail = htmlentities(strip_tags($_POST['email']));
$correctedUsername = htmlentities(strip_tags($_POST['username']));

$password = $_POST['password'];

$userEmailExists = User::userEmailAlreadyExists($db, $correctedEmail);
$usernameExists = User::usernameAlreadyExists($db, $correctedUsername);

if($_POST['email'] == ""){
  InvalidInput($session, 'Please enter an email.');
}
else if($_POST['username'] == ""){
  InvalidInput($session, 'Please enter a username');
}
else if(!filter_var($correctedEmail, FILTER_VALIDATE_EMAIL)){
  InvalidInput($session, 'Email is invalid.');
}
else if (strlen($_POST['username']) < 3){
  InvalidInput($session, 'Username is too short.');
}
else if (strlen($_POST['username']) > 30){
  InvalidInput($session, 'Username is too long.');
}
else if ($correctedUsername !== "" && preg_match('/\s/', $correctedUsername)){
  InvalidInput($session, 'Username cannot contain white spaces.');
}
else if ($userEmailExists) {
  InvalidInput($session, "Email already registered!");
}
else if ($usernameExists){
  InvalidInput($session, 'Username already registered!');
}
else if ($_POST['password'] !== $_POST['password2']){ 
  InvalidInput($session, 'Passwords don\'t match!');
}
else if(strlen($password) < '6'){
  InvalidInput($session, 'Password too small.');
}
else if(!preg_match("#[a-z]+#",$password)){
  InvalidInput($session, 'Password must contain a lowercase letter.');
} 
else if(!preg_match("#[A-Z]+#",$password)){
  InvalidInput($session, 'Password must contain a capital letter.');
}
else if(!preg_match("#[0-9]+#", $password)){
  InvalidInput($session, 'Password must contain a number.');
} 
else {
$hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

$user = new User(
  0,
  $correctedUsername,
  $correctedEmail,
  'Tell us about yourself',
  $hashedPassword,
  'customer'
);
$user->save($db);

$session->addMessage('register-success', 'Registration successful!');
header('Location: ../pages/authentication.php');
die();
}
header('Location: ' . $_SERVER['HTTP_REFERER']);

?>

<?php function InvalidInput(Session $session, string $message){
    $session->addMessage('error-signup', $message);
    header('Location: ../pages/authentication.php?error=true');
    die();
} ?>