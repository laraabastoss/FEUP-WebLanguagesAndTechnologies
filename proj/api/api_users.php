<?php 

declare(strict_types = 1);

require_once(__DIR__ . '/../database/connection.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/suggestedfaq.class.php');
require_once(__DIR__ . '/../database/faq.class.php');
$session = new Session();

$db = getDatabaseConnection();

/*if ($_SESSION['csrf'] !== $_GET['csrf'] ){
  $session->addMessage('error', "Unavailable action");
  header("Location: ../pages/accessdenied.php");
  die();
}*/


if ($_GET['action'] === 'getUserById') {
    $userId = $_GET['id'];
    $stmt = $db->prepare('SELECT * FROM Users WHERE user_id = ?');
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($user);
  }
  else if ($_GET['action'] === 'getUsersByUsername') {
    $isAdminSearch = isset($_GET['isAdminSearch']) ? $_GET['isAdminSearch'] === 'true' : true;

    if (!$isAdminSearch) {
        $stmt = $db->prepare('SELECT * FROM Users u WHERE username LIKE :username');
    } else {
        $stmt = $db->prepare('SELECT * FROM Users u WHERE u.role = "customer" AND username LIKE :username');
    }

    $stmt->bindValue(':username', '%' . $_GET['username'] . '%');
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
}
else if ($_GET['action'] === 'updateUserRole') {

    $username = $_POST['username'];
    $role = $_POST['role'];
  
    $stmt = $db->prepare('UPDATE Users SET role = :role WHERE username = :username');
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':username', $username);
  
    $stmt->execute();
}
else if ($_GET['action'] === 'assignUserToDepartment') {
  if (User::getCurrentUser($db, $session->getId())->role === "admin") {
    $username = $_GET['username'];
    $departmentIds = $_GET['departments'];
    $departmentIdsArray = explode(',', $departmentIds);

    $stmt = $db->prepare('SELECT user_id FROM Users WHERE username = ? GROUP BY 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    foreach ($departmentIdsArray as $departmentId) {
      // ver se já é agent daquele department
      $stmt = $db->prepare('SELECT COUNT(*) FROM Department_Agent WHERE department_id = ? AND agent_id = ?');
      $stmt->execute([$departmentId, $user['user_id']]);
      $count = $stmt->fetchColumn();

      if ($count === 0) {
        $stmt = $db->prepare('INSERT INTO Department_Agent (department_id, agent_id) VALUES (?, ?)');
        $stmt->execute([$departmentId, $user['user_id']]);
      }
    }
  }
}
else if ($_GET['action'] === 'addDepartment') {
  if (User::getCurrentUser($db, $session->getId())->role === "admin") {
    
    $departmentName = htmlentities($_GET['departmentName']);

    if (!empty($departmentName)) {

      $stmt = $db->prepare('SELECT COUNT(*) FROM Departments WHERE name = ?');
      $stmt->execute([$departmentName]);
      $count = $stmt->fetchColumn();

      if ($count > 0) {
        $response = array('addDepartmentStatus' => "Department already exists.");
        echo json_encode($response);
      } 
      else {
        $stmt = $db->prepare('INSERT INTO Departments (name) VALUES (?)');
        $stmt->execute([$departmentName]);

        $response = array('addDepartmentStatus' => "Department added sucessfully.");
        echo json_encode($response);
      }
    } 
    else {
      $response = array('addDepartmentStatus' => "Department name cannot be empty.");
      echo json_encode($response);
    }
  }
}
else if ($_GET['action'] === 'getAgentStats'){

  $sortBy = $_GET['sortBy'];
  $orderBy = 'ORDER BY 3 DESC, 2 COLLATE NOCASE ASC';

  if ($sortBy === 'username') {
    $orderBy = 'ORDER BY 2 COLLATE NOCASE ASC';
  } else if ($sortBy === 'closed') {
    $orderBy = 'ORDER BY 3 DESC, 2 COLLATE NOCASE ASC';
  } else if ($sortBy === 'ongoing') {
    $orderBy = 'ORDER BY 4 DESC, 2 COLLATE NOCASE ASC';
  }

  $stmt = $db->prepare("
  SELECT da.agent_id,
    u.username AS agent_username,
    IFNULL(c.num_closed_tickets, 0) AS num_closed_tickets,
    COUNT(CASE WHEN t.status != 'closed' THEN 1 END) AS num_ongoing_tickets
  FROM
    Department_Agent da
  JOIN
    Users u ON u.user_id = da.agent_id
  LEFT JOIN (
    SELECT
      agent_id,
      COUNT(*) AS num_closed_tickets
    FROM
      Tickets
    WHERE
      status = 'closed'
    GROUP BY
      agent_id
  ) c ON c.agent_id = da.agent_id
  LEFT JOIN
    Tickets t ON t.agent_id = da.agent_id
  GROUP BY
    da.agent_id, u.username
  $orderBy  
    ;
  ");
  $stmt->execute();
  $agentStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

  foreach ($agentStats as &$agent) {
    $profilePicture = '';
    $agentId = $agent['agent_id'];
    $fileExtensions = ['png', 'jpg', 'jpeg'];
  
    foreach ($fileExtensions as $extension) {
      $filePath = "../images/{$agentId}.{$extension}";
      if (file_exists($filePath)) {
        $profilePicture = $filePath;
        break;
      }
    }
  
    $agent['profile_picture'] = $profilePicture;
  }
  
  echo json_encode($agentStats);
}
else if ($_GET['action'] === 'updateFAQS'){
  $suggested_faq=$_GET['suggestedfaq'];
  $answer=htmlentities($_GET['answer']);

  $stmt = $db->prepare('
  SELECT *
  FROM Suggested_Frequently_Asked_Questions
  WHERE question_id = ?
');
$stmt->execute(array(intval($suggested_faq)));
$s_faq=$stmt -> fetch();
  $faq =new FAQ(
    0,
   $s_faq['title'],
  $answer
  ); 
  $faq->save($db);
}
else if($_GET['action'] === 'deleteFAQS'){
  $suggestedfaq_id=$_GET['suggestedfaq'];
  SuggestedFAQ::remove($db,$suggestedfaq_id);
}
  ?>