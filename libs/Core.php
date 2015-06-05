<?php

/**
 * Class Core
 */
class Core extends
  Singleton {

  private static $mode          = NULL;
  private static $dateToday     = "Ma";
  private static $dateYesterday = "Tegnap";

  public static $theme = "Default";
  public static $base  = "http://www.rsfw.dev";

  public static $viewDirectory = "theme";
  public static $logDirectory  = "files/log";

  protected function __construct($mode) {
    self::$mode = $mode;
  }

  /**
   * Return base theme URL
   *
   * @param bool $base
   *
   * @return string
   */
  public static function themeUrl() {
    return self::$viewDirectory . "/" . self::$theme . "/";
  }

  /**
   * Set meta tags
   *
   * @param $title
   * @param $description
   */
  public static function setMeta($title, $description) {
    if (isset($_SESSION['page']['title'])) {
      $_SESSION['page']['title'] = $title;
    }
    if (isset($_SESSION['page']['desc'])) {
      $_SESSION['page']['desc'] = $description;
    }
    if (isset($_SESSION['page']['keywords'])) {
      $_SESSION['page']['keywords'] .= getRandomWords($description);
    }
  }

  /**
   * Highlight menu item
   *
   * @param string $pageId
   *
   * @return null|string
   */
  public static function h($pageId = '') {
    if ($pageId == $_SESSION['page']['id']) {
      return " class='active'";
    }
    return NULL;
  }

  /**
   * Returns part of string
   *
   * @param string $string
   * @param int    $index
   *
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
   *
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
   *
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
   *
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
    return date("Y-m-d H:i:s", time());
  }

  /**
   * Date to string compiler
   *
   * @param $time
   *
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
   *
   * @return bool|string
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
   *
   * @return string or array
   */
  public static function getRandomWords($text, $count = 10, $inArray = TRUE) {
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
   *
   * @return mixed|string
   */
  public static function rip_tags($string) {

    // ----- remove HTML TAGs -----
    $string = preg_replace('/<[^>]*>/', ' ', $string);

    // ----- remove control characters -----
    $string = str_replace("\r", '', $string);    // --- replace with empty space
    $string = str_replace("\n", ' ', $string);   // --- replace with space
    $string = str_replace("\t", ' ', $string);   // --- replace with space
    // ----- remove multiple spaces -----
    $string = trim(preg_replace('/ {2,}/', ' ', $string));

    return $string;
  }

  /**
   * Debug
   *
   * @param      $type
   * @param null $file
   * @param null $line
   * @param bool $log
   *
   * @return string
   */
  public static function debug(&$type, $file = NULL, $line = NULL, $log = TRUE) {
    $ret = "";
    $ret .= "<pre>";
    $ret .= "\nDEBUG - " . date("Y-m-d H:i:s", time());
    if (!is_null($file) && !is_null($line)) {
      $ret .= "\nFile: " . $file;
      $ret .= "\nLine: " . $line;
    }
    var_dump($type);
    $ret .= "</pre>";
    if ($log) {
      file_put_contents(self::logFile(), $ret, FILE_APPEND);
    }
    return $ret;
  }

  public static function logFile() {
    return self::$logDirectory . "/core_" . self::formatDate(time(), 2);
  }
} 