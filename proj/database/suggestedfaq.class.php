<?php
  declare(strict_types = 1);

  class SuggestedFAQ {
    public int $question_id;
    public string $title;
    public function __construct(int $question_id, string $title,
      )
    {
      $this->question_id = $question_id;
      $this->title = $title;
    }

    static function getSuggestedFAQ(PDO $db) : array {
      $stmt = $db->prepare('
        SELECT f.question_id, f.title
        FROM Suggested_Frequently_Asked_Questions f;');
      $stmt->execute(array());

      $faq_array = array();
      while ($faq = $stmt->fetch()) {
        $faq_array[] = new SuggestedFAQ(
          $faq['question_id'], 
          $faq['title'],
        );
      }
      return $faq_array;
    }



    static function getSingleFAQ(PDO $db, int $id) : SuggestedFAQ {
        $stmt = $db->prepare('
        SELECT f.question_id, f.title
        FROM Suggested_Frequently_Asked_Questions f
        WHERE f.question_id=id;');
      $stmt->execute(array());
      $faq=$stmt -> fetch();
  
        return new SuggestedFAQ(
          $faq['question_id'], 
          $faq['title'],
        );
      }
      public function save(PDO $db): void { //funçao para adicionar users quando dao register e para dar update (para dps dar update a roles etc.)
        
          // quando cria um novo user no register cria com o id a 0, mas dá update a esse user-Id para ser igual à base de dados quando é inserido lá
          $stmt = $db->prepare('INSERT INTO Suggested_Frequently_Asked_Questions  (title) VALUES (?)');
          $stmt->execute([$this->title]);
          $this->question_id = intval($db->lastInsertId());
        }

        public static function remove(PDO $db,$suggestedfaq_id){
          $stmt = $db->prepare(
          'DELETE 
            FROM Suggested_Frequently_Asked_Questions 
            WHERE question_id=?;');
          $stmt->execute(array($suggestedfaq_id));
        }
      }

?>