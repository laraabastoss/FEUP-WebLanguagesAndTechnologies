<?php 
  declare(strict_types = 1);

  require_once(__DIR__ . '/../database/user.class.php');
?>
<?php
function drawEditProfile($session)
{
?>
<section class="edit_profile_container">
  <h1>Edit Profile</h1>
  <form action="../actions/action_editprofile.php" method="post" enctype="multipart/form-data">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Your name..">

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Your email..">

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Your password..">

    <label for="confirm-password">Confirm Password:</label>
    <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password..">

    <label for="bio">Bio:</label>
    <textarea id="bio" name="bio" maxlength="100" placeholder="Tell us about yourself.."></textarea>

    <label for="avatar">Avatar:</label>
    <input type="file" id="avatar" name="avatar" accept="image/*">

    <input type="submit" formmethod="post" value="UPDATE">

    <?php if ($session->getMessages()) { ?>
      <section class="error-messages">
        <?php foreach ($session->getMessages() as $message) {
          if ($message['type'] === "error-profile-update") { ?>
            <article class="<?= $message['type'] ?>">
              <?= $message['text'] ?>
            </article>
            <?php break;
          }
           ?>
        <?php }
        } ?>
      </section>
      
  </form>
</section>
<?php
}
?>
