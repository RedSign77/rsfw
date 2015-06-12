<?php
class Cache extends Singleton
{

	private $cacheType = "Session"; // Modify and generate init{$cacheType} function
	private $defaultTTL = 1200; // In seconds

	/**
	 * Construct
	 */
	protected function __construct()
	{
		$this->initialize();
	}

	/**
	 * Default initialize cache
	 */
	private function initialize()
	{
		if (method_exists($this, "init".$this->cacheType)) {
			$this->{"init".$this->cacheType}();
		}
	}

	/**
	 * Add data to the cache
	 *
	 * @param      $data
	 * @param null $key
	 * @param null $ttl
	 */
	public function add($data, $key = null, $ttl = null)
	{

	}

	/**
	 *  Get data from the cache
	 *
	 * @param string $key
	 */
	public function get($key)
	{
		if (method_exists($this, "get".$this->cacheType)) {
			return $this->{"get".$this->cacheType}($key);
		}
	}

	/**
	 * Initialize Session caching or invalidate cache
	 */
	private function initSession()
	{
		if (!isset($_SESSION[RS_CACHE_KEY]) || $_SESSION[RS_CACHE_KEY]['init'] < time()) {
			$_SESSION[RS_CACHE_KEY] = array(
				'init' => time() + $this->defaultTTL,
				'data' => null,
			);
		}
	}

	/**
	 * Get session cache data
	 *
	 * @param $key
	 * @return bool
	 */
	private function getSession($key)
	{
		if (isset($_SESSION[RS_CACHE_KEY][$key])) {
			return $_SESSION[RS_CACHE_KEY][$key];
		}
		return false;
	}

}