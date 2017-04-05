<?php
namespace DB;

use DB\Model;

class Comment extends Model {
  protected static $table_name = 'comment';

  function __construct($get_id=null) {
    $this->id = $get_id;
  }

  public static function all(){
    $sql = "SELECT * FROM " . self::$table_name;

    $q = self::setDB()->prepare($sql);
    $q->execute();

    if($q->rowCount() > 0) {
      $result = $q->fetchAll();
      return $result;
    } else {
      return false;
    }
  }

  public static function findByPage($page, $size) {
    $start = ($page - 1) * $size;
    $sql = "SELECT * FROM " . self::$table_name . " ORDER BY id DESC LIMIT $start, $size";
    $q = self::setDB()->prepare($sql);
    $q->execute();

    if($q->rowCount() > 0) {
      $result = $q->fetchAll();
      return $result;
    } else {
      return false;
    }
  }
}