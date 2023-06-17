<?php
  declare(strict_types = 1);

  class Comment{
    public int $comment_id;
    public int $user_id;
    public int $ticket_id;
    public string $comment;
    public string $created_at;
    public function __construct(int $comment_id, 
    int $user_id, int $ticket_id,string $comment, string $created_at)
    {
      $this->comment_id = $comment_id;
      $this->user_id = $user_id;      
      $this->ticket_id = $ticket_id;
      $this->comment = $comment;
      $this->created_at=$created_at;
    }

    static public function getComments(PDO $db,int $ticket_id):array{
        $stmt = $db->prepare('
        SELECT comment_id, user_id, ticket_id, comment, created_at
        FROM Comments
        WHERE ticket_id= ?
      ');

      $stmt->execute(array($ticket_id));
      $comments = array();
      while ($comment=$stmt->fetch()){
        $comments[]=new Comment(
          $comment['comment_id'],
          $comment['user_id'],
          $comment['ticket_id'],
          $comment['comment'],
          $comment['created_at']
        );

      }
      return $comments;
    }

    public function save(PDO $db): void {
        if ($this->comment_id === 0) {
          $stmt = $db->prepare('INSERT INTO Comments (user_id, ticket_id,comment,created_at) VALUES (?, ?, ?,?)');
          $stmt->execute([$this->user_id, $this->ticket_id, $this->comment, $this->created_at]);
           $this->comment_id = intval($db->lastInsertId());
      }
  }

  }