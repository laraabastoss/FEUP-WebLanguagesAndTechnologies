<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();
    


    require_once(__DIR__ . '/../database/connection.php');
    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../database/ticket.class.php');

    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/profile.tpl.php');

    $db = getDatabaseConnection();

    if(!$session->isLoggedIn()) {
        header('Location: authentication.php');
        die();
    }

    $user = User::getCurrentUser($db, $session->getId());

    $agent_departments = Department::getDepartmentsFromAgent($db, $user->user_id);

    if (empty($agent_departments)){
        drawHeader($db, array("profile.css", "tickets.css", "responsive.css"), $user, array());
    }
    else {
        drawHeader($db, array("profile.css", "tickets.css","profile_agent.css","responsive_agent.css"), $user, array());
    }

    $section = isset($_GET['section']) ? $_GET['section'] : 'tickets';

    switch ($section) {
        case 'tickets':
            $tickets = Ticket::getTicketsFromUser($db, $user->user_id);
            break;
        case 'inprogress':
            $tickets = Ticket::getTicketsInProgressFromUser($db, $user->user_id);
            break;
        case 'resolved':
            $tickets = Ticket::getTicketsResolvedFromUser($db, $user->user_id);
            break;
        case 'assigned':
            $tickets = Ticket::getTicketsFromAgent($db, $user->user_id);
            break;
        default:
            $tickets = Ticket::getTicketsFromUser($db, $user->user_id);
            break;
    }
    $tickets = array_reverse($tickets,true);
    drawCommonProfile($db, $user, $section);
    drawAllTickets($db, $tickets);
    drawFooter();
?>