<?php 

declare(strict_types = 1);

require_once(__DIR__ . '/../database/connection.php');
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

$db = getDatabaseConnection();

$tickets = Ticket::getAllTickets($db);

$agent_id = intval($session->getId());

$action = $_GET['action'];

$statuses = (isset($_GET['status']) && ($_GET['status'] !== "")) ? explode(',', $_GET['status']) : array();
$priorities = (isset($_GET['priority'])  && ($_GET['priority'] !== "")) ? explode(',', $_GET['priority']) : array();
$departments = (isset($_GET['departments'])  && ($_GET['departments'] !== "")) ? explode(',', $_GET['departments']) : array();
$assigned = (isset($_GET['assigned'])  && ($_GET['assigned'] !== "")) ? explode(',', $_GET['assigned']) : array();

$tickets_filtered = array();

switch ($action) {
    case 'getTicketsFromAgentDepartments':
        $tickets_filtered = getTickets($db, $statuses, $priorities, $departments, $assigned, $agent_id);
        echo json_encode($tickets_filtered);
        break;
}

function getTickets(PDO $db, array $statuses, array $priorities, array $departments, array $assigned, int $agent_id) : array {

    $statuses = array_map(function ($status) {
        return strtolower($status) === "in-progress" ? "in progress" : strtolower($status);
    }, $statuses);

    $placeholder_status = implode(',', array_fill(0,count($statuses), '?'));
    $placeholder_priorities = implode(',', array_fill(0,count($priorities), '?'));
    $placeholder_departments = implode(',', array_fill(0,count($departments), '?'));

    if (count($assigned) === 0 || count($assigned) === 2){
        $isAssignedCondition = "";
    }

    if (count($statuses) === 0){
        $placeholder_status = "SELECT DISTINCT t.status FROM Tickets t";
    }
    if (count($priorities) === 0){
        $placeholder_priorities = "SELECT DISTINCT t.priority FROM Tickets t";
    }
    if (count($departments) === 0){
        $placeholder_departments = "SELECT DISTINCT da.department_id FROM Department_Agent da WHERE agent_id = $agent_id";
    }
    if (count($assigned) === 1){
        if ($assigned[0] === "assigned") {
            $isAssignedCondition = "AND t.agent_id IS NOT NULL";
        } else if ($assigned[0] === "not-assigned") {
            $isAssignedCondition = "AND t.agent_id IS NULL";
        }
    }

    $stmt = $db->prepare("
        SELECT t.ticket_id, t.user_id, u.username,
         t.agent_id, d.name, t.department_id, t.title, t.description,
          t.status, t.priority, t.created_at, t.updated_at, t.hashtags
        FROM Tickets t
        INNER JOIN Departments d ON t.department_id = d.department_id
        INNER JOIN Users u ON t.user_id = u.user_id
        WHERE TRUE
        AND t.status IN ($placeholder_status)
        AND t.priority IN ($placeholder_priorities)
        AND t.department_id IN ($placeholder_departments)
        $isAssignedCondition
        GROUP BY 1
    ");
    $stmt->execute(array_merge($statuses, $priorities, $departments));

    $tickets_array = array();


    while ($ticket = $stmt->fetch()) {
        $hashtags_array=explode(',', $ticket['hashtags']);
        $new_ticket = new Ticket(
          $ticket['ticket_id'], 
          $ticket['user_id'],
          $ticket['agent_id'],
          $ticket['department_id'],
          $ticket['title'],
          $ticket['description'],
          $ticket['status'],
          $ticket['priority'],
          $ticket['created_at'],
          $ticket['updated_at'],
          $hashtags_array
        );


        $tickets_array[] = array(
            'ticket' => $new_ticket,
            'department_name' => $ticket['name'],
            'user_name' => $ticket['username']
        );
    }

    return $tickets_array;
}
?>

