<?
class db_model {
  protected static $table_name; //defined in individual object
  protected $prop = array();
  private static $db = null;

  public static function setDB() {
    if (is_null(self::$db)) {
      self::$db = new PDO('mysql:host=mysql;dbname=web', 'root', 'yangqia');
    }
    return self::$db;
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
      $sql_key .= "`" . $key . "`";
      $sql_value .= ":" . $key;
      if($n != $count) {
        $sql_key .= ", "; 
        $sql_value .= ", ";
      }
    }

    $sql = "INSERT INTO `" . static::$table_name . "`(" . $sql_key . ") VALUES (" . $sql_value . ")";
    $q = self::setDB()->prepare($sql);

    $q->execute($this->prop);
  }
}