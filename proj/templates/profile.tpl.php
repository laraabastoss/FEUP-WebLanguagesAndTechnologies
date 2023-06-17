<?php 
  declare(strict_types = 1);

  require_once(__DIR__ . '/../database/user.class.php');
  require_once(__DIR__ . '/tickets.tpl.php');
?>

<?php
function drawCommonProfile($db, $user,  $section)
{
?>
<ul class="profile-menu">
  <li class="<?php echo ($section === 'tickets') ? 'selected' : ''; ?>" id="profile-menu-tickets"><a href="profile.php?section=tickets">Tickets</a></li>
  <li class="<?php echo ($section === 'inprogress') ? 'selected' : ''; ?>" id="profile-menu-progress"><a href="profile.php?section=inprogress">In Progress</a></li>
  <li class="<?php echo ($section === 'resolved') ? 'selected' : ''; ?>" id="profile-menu-resolved"><a href="profile.php?section=resolved">Resolved</a></li>
  <?php if (!empty(Department::getDepartmentsFromAgent($db, $user->user_id))) { ?>
    <li class="<?php echo ($section === 'assigned') ? 'selected' : ''; ?>" id="profile-menu-assigned"><a href="profile.php?section=assigned">Assigned</a></li>
  <?php } ?>
</ul>


<div class="wrapper">
    <div class="profile">
        <section class="profile_container"> 
            <img src="<?php echo $user->get_avatar_path()?>">
        </section>
        <p class="username"><?php echo $user->username ?></p>
        <p class="bio"><?php echo $user->bio ?></p>
        <a href="../pages/edit_profile.php"><button class="edit-profile">Edit</button></a>
    </div>
<?php
} 
?>


<?php
function drawAllTickets($db, $tickets)
{ ?>
    <section class="profile_tickets">
    <?php foreach ($tickets as $ticket){ ?>
        <?php drawSingleTicket($ticket,$db) ?>
    <?php } ?>
    </section>
</div>
<?php
}
?>
