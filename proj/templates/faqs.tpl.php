<?php 
  declare(strict_types = 1); 

  require_once(__DIR__ . '/../database/faq.class.php');
  require_once(__DIR__ . '/../database/department.class.php');

  $db = getDatabaseConnection();

  function drawFAQS($session,$faqs) {  ?>
  <section class="frequently_asked_questions">
    <h1> Frequently Asked Questions </h1> 
    <?php if ($session->getMessages()) { ?>
      <section class="error-messages">
        <?php foreach ($session->getMessages() as $message) {
          if ($message['type'] === "error-submiting") { ?>
            <article class="<?= $message['type'] ?>">
              <?= $message['text'] ?>
            </article>
            <?php break;
          } ?>
        <?php } ?>
      </section>
    <?php } ?>
    <section class="container">
      <section class="faqs">
        <?php
        $i=1;
        foreach ($faqs as $faq ){   ?>
          <section class="faq">  
              <?php
                  drawFAQ($faq,$i); ?> 
          </section> 
          <?php
              $i++;
            }?>
        <section class="title"> 
          Any other question you like to ask?
        </section>  
        <section class="new_faq">   
          <form action="../actions/action_addsuggestedfaq.php" method="post">  
            <textarea name="title" placeholder="Ask whatever you would like to know about our website and we might answer you"></textarea>
            <button type="submit" class="commentbutton">
                      SUBMIT
            </button>
          </form>
        </section>
      </section>
     </section>
  </section>
<?php
  }

function drawFAQ($faq,$i){  ?>
<section class="title" id="faqTitle_<?php echo $i; ?>"> 
   <?php echo $i.". ".$faq->title; ?> 
</section>  
<span class="answer">  <?php echo $faq->description; ?> </span> <?php
}

function drawFAQStouse($faqs){ ?>
  <section class="faqs"> <?php
  $i=1;
  foreach ($faqs as $faq ){  ?>
    <section class="faq">
    <?php
         drawFAQ($faq,$i); ?> </section> <?php
    $i++;
  } ?>
  </section> <?php
}

?>