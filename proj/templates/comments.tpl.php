<?php 
  declare(strict_types = 1);

  require_once(__DIR__ . '/../database/connection.php');
  require_once(__DIR__ . '/../database/comments.class.php');
  require_once(__DIR__ . '/../templates/faqs.tpl.php');

  $db = getDatabaseConnection();

?>

<?php function drawComments($db , $comments , $session , $department,$department_agents, $current_agent,$ticket , $faqs) { ?>
  <span id="page"> <a href= "tickethistory.php?ticket_id=<?php echo $ticket->ticket_id?>"> see ticket history </a></span>
  </div> 
     <span id="page_mobile"> <a href= "tickethistory.php?ticket_id=<?php echo $ticket->ticket_id?>"> see ticket history </a></span>
      <hr class="divider2"> </hr>
          
      <div class=line2>
      <div class=description> <?php echo $ticket->description ?></div>
      </div>  
      <?php 
        if($ticket->get_file_path()!==""){ ?>
         <a href= "<?php echo $ticket->get_file_path()?>" id="file">   ATTACHED FILE </a>
        <?php } ?>
  <hr class="ticketdivider" id="line_start"> </hr>
  <div class="comments">
    <div class="only_comments">
  <?php
    foreach($comments as $comment){?>
      <div class="comment">
          <div class="line"> 
            <img src="<?php echo (User::getCurrentUser($db,intval($comment->user_id)))->get_avatar_path()?>">
           <div class=author> <?php echo User::getCurrentUser($db,intval($comment->user_id))->username?></div>
           <div class=date> <?php echo $comment->created_at?></div>
    </div>
           <?php echo $comment->comment ?>
       </div>
  <?php } ?> </div>
  <div class="info">
    <div class="department"> <?php echo $department->name;
      if ($ticket->agent_id===null and User::userIsAgent($db,$session->getId()) and ($session->getId()!=$ticket->user_id)){ ?>
  <i i class="fa fa-pencil" aria-hidden="true" id="department_pencil"></i>  
  <div class="department_dropdown">
       <form method="post" action="../actions/action_changedepartment.php">
       <input type="hidden" name="department_id" value="<?php echo $department->department_id ?>">
       <input type="hidden" name="ticket_id" value="<?php echo $_GET['ticket_id']?>"> <?php
       $stmt = $db->prepare('SELECT * FROM Departments ');
       $stmt->execute([]);
       $departments=array();
       while ($currdepartment = $stmt->fetch()) {
         $departments[] = new Department(
           $currdepartment['department_id'], 
           $currdepartment['name']
            );
       } ?>
       <select name="new_department">
         <?php foreach ($departments as $currdepartment) { ?>
   
           <option value="<?php echo $currdepartment->department_id  ?>"><?php echo $currdepartment->name?></option>
         <?php } ?>
       </select>
   
       <button type="submit" class="edit_button">
             Change Department
       </button>
     </form>
     </div>
  <?php
    } ?>
     </div>

      <div class="agent"> <?php echo $ticket->agent_id==null? "no agent assigned":$current_agent->username;
      if ((($ticket->agent_id===null and User::userIsAgentOfDepartment($db,$session->getId(),$ticket->department_id)) || (($session->getId()==$ticket->agent_id))) and ($ticket->status!='closed')and ($session->getId()!=$ticket->user_id)){ ?>
      <i i class="fa fa-pencil" aria-hidden="true" ></i>  
      <div class="agent-dropdown">
      <form method="post" action="../actions/action_assignagent.php">
      <input type="hidden" name="department_id" value="<?php echo $department->department_id ?>">
      <input type="hidden" name="ticket_id" value="<?php echo $_GET['ticket_id']?>">
      <select name="new_agent_id">
        <?php foreach ($department_agents as $agent) { ?>
          <option value="<?php echo $agent['agent_id'] ?>"><?php echo $agent['agent_username'] ?></option>
        <?php } ?>
      </select>
      <button type="submit" class="edit_button">
            Assign Agent
      </button>
    </form>
    </div>
    <?php
    } ?>
        
      </div> 
      <div class="priority"> <?php echo $ticket->priority; ?> 
      <?php
      if ($session->getId()===$ticket->agent_id and $ticket->status!='closed'){ ?>
        <i i class="fa fa-pencil" aria-hidden="true" ></i>  
        <div class="priority-dropdown">
        <form method="post" action="../actions/action_changepriority.php">
        <input type="hidden" name="user_id" value="<?php echo $session->getId() ?>">
        <input type="hidden" name="ticket_id" value="<?php echo $_GET['ticket_id']?>">
        <select name="priority">
          <?php foreach (['low','medium','high'] as $priority) {
              ?>
            <option value="<?php echo $priority ?>"><?php echo $priority ?></option>
          <?php } ?>
        </select>
        <button type="submit" class="edit_button">
              Change Priority
        </button>
      </form>
      </div>
      <?php
      } ?>

      </div>

      <?php
    if (($session->getId()===$ticket->user_id || $session->getId()===$ticket->agent_id ) and  $ticket->status!=='closed'){
      ?>
    <div class=display_hashtags> 
    <ul id="hashtagList">  <?php
    $i=0;
        foreach($ticket->hashtags as $hashtag){
          if (trim($hashtag, '"\]\[')!=""){
            $trimedhashtag=trim($hashtag, '"\]\[');
            ?>  <li class="one_hashtag">  <?php echo trim($hashtag, '"\]\[')  ?>  <button class="remove-hashtag" data-hashtag="<?php echo trim($hashtag, '"\]\['); ?>" data-ticket-id="<?php echo $ticket->ticket_id; ?>" data-curr-user="<?php echo $session->getId(); ?>">x</button> </li>  <?php
          }
          $i=$i+1;
         }
         ?> 
        <li>
          
        <input type="text" placeholder="add some hashtags" id="hashtagsinput" list="hashtagList" class="hashtags-input" name="hashtag" data-ticket-id="<?php echo $ticket->ticket_id; ?>" data-curr-user="<?php echo $session->getId(); ?>">
         <datalist id="hashtagList"></datalist>
     
         </li>

         <?php
           ?> 
       </ul>
        </div>
       <?php 
    }
    else{ ?>
    <div class=display_hashtags> 
    <ul>  <?php
        foreach($ticket->hashtags as $hashtag){
          if (trim($hashtag, '"\]\[')!=""){
            ?>  <li class="one_hashtag">  <?php echo trim($hashtag, '"\]\[')  ?> </li>  <?php
          }
         }
           ?> 
       </ul>
        </div>
       <?php  }
 ?>
  </div> 

  <?php if($session->getId()===$ticket->user_id || $session->getId()===$ticket->agent_id) { ?>
  <div class="new_comment"> 
    <form action="../actions/action_addcomment.php?ticket_id=<?php echo $_GET['ticket_id']; ?>" method="post" id="addcommentform">  
    <textarea name="newComment" placeholder="Comment" id="newComment"></textarea>
    <button formmethod="post" type="submit" class="commentbutton">
              COMMENT
    </button>
    <?php
    if ($session->getId()===$ticket->agent_id){
      ?>
      <button class="usefaqbutton" >
            USE A FAQ TO ANSWER
      </button>

   <?php } ?> 
  </form> 
    <div id="dropdown" style="display: none;">
    <?php
    drawFAQStouse($faqs);
    ?>
      </div>
  </div>
  <?php } ?> 
  </div>  
  <?php } ?>


  <?php function drawInfo($bd, $department, $agent, $ticket) { ?>
    <div class="grid"> 
    <div class="info">
    <div class="department"> <?php echo $department->name?>  </div>
    <div class="agent">   <?php echo $agent->username ?> </div>  
    <div class=display_hashtags> 
    <ul>  <?php
        foreach($ticket->hashtags as $hashtag){
          if (trim($hashtag, '"\]\[')!=""){
            ?>  <li class="one_hashtag">  <?php echo trim($hashtag, '"\]\[')  ?> </li>  <?php
          }
         }
           ?> 
       </ul>
  </div>    </div>    </div>  </div>
<?php } ?>