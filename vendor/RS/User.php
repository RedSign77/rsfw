<?php
class RS_User extends Singleton{

  private static $session = "user";
  private $data = null;

  public function __construct()
  {
    if (isset($_SESSION[self::$session])) {
      $this->data = $_SESSION[self::$session];
    }
  }

  public function login($mail, $password) {
    if (filter_var($mail, FILTER_VALIDATE_EMAIL) && trim($password)) {

    }
    return false;
  }

}