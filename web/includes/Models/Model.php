<?php
namespace DB;

class Model {
  protected static $table_name; //defined in individual object
  protected $id;
  protected $prop = array();
  private static $db = null;

  public static function setDB() {
    if (is_null(self::$db)) {
      self::$db = new \PDO('mysql:host=mysql;dbname=web', 'root', 'yangqia');
    }
    return self::$db;
  }

  public static function count() {
    $sql = "SELECT COUNT(*) FROM " . static::$table_name;
    $q = self::setDB()->prepare($sql);
    $q->execute();
    $result = $q->fetchColumn();
    return $result;
  }

  public static function all() {
    return true;
  }

  public static function findByPage($page, $size) {
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

    if(!is_null($this->id)) {
      $this->prop['id'] = $this->id;
      $sql = "UPDATE " . static::$table_name . " SET " . $sql_set . " WHERE id=:id";
    } else {
      $sql = "INSERT INTO " . static::$table_name . " SET " . $sql_set;
    }
    
    $q = self::setDB()->prepare($sql);

    $q->execute($this->prop);
  }

  private static function deleteById($ids) {
    if(!is_array($ids)) {
      $ids = array($ids);
    }

    $count = count($ids);
    $n = 0;
    $id_sql = '';
    foreach($ids as $number) {
      $n++;
      if(!is_numeric($number)) continue;
      $id_sql .= '?';
      if($n != $count) {
        $id_sql .= ", ";
      }
    }

    $sql = "DELETE FROM " . static::$table_name . " WHERE id IN (" . $id_sql . ")";
    $q = self::setDB()->prepare($sql);
    $q->execute($ids);
  }

  public static function __callStatic($name, $args) {
    switch ($name) {
      case 'delete':
        static::deleteById($args[0]);
        break;      
      default:
        echo "no such a function";
        break;
    }
  }

  public function __call($name, $args) {
    switch ($name) {
      case 'delete':
        static::deleteById($this->id);
        break;
      default:
        echo "no such a function";
        break;
    }
  }
}