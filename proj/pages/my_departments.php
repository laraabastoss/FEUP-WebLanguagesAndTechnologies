<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.php');
    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../database/ticket.class.php');

    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/my_departments.tpl.php');

    $db = getDatabaseConnection();

    if(!$session->isLoggedIn()) {
        header('Location: authentication.php');
        die();
    }

    $user = User::getCurrentUser($db, $session->getId());

    if (!(User::userIsAgent($db,$user->user_id))){
        $session->addMessage('error', "Page only available to agents");
        die(header('Location: /../pages/accessdenied.php'));
    }

    $agent_departments = Department::getDepartmentsFromAgent($db, $user->user_id);

   /* if(empty($agent_departments)) {
        header('Location: profile.php');
        die();
    }*/

    drawHeader($db, array("profile.css", "tickets.css", "profile_agent.css", "my_departments.css","responsive.css"), $user, array('sort.js'));

    drawMyDepartments($db, $agent_departments);

    drawFooter();
?>