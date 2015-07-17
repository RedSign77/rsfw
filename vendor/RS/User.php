<?php
/**
 * Class RS_User
 */
class RS_User extends Singleton
{

	private static $session = "user";
	public static $table = TBL_USERS;
	private $data = null;

	public function __construct() {
		if (isset($_SESSION[self::$session])) {
			$this->data = $_SESSION[self::$session];
		} else {
			$_SESSION[self::$session] = null;
		}
	}

	private function setData($data) {
		$this->data = $data;
		$_SESSION[self::$session] = $data;
	}

	public function login($mail, $password) {
		$this->notifyObservers("loginBefore");
		if (!is_null($this->data)) {
			$this->notifyObservers("loginAfter");
			return true;
		}
		if (filter_var($mail, FILTER_VALIDATE_EMAIL) && trim($password)) {
			$db = Database::getInstance();
			$data = $db->getOneRow(self::$table, "email='".$mail."' AND password='".md5($password)."'");
			if ($data) {
				$this->setData($data);
				$this->notifyObservers("loginAfter");
				return true;
			}
		}
		$this->notifyObservers("loginAfter");
		return false;
	}

	public function logout() {

	}

	public static function getRank()
	{
		return $_SESSION[self::$session]['rank'];
	}

	public static function isLogged()
	{
		if (!is_null($_SESSION[self::$session])) {
			return true;
		}
		return false;
	}

	public function __toString()
	{
		$ret = "";
		if (self::isLogged()) {
			$ret .= "User logged in.";
		} else {
			$ret .= "User not logged in.";
		}
		return $ret;
	}

}
