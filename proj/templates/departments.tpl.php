<?php 
  declare(strict_types = 1); 


  require_once(__DIR__ . '/../database/connection.php');
  require_once(__DIR__ . '/../database/department.class.php');

  $db = getDatabaseConnection();

  $departments = Department::getDepartments($db);
?>



<?php
function drawDepartments(array $departments)
{
?>
<section class="carousel" >
  <h1><i class="fa fa-tags" aria-hidden="true"></i>Departments</h1>
  <hr class="divider">
  <section id="departments">
  <ul data-slides>
  <div class="buttons_mobile">
  <button class="carousel button prev"><</button>  
  <button class="carousel button next_mobile">></button>
  </div>
      <?php
      $i = 0;
      foreach ($departments as $department) {
        if ($i < 5) {
      ?>
          <li class="department current-department">
            <a href="../pages/department.php?department_id=<?php echo $department->department_id; ?>" id="dep" data-custom-id=<?php echo $department->department_id ?>>
              <?php echo $department->name; ?>
            </a>
          </li>
      <?php
          $i++;
        } else {
      ?>
          <li class="department">
            <a href="../pages/department.php?department_id=<?php echo $department->department_id; ?>">
              <?php echo $department->name; ?>
            </a>
          </li>
      <?php
        }
      }
      ?>
        <div class="buttons_desktop">
       <button class="carousel button next">></button>
       </div>
    </ul>
  </section>
</section>
<?php
}
?>
