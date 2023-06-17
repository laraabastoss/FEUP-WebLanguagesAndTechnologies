<?php 
  declare(strict_types = 1); 


  require_once(__DIR__ . '/../database/connection.php');
  require_once(__DIR__ . '/../templates/tickets.tpl.php');
?>


<?php
function drawMyDepartments($db, $agent_departments)
{
?>
<section class="department_page">
    <div class="department_sort">
        <section class="sort-by">
            <h3>Sort by:</h3>
            <select id="sort-select" value="date-asc">
                <option value="date-asc">Date (oldest first)</option>
                <option value="date-desc">Date (newest first)</option>
            </select>
        </section>

        <section class="sort-status">
            <h3>Status:</h3>
            <label><input type="checkbox" class="status-filter" value="open"> Open</label>
            <label><input type="checkbox" class="status-filter" value="in-progress"> In Progress</label>
            <label><input type="checkbox" class="status-filter" value="closed"> Closed</label>
        </section>

        <section class="sort-priority">
            <h3>Priority:</h3>
            <label><input type="checkbox" class="priority-filter" value="low"> Low</label>
            <label><input type="checkbox" class="priority-filter" value="medium"> Medium</label>
            <label><input type="checkbox" class="priority-filter" value="high"> High</label>
        </section>

        <section class="sort-assigned">
            <h3>Assignment:</h3>
            <label><input type="checkbox" class="assigned-filter" value="assigned"> Assigned</label>
            <label><input type="checkbox" class="assigned-filter" value="not-assigned"> Not Assigned</label>
        </section>

        <section class="sort-departments">
            <h3>Departments:</h3>
            <?php
            foreach ($agent_departments as $single_department) {
            ?>
                <label><input type="checkbox" value="<?php echo $single_department->department_id ?>" class="department-filter"> <?php echo $single_department->name ?></label>
            <?php
            }
            ?>
        </section>
    </div>
    <div id="department_tickets">
    </div>
</section>
<?php
}
?>
