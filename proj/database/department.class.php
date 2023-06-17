<?php
  declare(strict_types = 1);

use JetBrains\PhpStorm\Deprecated;

  class Department{
    public int $department_id;
    public string $name;

    public function __construct(
        int $department_id, string $name
      )
    {
      $this->department_id = $department_id;
      $this->name = $name;
    }

    static function getDepartmentsFromAgent(PDO $db, ?int $agent_id) : array {
      $stmt = $db->prepare('
        SELECT department_id
        FROM Department_Agent
        WHERE agent_id = ?
      ');
      $stmt->execute(array($agent_id));

      $agent_departments = array();
      while ($agent_department=$stmt->fetch()){
        $agent_departments[]= Department::getSingleDepartment($db, $agent_department['department_id']);
      }

      return $agent_departments;
    }

    static function getSingleDepartment(PDO $db, int $id) : Department {
      $stmt = $db->prepare('
        SELECT department_id, name
        FROM Departments
        WHERE department_id = ?
      ');
      $stmt->execute(array($id));
  
      $department = $stmt->fetch();
  
      return new Department(
        $department['department_id'], 
        $department['name']
        );
      }

      static function getDepartments(PDO $db) : array{
        $stmt = $db->prepare('
        SELECT department_id, name
        FROM Departments
      ');
      $stmt->execute(array());

      $departments = array();
      while ($department=$stmt->fetch()){
        $departments[]=new Department(
          $department['department_id'],
          $department['name']
        );

      }
      return $departments;
     
    }

    static function getDepartmentByName(PDO $db, string $name) : Department {
      $stmt = $db->prepare('
        SELECT department_id, name
        FROM Departments
        WHERE name = ?
      ');
      $stmt->execute(array($name));
  
      $department = $stmt->fetch();
  
      return new Department(
        $department['department_id'], 
        $department['name']
        );
      }
  
  
  }
 
?>