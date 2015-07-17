<?php

/**
 * Class Core
 */
class Core extends Singleton
{

	private static $mode = null;
	private static $dateToday = "Ma";
	private static $dateYesterday = "Tegnap";

	public static $theme = "Default";
	public static $base = "http://www.rsfw.dev";

	public static $viewDirectory = "theme";
	public static $logDirectory = "files/log";

	protected function __construct($mode) {
		self::$mode = $mode;
	}

	/**
	 * Return base theme URL
	 *
	 * @param bool $base
	 * @return string
	 */
	public static function themeUrl() {
		return self::$viewDirectory . "/" . self::$theme . "/";
	}

	/**
	 * Highlight menu item
	 *
	 * @param string $pageId
	 * @return null|string
	 */
	public static function h($pageId = '') {
		if ($pageId == $_SESSION['page']['id']) {
			return " class='active'";
		}

		return null;
	}

	/**
	 * Returns part of string
	 *
	 * @param string $string
	 * @param int    $index
	 * @return string
	 */
	public static function subString($string, $index = 24) {

		if (strlen($string) > $index) {
			$position = strpos($string, " ", ($index > 2 ? $index - 2 : 0));
			$string = substr($string, 0, $position);
			$string .= "...";
		}

		return $string;
	}

	/**
	 * @name isCore
	 * @return bool
	 */
	public static function isCore() {
		return (isset($_SESSION['core']) && $_SESSION['core'] == 1);
	}

	/**
	 * @name isAjax
	 * @return bool
	 */
	public static function isAjax() {
		return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
	}

	/**
	 * get the IP address
	 *
	 * @name getIP ()
	 * @return string
	 */
	public static function getIP() {
		$ip = "unknown";
		if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
			$ip = getenv("HTTP_CLIENT_IP");
		}
		else {
			if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
				$ip = getenv("REMOTE_ADDR");
			}
			else {
				if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
					$ip = getenv("HTTP_X_FORWARDED_FOR");
				}
				else {
					if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
						$ip = $_SERVER['REMOTE_ADDR'];
					}
				}
			}
		}

		return $ip;
	}

	/**
	 * Select the first i tag on the URL
	 *
	 * @param string  $url
	 * @param integer $index
	 * @return string
	 */
	public static function getURI($url, $index = 1) {
		$_tmp = explode("/", $url);
		$ret = "";
		if (is_array($_tmp)) {
			for ($i = 0; $i < $index; $i++) {
				$ret .= $_tmp[$i] . "/";
			}
		}

		return $ret;
	}

	/**
	 * Returns the last integer on the selected string
	 *
	 * @param string $url
	 * @return string
	 */
	public static function getLastId($url) {
		$ret = explode("-", $url);
		if (is_array($ret)) {
			return end($ret);
		}

		return $ret;
	}

	/**
	 * Remova all special character to get a nice url string
	 *
	 * @param string $text
	 * * @return string
	 */
	public static function postSlug($text) {
		return strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', self::removeAccent($text)));
	}

	/**
	 * Replace all hungarian character
	 *
	 * @param string $text
	 * @return string
	 */
	public static function removeAccent($text) {
		$special = array(
			'ö',
			'ü',
			'ó',
			'ő',
			'ú',
			'é',
			'á',
			'ű',
			'í',
			'Ö',
			'Ü',
			'Ó',
			'Ő',
			'Ú',
			'É',
			'Á',
			'Ű',
			'Í'
		);
		$changed = array(
			'o',
			'u',
			'o',
			'o',
			'u',
			'e',
			'a',
			'u',
			'i',
			'O',
			'O',
			'O',
			'O',
			'U',
			'E',
			'A',
			'U',
			'I'
		);

		return str_replace($special, $changed, $text);
	}

	/**
	 * Get current timestamp
	 *
	 * @return bool|string
	 */
	public static function getTS() {
		$dt = new DateTime('now');
		return $dt->getTimestamp();
	}

	/**
	 * Date to string compiler
	 *
	 * @param $time
	 * @return string
	 */
	public static function dateToString($time) {
		$time = strtotime($time);
		$now = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
		$yesterday = $now - (3600 * 24);
		$ret = date("Y.m.d. H:i", $time);
		if ($time > $now) {
			$ret = self::$dateToday . ", " . date("H:i", $time);
		}
		if ($now > $time && $time > $yesterday) {
			$ret = self::$dateYesterday . ", " . date("H:i", $time);
		}

		return $ret;
	}

	/**
	 * Format date
	 *
	 * @param     $date
	 * @param int $type
	 * @return bool|string|object
	 */
	public static function formatDate($date, $type = 0) {
		$time = strtotime($date);
		switch ($type) {
			case 1:
				return date("Y.m.d. H:i", $time);
				break;
			case 2:
				return date("Y-m-d", $time);
				break;
			case 3:
				return new DateTime('now');
				break;
			default:
				return date("Y-m-d H:i:s", $time);
				break;
		}
	}

	/**
	 * Get random words from a string
	 *
	 * @param      $text
	 * @param int  $count
	 * @param bool $inArray
	 * @return string or array
	 */
	public static function getRandomWords($text, $count = 10, $inArray = true) {
		$words = explode(" ", $text);
		$maxWords = str_word_count($text, 1);
		if ($maxWords < $count) {
			$count = $maxWords;
		}
		shuffle($words);
		$selection = array_slice($words, 0, $count);
		if ($inArray) {
			return $selection;
		}

		return implode(", ", $selection);
	}

	/**
	 * Strip tags
	 *
	 * @param $string
	 * @return mixed|string
	 */
	public static function rip_tags($string) {
		$string = preg_replace('/<[^>]*>/', ' ', $string);
		$string = str_replace("\r", '', $string);
		$string = str_replace("\n", ' ', $string);
		$string = str_replace("\t", ' ', $string);
		$string = trim(preg_replace('/ {2,}/', ' ', $string));
		return $string;
	}

	/**
	 * Get log file
	 *
	 * @return string
	 */
	public static function logFile() {
		return self::$logDirectory . "/core_".date("Ymd", time()).".log";
	}

	/**
	 * Put message to log
	 *
	 * @param $message
	 * @return int
	 */
	public static function log($message) {
		$dt = self::formatDate(0, 3);
		$message = $dt->format(DateTime::ATOM)." ".$message.PHP_EOL;
		return file_put_contents(self::logFile(), (String) $message, FILE_APPEND);
	}

	/**
	 * Generate hash string
	 *
	 * @param int $limit
	 * @return string
	 */
	public static function generateHash($limit = 32) {
		$baseText = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ-";
		$ret = "";
		for ($i = 0; $i <= $limit; $i++) {
			$ret .= substr($baseText, mt_rand(0, strlen($baseText)), 1);
		}

		return $ret;
	}

	/**
	 * Get config file from sys/config
	 *
	 * @param      $json
	 * @param bool $isArray
	 * @return mixed|string
	 * @throws Exception
	 */
	public static function readConfigJSON($json, $isArray = true) {
		if (is_file("sys/config/" . $json . ".json")) {
			$data = file_get_contents("sys/config/" . $json . ".json");
			if ($isArray) {
				return json_decode($data, true);
			}
			return $data;
		}
		throw new Exception("Aborted: Config file not found!");
	}

}