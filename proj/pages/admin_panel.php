<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.php');
    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../database/ticket.class.php');
    require_once(__DIR__ . '/../database/suggestedfaq.class.php');

    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/admin_panel.tpl.php');

    $db = getDatabaseConnection();

    if(!$session->isLoggedIn()) {
        header('Location: authentication.php');
        die();
    }

    $user = User::getCurrentUser($db, $session->getId());
    $suggeestdefaqs=SuggestedFAQ::getSuggestedFAQ($db);

    if($user->role !== "admin") {
        $session->addMessage('error', "Page only available to admins");
        die(header('Location: /../pages/accessdenied.php'));
       
    }

    drawHeader($db, array("profile.css", "tickets.css", "profile_agent.css", "my_departments.css", "admin_panel.css","responsive.css"), $user, array("search_user.js","faq.js"));

    drawAdminPanel($db,$suggeestdefaqs);

    drawFooter();
?>