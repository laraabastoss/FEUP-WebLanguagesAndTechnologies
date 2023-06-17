<?php 
  declare(strict_types = 1); 

  require_once(__DIR__ . '/../database/ticket.class.php');
  require_once(__DIR__ . '/../database/department.class.php');
  require_once(__DIR__ . '/../database/user.class.php');

  $db = getDatabaseConnection();

?>

<?php
function drawSingleTicket(Ticket $ticket, $db)
{ 
  $ticket_id = $ticket->ticket_id;
  ?>
  <a href="../pages/ticket.php?ticket_id=<?php echo $ticket_id; ?>">
    <section class="ticket">
      <h3><?php echo $ticket->title ?></h3>
      <span class="description"><?php echo $ticket->description ?></span>
      <?php
      $stmt = $db->prepare('SELECT * FROM Users u WHERE u.user_id=?');
      $stmt->execute([$ticket->user_id]);
      $user = $stmt->fetch();
      ?>
      <span class="author"><?php echo $user['username'] ?></span>
      <span class="date"><?php echo $ticket->created_at ?></span>
    </section>
  </a>
<?php } ?>



<?php
function drawNewTicket($popup, $departments)
{
?>
  <h1>New ticket</h1>

  <form action="../actions/action_newticket.php" method="post" enctype="multipart/form-data">
    <section class="new_ticket_header">
      <input type="text" name="title" class="ticket_title" placeholder="Title" required="required" abled>

      <select name="department">
        <option value="" selected hidden>Department</option>
        <?php foreach ($departments as $department) : ?>
          <option><?php echo $department->name; ?></option>
        <?php endforeach; ?>
      </select>
    </section>

    <section class="hashtags">
      <section class="tag-box">
        <ul>
          <input type="text" placeholder="Hashtags" id="hashtags" list="hashtagList" class="hashtags-input">
          <datalist id="hashtagList"></datalist>
          <input type="hidden" placeholder="Hashtags" id="hashtags-store" name="hashtags">
        </ul>
      </section>
    </section>

    <section class="new_ticket_description">
      <textarea name="ticket" placeholder="Ticket Description" class="new_ticket"></textarea>
    </section>

    <label for="file">ADD FILE:</label>
    <input type="file" id="file" name="file" accept="file/*">

    <button formmethod="post" type="submit" name="popup" value="true" class="button_new_ticket">
      CREATE
    </button>

    <div class="popup" id="popup" data-popup="<?php echo $popup; ?>">
      <p>Ticket created with success!</p>
      <a href="/../pages/new_ticket.php"><button type="button" onclick="closePopUp()">OK</button></a>
    </div>


  </form>



  <?php

}
?>


<?php function drawTicketsFromDepartments($db, $agent_departments) {
    foreach($agent_departments as $single_department){
        $tickets = Ticket::getTicketsFromDepartment($db, $single_department->department_id);
        foreach($tickets as $ticket){
            drawSingleTicket($ticket, $db);
        }
    }
} ?>


<?php function drawDepartmentTickets(array $tickets, $db, $department_id, $department_name) { ?>
  <section class="department_name">
    <h1> <?php echo $department_name ?></h1>
    <hr class="divider"> </hr> </section>
    <section class=tickets> <?php foreach ($tickets as $ticket){?>
        <?php if($ticket->department_id==$department_id){?>
      <?php drawSingleTicket($ticket,$db) ?> <?php } }?>
    </section>
<?php
}
?>

<?php 
function drawTicketPage(Ticket $ticket, $db, $curruser)
{
?>
<div class="ticketpage">
  <section class="headerline">
    <h2>
      <form action="../actions/action_changestatus.php?status=<?php echo $ticket->status; ?>&ticket_id=<?php echo $ticket->ticket_id; ?>&agent_id=<?php echo $curruser->user_id; ?>" method="get">
        <?php echo $ticket->title;
        if ($ticket->agent_id == $curruser->user_id && $ticket->status == 'in progress') { ?>
          <button formmethod="post" type="submit" name="close" value="true">
            Mark as resolved
          </button>
        <?php } else if ($ticket->user_id == $curruser->user_id && $ticket->status == 'resolved') { ?>
          <button formmethod="post" type="submit" name="open" value="false">
            Open ticket again
          </button>
          <button formmethod="post" type="submit" name="close" value="true">
            Close ticket
          </button>
        <?php }
        ?>
      </form>
    </h2>
  </section>

  <div class="line">
    <section class="ticketinformation">
      <span class="status-<?php echo strtolower($ticket->status); ?>"><?php echo $ticket->status ?></span>
      <?php
      $stmt = $db->prepare('SELECT * FROM Users u WHERE u.user_id=?');
      $stmt->execute([$ticket->user_id]);
      $user = $stmt->fetch();
      ?>
      <img src="<?php echo (User::getCurrentUser($db, intval($user['user_id'])))->get_avatar_path() ?>">
      <span class="author">
        <?php echo $user['username'] ?>
      </span>
      <span class="creationdate">
        <?php echo $ticket->created_at ?>
      </span>
    </section>
    <?php
    }
    ?>




<?php
function drawTicketHistory(Ticket $ticket,$db){ ?>
  <span id="page"> <a href= "ticket.php?ticket_id=<?php echo $ticket->ticket_id?>"> go back to comments </a></span>
     </div> 
     <span id="page_mobile"> <a href= "ticket.php?ticket_id=<?php echo $ticket->ticket_id?>"> go back to comments </a></span>
</section>
      <hr class="divider2"> </hr>
      <?php 
        if($ticket->get_file_path()!==""){ ?>
         <a href= "<?php echo $ticket->get_file_path()?>" id="file">   ATTACHED FILE </a>
        <?php } ?>
  
<?php
  $stmt = $db->prepare('SELECT * FROM Updates u WHERE u.ticket_id=? ORDER BY u.update_id DESC');
  $stmt->execute([$ticket->ticket_id]);
  $updates = array();
  $updates = $stmt->fetchAll(PDO::FETCH_OBJ);

  if (count($updates) == 0){ 
    echo "ticket has no updates";
  }

  $curr=1;

  foreach ($updates as $update){
    drawTicketUpdate($update,$db,$curr===count($updates));
    $curr++;
  }


}?>



<?php
function drawTicketUpdate($update, $db, $islast){?>
  <section class="update">
    <?php
    $stmt = $db->prepare('SELECT username FROM Users u WHERE u.user_id=?');
    $stmt->execute([$update->user_id]);
    $user = $stmt->fetch();
    ?>
    <div class=line2>
      <span class=date> <?php echo $update->updated_at ?></span>
    </div>

    <?php if ($update->status_before != $update->status_after) {
      if ($update->status_before == 'open' && $update->status_after == 'in progress') {

        $stmt = $db->prepare('SELECT * FROM Users u WHERE u.user_id=?');
        $stmt->execute([$update->agent_after]);
        $agent = $stmt->fetch();
      ?>
        <div class='line3'>
          <?php
          echo "ticket was assigned to ";
          ?> <span class='agent'> <?php echo $agent['username'] ?> </span> <?php ;
                                                                          echo  " and status was changed from ";
          ?> <span class="status-<?php echo strtolower($update->status_before) ?>"> <?php echo $update->status_before ?> </span> <?php ;
                                                                                                                                    echo " to ";
          ?> <span class="status-<?php echo strtolower($update->status_after) ?>"><?php echo $update->status_after  ?> </span> <?php ;
                                                                                                                                  ?> </div> <?php
                    }
                  else { ?>
        <div class='line3'>
          <span class='agent'> <?php echo $user['username'] ?> </span> <?php ;
                                                                        echo " changed status from ";
          ?> <span class="status-<?php echo strtolower($update->status_before) ?>"> <?php echo $update->status_before ?> </span> <?php ;
                                                                                                                                  echo " to ";
          ?> <span class="status-<?php echo strtolower($update->status_after) ?>"><?php echo $update->status_after ?> </span> <?php ;
                                                                                                                              ?> </div> <?php

                                                                                                                            };
                                                                                                                          }
                                                                                                                          else if ($update->department_before != $update->department_after) {
                                                                                                                            $stmt = $db->prepare('SELECT * FROM Departments d WHERE d.department_id=?');
                                                                                                                            $stmt->execute([$update->department_before]);
                                                                                                                            $department_before = $stmt->fetch();
                                                                                                                            $stmt = $db->prepare('SELECT * FROM Departments d WHERE d.department_id=?');
                                                                                                                            $stmt->execute([$update->department_after]);
                                                                                                                            $department_after = $stmt->fetch();  ?>
        <div class='line3'>
          <span class='agent'> <?php echo $user['username'] ?> </span> <?php ;
                                                                        echo " changed ticket from "; ?>
          <span class='department'> <?php echo '#' . $department_before['name'] ?> </span> <?php ;
                                                                                              echo " to "; ?>
          <span class='department'> <?php echo '#' . $department_after['name'] ?> </span> <?php
                                                                                              ?> </div> <?php
                                                                                                      }
                                                                                                      else if ($update->agent_before != $update->agent_after) {
                                                                                                        $stmt = $db->prepare('SELECT * FROM Users u WHERE u.user_id=?');
                                                                                                        $stmt->execute([$update->agent_before]);
                                                                                                        $agent_before = $stmt->fetch();
                                                                                                        $stmt = $db->prepare('SELECT * FROM Users u WHERE u.user_id=?');
                                                                                                        $stmt->execute([$update->agent_after]);
                                                                                                        $agent_after = $stmt->fetch(); ?>
        <div class='line3'>
          <?php

          echo "ticket was transfered from " ?>
          <span class='agent'> <?php echo  $agent_before['username'] ?> </span> <?php ;
                                                                                  echo " to ";  ?>
          <span class='agent'> <?php echo  $agent_after['username'] ?> </span> <?php ;
                                                                                  ?> </div> <?php

                                                                                }
                                                                                else if ($update->added_hashtag != null) {
                                                                                  $stmt = $db->prepare('SELECT * FROM Users u WHERE u.user_id=?');
                                                                                  $stmt->execute([$update->user_id]);
                                                                                  $user = $stmt->fetch(); ?>
        <div class='line3'>
          <span class='agent'> <?php echo $user['username'] ?> </span>
          <?php echo " added " ?>
          <span class='hashtag'> <?php echo  $update->added_hashtag ?> </span> <?php ;
                                                                                  echo " as an hashtag";  ?> </div> <?php

                                                                                                                }
                                                                                                                else if ($update->removed_hashtag != null) {
                                                                                                                  $stmt = $db->prepare('SELECT * FROM Users u WHERE u.user_id=?');
                                                                                                                  $stmt->execute([$update->user_id]);
                                                                                                                  $user = $stmt->fetch(); ?>
        <div class='line3'>
          <span class='agent'> <?php echo $user['username'] ?> </span>
          <?php echo " removed " ?>
          <span class='hashtag'> <?php echo  $update->removed_hashtag ?> </span> <?php ;
                                                                                    echo " hashtag";  ?> </div> <?php

                                                                                                                  }
                                                                                                                  else if ($update->priority_before != $update->priority_after) { ?>
        <div class='line3'>
          <span class='agent'> <?php echo $user['username'] ?> </span> <?php ;
                                                                        echo " changed ticket's priority from "; ?>
          <span class='priority'> <?php echo $update->priority_before; ?> </span> <?php ;
                                                                                      echo " to "; ?>
          <span class='priority'> <?php echo $update->priority_after; ?> </span> <?php
                                                                                      ?> </div> <?php
                                                                                            };


                                                                                            if (!$islast) { ?>
      <hr class="divider2"> </hr>
  </section>
<?php
  }
}
?>

<?php
function drawLatestTickets($tickets, $db)
{
?>
<section class="frequently_asked">
  <h1><i class="fa fa-commenting" aria-hidden="true"></i>Recent Questions</h1>
  <hr class="divider">
  </hr>
  <?php
  $tickets = array_reverse($tickets);
  ?>
  <section class="latestquestions">
    <?php for ($i = 0; $i < 10; $i++) {
      if (count($tickets) > $i) {
        drawSingleTicket($tickets[$i], $db);
      }
    }
    ?>
  </section>
</section>
<?php
}
?>
