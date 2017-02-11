<?php
namespace DB;

use DB\Model;

class User extends Model {
  protected static $table_name = 'user';

  public static function findByUser($value, $user) {
    $sql = "SELECT " . $value . " FROM " . self::$table_name . " WHERE username = :user";

    $q = self::setDB()->prepare($sql);
    $q->bindParam(':user', $user, \PDO::PARAM_STR);
    $q->execute();

    if($q->rowCount() > 0) {
      $result = $q->fetch();
      return $result;
    } else {
      return false;
    }
  }
}