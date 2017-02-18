<?php
namespace DB;

class Model {
  protected static $table_name; //defined in individual object
  protected $prop = array();
  private static $db = null;

  public static function setDB() {
    if (is_null(self::$db)) {
      self::$db = new \PDO('mysql:host=mysql;dbname=web', 'root', 'yangqia');
    }
    return self::$db;
  }

  public static function all() {
    return true;
  }
  
  public static function findByUser($value, $user) {
    return true;
  }

  public function __set($key, $value) {
    return $this->prop[$key] = $value;
  }

  public function __get($key) {
    return $this->prop[$key];
  }

  public function save() {
    $sql_key = "";
    $sql_value = "";
    $n = 0;
    $count = count($this->prop);

    foreach($this->prop as $key => $value){
      $n++;
      $sql_set .= $key . "=" . ":" . $key;
      if($n != $count) {
        $sql_set .= ", ";
      }
    }

    if(isset($this->prop['id'])) {
      $sql = "UPDATE " . static::$table_name . " SET " . $sql_set . " WHERE id=:id";
    } else {
      $sql = "INSERT INTO " . static::$table_name . " SET " . $sql_set;
    }
    
    $q = self::setDB()->prepare($sql);

    $q->execute($this->prop);
  }
<<<<<<< HEAD:web/includes/model.php

  public function del($id) {
    $sql = "DELETE FROM " . static::$table_name . " WHERE id = ?";

    $q = self::setDB()->prepare($sql);
    $q->execute(array($id));
  }
}
=======
}
>>>>>>> 9c26cf8ead2160c686325a61b98e0ad9609d8694:web/includes/Model.php
