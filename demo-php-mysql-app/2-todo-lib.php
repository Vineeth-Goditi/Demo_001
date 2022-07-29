<?php
class ToDo {
  // (A) CONSTRUCTOR - CONNECT TO DATABASE
  private $pdo = null;
  private $stmt = null;
  public $error = "";
  function __construct () {
    try {
      $this->pdo = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET,
        DB_USER, DB_PASSWORD, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
      ]);
    } catch (Exception $ex) { exit($ex->getMessage()); }
  }

  // (B) DESTRUCTOR - CLOSE DATABASE CONNECTION
  function __destruct () {
    if ($this->stmt!==null) { $this->stmt = null; }
    if ($this->pdo!==null) { $this->pdo = null; }
  }

  // (C) SUPPORT FUNCTION - SQL QUERY
  function query ($sql, $data) {
    try {
      $this->stmt = $this->pdo->prepare($sql);
      $this->stmt->execute($data);
      return true;
    } catch (Exception $ex) {
      $this->error = $ex->getMessage();
      return false;
    }
  }

  // (D) SAVE TO-DO TASK
  function save ($task, $status, $id=null) {
    // (D1) ADD NEW TASK
    if ($id===null) {
      return $this->query(
        "INSERT INTO `todo` (`todo_task`, `todo_status`) VALUES (?,?)",
        [$task, $status]
      );
    }
    // (D2) UPDATE TASK
    else {
      return $this->query(
        "UPDATE `todo` SET `todo_task`=?, `todo_status`=? WHERE `todo_id`=?",
        [$task, $status, $id]
      );
    }
  }

  // (E) GET ALL TASKS
  function getAll () {
    if ($this->query("SELECT * FROM `todo`", null)) {
      return $this->stmt->fetchAll();
    } else{ return false; }
  }

  // (F) DELETE TASK
  function del ($id) {
    return $this->query(
      "DELETE FROM `todo` WHERE `todo_id`=?", [$id]
    );
  }
}

// (G) DATABASE SETTINGS - CHANGE TO YOUR OWN!
define("DB_HOST", "localhost");
define("DB_NAME", "test");
define("DB_CHARSET", "utf8");
define("DB_USER", "root");
define("DB_PASSWORD", "");

// (H) NEW TO-DO OBJECT
$TODO = new ToDo();
