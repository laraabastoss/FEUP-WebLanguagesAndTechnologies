<?php 
  declare(strict_types = 1);
  
  require_once(__DIR__ . '/../database/department.class.php');
  require_once(__DIR__ . '/../database/user.class.php');

?>

<?php function drawHeader($db, array $css_styles, $user, array $scripts) { ?>
<!DOCTYPE html>
<html lang="en-US">
  <head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1"> 
  <title>LTW Project</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php /*<script src="../js/line_comments.js"></script> */?>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">
      <?php foreach($css_styles as $css_style) { ?>
          <link href="../css/<?=$css_style?>" rel="stylesheet">
      <?php } ?>
      <?php foreach($scripts as $script) { ?>
            <script src="../js/<?=$script?>" defer></script>
        <?php } ?>
  </head>
  <body>  
    <header>
    <input  type="checkbox" id="hamburger"> 
        <label class="hamburger" for="hamburger"></label>
        <section id="sideSection">
              <span id="home"> <a href= homepage.php> Home </a></span>
              <span id="faq"> <a href= faq.php> FAQ </a></span>
              <span id="new">  <a href= ../pages/new_ticket.php>New Ticket </a> </span>
              <?php if (isset($_SESSION['id']) && $user->role === "admin"){ ?>
                 <span id="my-departments"> <a href= ../pages/admin_panel.php>Admin Panel</a> </span>
              <?php }?>
              <?php if (isset($_SESSION['id'])  && !empty(Department::getDepartmentsFromAgent($db, $user->user_id))) { ?> 
                <span id="my-departments"> <a href= ../pages/my_departments.php>My Departments</a> </span>
              <?php } ?>
              <span id="mobile_sign_out"> <a href= ../actions/action_logout.php>SIGN OUT</a> </span>
              <script src="../js/navbar_mobile.js" defer></script>  </section>
        <nav class="navbar">   
        <div class="left">
              <i class="fa fa-home"></i>
              <span id="home"> <a href= homepage.php> Home </a></span>
              <span id="faq"> <a href= faq.php> FAQ </a></span>
              <?php if (isset($_SESSION['id']) && $user->role === "admin"){ ?>
                 <span id="my-departments"> <a href= ../pages/admin_panel.php>Admin Panel</a> </span>
              <?php }?>
          </div>
        <div class="right">
          <?php if (isset($_SESSION['id'])  && !empty(Department::getDepartmentsFromAgent($db, $user->user_id))) { ?> 
            <span id="my-departments"> <a href= ../pages/my_departments.php>My Departments</a> </span>
          <?php } ?>
          <span id="new">  <a href= ../pages/new_ticket.php>New Ticket </a> </span>
          <?php if (isset($_SESSION['id'])){?>
            <span id="profile-picture"> <a href="../pages/profile.php"> <img src="<?php echo $user->get_avatar_path()?>" alt="Avatar Image"> </a> </span>
          <?php } ?>
          <a class="fa fa-sign-out" id = "sign_out" href= ../actions/action_logout.php></a>
        </div>
          </nav>
    </header>
    <body>
    <main>
<?php } 


function drawFooter() { ?>
    </main>

    <div class="all_footer">
    <hr class="divider"> </hr>
    <footer class="footer">
      <p> &copy;  LTW 2023 </p>
      <div class="icons">
        <a href="https://github.com/arestivo" class="fa fa-github"></a>
        <a href="https://web.fe.up.pt/~arestivo/page/" class="fa fa-google"></a>
        <a href="#" class="fa fa-linkedin"></a></div> 
    </footer>
    </div>
  </body>
</html>
<?php } ?> 


<?php function drawSearchBar() { ?>
  <script src="/js/search.js" defer ></script>
  <input id="search" type="text" placeholder="search" class="search">
  <span id="department_searched"></span>
  <section id="results"></section>
<?php } 



function drawAcessDenied(Session $session) { ?>
    <section id="accessDenied">
        <h1>Access Denied</h1>
        <section id="messages">
          <?php foreach ($session->getMessages() as $message) { ?>
            <article class="<?=$message['type']?>">
            <?=$message['text']?>
            </article>
          <?php } ?> 
        </section> 
        <p>Go back to <a href="../pages/index.php">mainpage</a></p>  
    </section> 
<?php } 
?>