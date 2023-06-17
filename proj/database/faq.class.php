<?php
  declare(strict_types = 1);

  class FAQ {
    public int $question_id;
    public string $title;
    public string $description;

    public function __construct(int $question_id, string $title,
   string $description
      )
    {
      $this->question_id = $question_id;
      $this->title = $title;
      $this->description = $description;
    }

    static function getFAQ(PDO $db) : array {
      $stmt = $db->prepare('
        SELECT f.question_id, f.title, f.description
        FROM Frequently_Asked_Questions f;');
      $stmt->execute(array());
  
      $faq_array = array();
      while ($faq = $stmt->fetch()) {
        $faq_array[] = new FAQ(
          $faq['question_id'], 
          $faq['title'],
          $faq['description'],
        );
      }
  
      return $faq_array;
    }



    static function getSingleFAQ(PDO $db, int $id) : FAQ {
        $stmt = $db->prepare('
        SELECT f.question_id, f.title, f.description
        FROM Frequently_Asked_Questions f
        WHERE f.question_id=id;');
      $stmt->execute(array());
      $faq=$stmt -> fetch();
  
        return new FAQ(
          $faq['question_id'], 
          $faq['title'],
          $faq['description'],
        );
      }

      public function save(PDO $db): void {
        if ($this->question_id === 0) {
          $stmt = $db->prepare('INSERT INTO Frequently_Asked_Questions  (title, description) VALUES (?, ?)');
          $stmt->execute([ $this->title, $this->description]);
          $this->question_id = intval($db->lastInsertId());
        }
      }
      

}
?>