<?
include('conn.php');

class db_model {
  protected $table_name; //defined in individual object
  protected $prop = array();

  public function findByUser($value, $user) {
    global $conn;

    $sql = "SELECT " . $value . " FROM " . $this->table_name . " WHERE username = :user";

    $q = $conn->prepare($sql);
    $q->bindParam(':user', $user, PDO::PARAM_STR);
    $q->execute();

    if($q->rowCount() > 0) {
      $result = $q->fetch();
      return $result;
    } else {
      return false;
    }
  }

  public function __set($key, $value) {
    return $this->prop[$key] = $value;
  }

  public function __get($key) {
    return $this->prop[$key];
  }

  public function save() {
    global $conn;

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

    $sql = "INSERT INTO `" . $this->table_name . "`(" . $sql_key . ") VALUES (" . $sql_value . ")";

    $q = $conn->prepare($sql);

    $q->execute($this->prop);
  }
}