<?php
namespace Security;

class Cookie {
  private static function encrypt($data) {
    $random = openssl_random_pseudo_bytes(16);
    return base64_encode($random . $data);
  }

  private static function decrypt($data) {
    $raw = base64_decode($data);
    return substr($raw, 16);
  }

  public static function get($key) {
    $value = $_COOKIE[$key];
    return self::decrypt($value);
  }

  public static function set($key, $value, $expire) {
    $value = self::encrypt($value);
    setcookie($key, $value, $expire);
  }

  public static function delete($key) {
    setcookie($key, null, -1);
  }
}