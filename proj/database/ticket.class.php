<?php
  declare(strict_types = 1);

  class Ticket {
    public int $ticket_id;
    public int $user_id;
    public ?int $agent_id;
    public int $department_id;
    public string $title;
    public string $description;
    public string $status;
    public string $priority;
    public string $created_at;
    public string $updated_at;
    public array $hashtags;

    public function __construct(int $ticket_id, 
    int $user_id, ?int $agent_id,int $department_id,
     string $title, string $description,
     string $status, string $priority,
      string $created_at, string $updated_at,array $hashtags
      )
    {
      $this->ticket_id = $ticket_id;
      $this->user_id = $user_id;
      $this->agent_id = $agent_id;
      $this->department_id = $department_id;
      $this->title = $title;
      $this->description = $description;
      $this->status = $status;
      $this->priority = $priority;
      $this->created_at = $created_at;
      $this->updated_at = $updated_at;
      $this->hashtags = $hashtags;
    }

    static function getTickets(PDO $db) : array {
      $stmt = $db->prepare('
        SELECT t.ticket_id, t.user_id,
         t.agent_id, t.department_id, t.title, t.description,
          t.status, t.priority, t.created_at, t.updated_at, t.hashtags
        FROM Tickets t, Users u
        WHERE u.user_id = t.user_id
        GROUP BY 1;
      ');
      $stmt->execute(array());
  
      $tickets_array = array();
    
      while ($ticket = $stmt->fetch()) {
        $hashtags_array=explode(',', $ticket['hashtags']);
        $tickets_array[] = new Ticket(
          $ticket['ticket_id'], 
          $ticket['user_id'],
          $ticket['agent_id'],
          $ticket['department_id'],
          $ticket['title'],
          $ticket['description'],
          $ticket['status'],
          $ticket['priority'],
          $ticket['created_at'],
          $ticket['updated_at'], 
          $hashtags_array
        );
      }
  
      return $tickets_array;
    }

    static function getTicketsFromUser(PDO $db, int $id) : array {
      $stmt = $db->prepare('
        SELECT t.ticket_id, t.user_id, t.agent_id,
         t.department_id, t.title, t.description,
          t.status, t.priority, t.created_at, t.updated_at, t.hashtags
        FROM Tickets t
        WHERE t.user_id = ?
        GROUP BY 1;
      ');
      $stmt->execute(array($id));
  
      $tickets_array = array();
  
      while ($ticket = $stmt->fetch()) {
        $hashtags_array=explode(',', $ticket['hashtags']);
        $tickets_array[] = new Ticket(
          $ticket['ticket_id'], 
          $ticket['user_id'],
          $ticket['agent_id'],
          $ticket['department_id'],
          $ticket['title'],
          $ticket['description'],
          $ticket['status'],
          $ticket['priority'],
          $ticket['created_at'],
          $ticket['updated_at'], 
          $hashtags_array
        );
      }
  
      return $tickets_array;
    }

    static function getSingleTicket(PDO $db, int $id) : Ticket {
      $stmt = $db->prepare('
        SELECT t.ticket_id, t.user_id, t.agent_id,
         t.department_id, t.title, t.description,
          t.status, t.priority, t.created_at, t.updated_at, t.hashtags
        FROM Tickets t
        WHERE t.ticket_id = ?
        GROUP BY 1;
      ');
      $stmt->execute(array($id));
  
      $ticket = $stmt->fetch();
      $hashtags_array = explode(',', $ticket['hashtags'] ?? '');
      return new Ticket(
          $ticket['ticket_id'], 
          $ticket['user_id'],
          $ticket['agent_id'],
          $ticket['department_id'],
          $ticket['title'],
          $ticket['description'],
          $ticket['status'],
          $ticket['priority'],
          $ticket['created_at'],
          $ticket['updated_at'], 
          $hashtags_array
        );
      }

      static function getTicketsInProgressFromUser(PDO $db, int $id) : array {
        $stmt = $db->prepare('
          SELECT t.ticket_id, t.user_id, t.agent_id,
           t.department_id, t.title, t.description,
            t.status, t.priority, t.created_at, t.updated_at, t.hashtags
          FROM Tickets t
          WHERE t.user_id = ? AND t.status = "in progress"
          GROUP BY 1;
        ');
        $stmt->execute(array($id));
    
        $tickets_array = array();
    
        while ($ticket = $stmt->fetch()) {
          $hashtags_array=explode(',', $ticket['hashtags']);
        $tickets_array[] = new Ticket(
          $ticket['ticket_id'], 
          $ticket['user_id'],
          $ticket['agent_id'],
          $ticket['department_id'],
          $ticket['title'],
          $ticket['description'],
          $ticket['status'],
          $ticket['priority'],
          $ticket['created_at'],
          $ticket['updated_at'], 
          $hashtags_array
        );
        }
    
        return $tickets_array;
      }

      static function getTicketsResolvedFromUser(PDO $db, int $id) : array {
        $stmt = $db->prepare('
          SELECT t.ticket_id, t.user_id, t.agent_id,
           t.department_id, t.title, t.description,
            t.status, t.priority, t.created_at, t.updated_at, t.hashtags
          FROM Tickets t
          WHERE t.user_id = ? AND t.status = "resolved"
          GROUP BY 1;
        ');
        $stmt->execute(array($id));
    
        $tickets_array = array();
    
        while ($ticket = $stmt->fetch()) {
          $hashtags_array=explode(',', $ticket['hashtags']);
        $tickets_array[] = new Ticket(
          $ticket['ticket_id'], 
          $ticket['user_id'],
          $ticket['agent_id'],
          $ticket['department_id'],
          $ticket['title'],
          $ticket['description'],
          $ticket['status'],
          $ticket['priority'],
          $ticket['created_at'],
          $ticket['updated_at'], 
          $hashtags_array
        );
        }
    
        return $tickets_array;
      }

      public function save(PDO $db): void {
        if ($this->ticket_id === 0) {
          $stmt = $db->prepare('INSERT INTO Tickets (user_id, agent_id, department_id,
          title, description, status, priority, created_at, updated_at,hashtags)
           VALUES (?, ?, ?, ?, ?, ?, ?,?,?,?)');
          $stmt->execute([$this->user_id, null, $this->department_id, $this->title, $this->description,
           $this->status, $this->priority, $this->created_at, $this->updated_at,json_encode($this->hashtags)]);
           $this->ticket_id = intval($db->lastInsertId());
      }
      else {

      }
  }

static function getTicketsFromAgent(PDO $db, int $agent_id) : array{
      $stmt = $db->prepare('
      SELECT t.ticket_id, t.user_id, t.agent_id,
      t.department_id, t.title, t.description,
        t.status, t.priority, t.created_at, t.updated_at, t.hashtags
      FROM Tickets t
      WHERE t.agent_id = ?
      GROUP BY 1;
    ');
    $stmt->execute(array($agent_id));

    $tickets_array = array();

    while ($ticket = $stmt->fetch()) {
      $hashtags_array=explode(',', $ticket['hashtags']);
        $tickets_array[] = new Ticket(
          $ticket['ticket_id'], 
          $ticket['user_id'],
          $ticket['agent_id'],
          $ticket['department_id'],
          $ticket['title'],
          $ticket['description'],
          $ticket['status'],
          $ticket['priority'],
          $ticket['created_at'],
          $ticket['updated_at'], 
          $hashtags_array
        );
    }

    return $tickets_array;
}

static function getTicketsFromDepartment(PDO $db, int $department_id) : array{
  $stmt = $db->prepare('
  SELECT t.ticket_id, t.user_id, t.agent_id,
  t.department_id, t.title, t.description,
    t.status, t.priority, t.created_at, t.updated_at, t.hashtags
  FROM Tickets t
  WHERE t.department_id = ?
  GROUP BY 1;
');
$stmt->execute(array($department_id));

$tickets_array = array();

while ($ticket = $stmt->fetch()) {
  $hashtags_array=explode(',', $ticket['hashtags']);
  $tickets_array[] = new Ticket(
    $ticket['ticket_id'], 
    $ticket['user_id'],
    $ticket['agent_id'],
    $ticket['department_id'],
    $ticket['title'],
    $ticket['description'],
    $ticket['status'],
    $ticket['priority'],
    $ticket['created_at'],
    $ticket['updated_at'], 
    $hashtags_array
  );
}

return $tickets_array;
}


static function getAllTickets(PDO $db): array{
  $stmt = $db->prepare('
  SELECT t.ticket_id, t.user_id, t.agent_id,
  t.department_id, t.title, t.description,
    t.status, t.priority, t.created_at, t.updated_at, t.hashtags
  FROM Tickets t
');
$stmt->execute(array());

$tickets_array = array();

while ($ticket = $stmt->fetch()) {
  $hashtags_array=explode(',', $ticket['hashtags']);
        $tickets_array[] = new Ticket(
          $ticket['ticket_id'], 
          $ticket['user_id'],
          $ticket['agent_id'],
          $ticket['department_id'],
          $ticket['title'],
          $ticket['description'],
          $ticket['status'],
          $ticket['priority'],
          $ticket['created_at'],
          $ticket['updated_at'], 
          $hashtags_array
        );
}

return $tickets_array;

}


static function searchTickets(PDO $db, string $search,int  $department) : array {
  $stmt = $db->prepare('
                SELECT t.ticket_id, t.user_id, t.agent_id,
                t.department_id, t.title, t.description,
                t.status, t.priority, t.created_at, t.updated_at,t.hashtags
                FROM Tickets t
                WHERE t.title LIKE ? OR t.hashtags LIKE ?');
  $stmt->execute(array('%'.$search.'%','%'.$search.'%'));
  $tickets = array();
  while ($ticket = $stmt->fetch()) {
    if($department===0 ||$department===intval($ticket['department_id']) ){
    $hashtags_array=explode(',', $ticket['hashtags']);
    $tickets[] = new Ticket(
      $ticket['ticket_id'], 
      $ticket['user_id'],
      $ticket['agent_id'],
      $ticket['department_id'],
      $ticket['title'],
      $ticket['description'],
      $ticket['status'],
      $ticket['priority'],
      $ticket['created_at'],
      $ticket['updated_at'],
      $hashtags_array
    );
    }
}
  return $tickets;
}

function get_file_path() : string{
  $file_extensions = ['jpg', 'jpeg', 'png', 'pdf','txt'];
  
  foreach ($file_extensions as $extension) {
    $file_path = "../files/$this->ticket_id.$extension";
    if (file_exists($file_path)) {
      return $file_path;
    }
  }
  return "";
}


  }

?>