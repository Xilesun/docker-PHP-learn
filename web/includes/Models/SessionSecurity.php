<?php
namespace Security;

use DB\model;

class SessionStore extends Model {
  protected static $table_name = 'session';

  public static function all(){
    $sql = "SELECT * FROM " . self::$table_name;

    $q = self::setDB()->prepare($sql);
    $q->execute();

    if($q->rowCount() > 0) {
      $result = $q->fetch();
      return $result;
    } else {
      return false;
    }
  }
}

class SessionSecurity extends \SessionHandler {
  private function encrypt($data) {
    $random = openssl_random_pseudo_bytes(16);
    return base64_encode($random . $data);
  }

  private function decrypt($data) {
    $raw = base64_decode($data);
    return substr($raw, 16);
  }

  public static function find() {
    return SessionStore::all();
  }

  public function read($id) {
    $result = SessionStore::all();
    $data = $result['data'];
    if (!$data) {
      return false;
    } else {
      return $this->decrypt($data);
    }
  }

  public function write($id, $data) {
    if (!$data) {
      return true;
    } else {
      $data = $this->encrypt($data);
    }

    $new_session = new SessionStore();
    $new_session->session_id = $id;
    $new_session->data = $data;
    $new_session->save();
    return true;
  }

  public function destroy($key) {
    SessionStore::delete(1);
    return true;
  }
}