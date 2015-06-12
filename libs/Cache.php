<?php
class Cache extends Singleton
{

	private $cacheType = "Session"; // Modify and generate init{$cacheType} function
	private $defaultTTL = "1200"; // In seconds

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

	public function add($data, $key = null, $ttl = null)
	{

	}

	public function get()
	{

	}

	private function initSession()
	{
		if (!isset($_SESSION[RS_CACHE_KEY])) {
			$_SESSION[RS_CACHE_KEY] = array(
				'init' => time(),
				'data' => null,
			);
		}
	}

}