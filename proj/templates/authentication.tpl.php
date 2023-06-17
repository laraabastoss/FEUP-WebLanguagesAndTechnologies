<?php 
  declare(strict_types = 1);

?>

<?php
function drawLogIn(Session $session)
{
?>
<section class="authentication login">
  <h1>LOG IN</h1>
  <form action="../actions/action_login.php" method="post">
    <section class="email">
      <input type="text" name="email" placeholder="email" required="required" abled>
    </section>
    <section class="password">
      <input type="password" name="password" placeholder="password" required="required" abled>
    </section>

    <?php if ($session->getMessages()) { ?>
      <section class="error-messages">
        <?php foreach ($session->getMessages() as $message) {
          if ($message['type'] === "error-login") { ?>
            <article class="<?= $message['type'] ?>">
              <?= $message['text'] ?>
            </article>
            <?php break;
          }
          if ($message['type'] === "error-login") { ?>
            <article class="<?= $message['type'] ?>">
              <?= $message['text'] ?>
            </article>
            <?php break; ?>
        <?php }
        } ?>
      </section>
    <?php } ?>
    <button formmethod="post" type="submit">
      LOG IN
    </button>
  </form>
  <div class="line">
    <p>Dont have an account yet? <p id="signup-link"> Register </a></p></p>
  </div>
</section>
<?php
}
?>

<?php
function drawSignUp(Session $session)
{
?>
<section class="authentication signup">
  <h1>SIGN UP</h1>
  <form action="../actions/action_register.php" method="post">
    <section class="email">
      <input type="text" name="email" placeholder="email" required="required" abled>
    </section>
    <section class="username">
      <input type="text" name="username" placeholder="username" required="required" abled>
    </section>
    <section class="password">
      <input type="password" name="password" placeholder="password" required="required" abled>
    </section>
    <section class="confirm_password">
      <input type="password" name="password2" placeholder="password" required="required" abled>
    </section>

    <?php if ($session->getMessages()) { ?>
      <section class="error-messages">
        <?php foreach ($session->getMessages() as $message) {
          if ($message['type'] === "error-signup") { ?>
            <article class="<?= $message['type'] ?>">
              <?= $message['text'] ?>
            </article>
            <?php break;
          } ?>
        <?php } ?>
      </section>
    <?php } ?>

    <button formmethod="post" type="submit">
      SIGN UP
    </button>
  </form>
  <div class="line">
    <p>Already have an account? <p id="login-link">Login </p> </p>
  </div>
</section>
<?php
}
?>
