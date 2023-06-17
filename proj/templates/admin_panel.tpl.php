<?php 
  declare(strict_types = 1); 


  require_once(__DIR__ . '/../database/connection.php');
  require_once(__DIR__ . '/../templates/tickets.tpl.php');
?>


<?php

function drawAdminPanel($db, $suggestedfaqs)
{
?>
<section class="adminPanelContainer">
  <h1>Admin Panel</h1>
   <input type="hidden" id="csrf" name="csrf" value="<?=$_SESSION['csrf']?>">
  <h2 class="section-title">Add New Department</h2>
  <section id="add-department">
    <label for="department-name"></label>
    <input type="text" id="department-name" name="department-name" placeholder="Department Name">
    <button type="submit" id="add-department-button">Add</button>
  </section>
  <h2 class="section-title">Upgrade Client to Admin</h2>
  <section id="upgrade-client">
    <form>
      <label for="search-user-admin"></label>
      <input type="text" id="search-user-admin" type="text" placeholder="search user" name="search-user-admin">
    </form>
    <ul id="user-list-admin"></ul>
    <p id="selected-users-text-admin">Selected Users:</p>
    <button type="submit" id="upgrade-users-to-admin-button">Set as Admin</button>
  </section>

  <h2 class="section-title">Assign Agents to Departments</h2>
  <section id="assign-agent">
    <form>
      <label for="search-user-agent"></label>
      <input type="text" id="search-user-agent" type="text" placeholder="search user" name="search-user-agent">
    </form>
    <ul id="user-list-agent"></ul>
    <p id="selected-users-text-agent">Selected Users:</p>
    <ul id="department-list"></ul>
    <p id="selected-departments-text">Selected Departments:</p>
    <button type="submit" id="upgrade-users-to-agent-button">Set as Agent</button>
  </section>

  <h2 class="section-title" id="agent-stats-title">Check Agent Stats</h2>
  <section id="agent-stats">
    <table>
      <thead>
        <tr>
          <th data-sort-by="username">Agent
          <span class="sort-icon"> ▼ </span>
          </th>
          <th data-sort-by="closed">Closed Tickets
          <span class="sort-icon"> ▼ </span>
          </th>
          <th data-sort-by="ongoing">Ongoing Tickets
          <span class="sort-icon"> ▼ </span>
          </th>
        </tr>
      </thead>
      <tbody id="agent-stats-body">
      </tbody>
    </table>
  </section>



    <h2 class= "section-title" id="upgrade-faqs">Upgrade to FAQ</h2>
    <section id="suggested-faqs">
      <ul>
      <?php foreach ($suggestedfaqs as $suggestedfaq ) {?>
        <li>
          <span id="title<?php echo $suggestedfaq->question_id?>" class="title"> <?php echo $suggestedfaq->title ?></span>
          <section class="description<?php echo $suggestedfaq->question_id?>" >
            <textarea placeholder="Answer this FAQ"></textarea>
            <button class="delete-suggested-faqs" data-question-id="<?php echo $suggestedfaq->question_id ?>"> DELETE </button>
            <button class="submit-suggested-faqs" data-question-id="<?php echo $suggestedfaq->question_id ?>"> SUBMIT </button>
        </section>
        </li>
     <?php }
      ?>
      </ul>
  </section>

</section>
<?php
}
?>
