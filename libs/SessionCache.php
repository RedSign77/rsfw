<?php

/**
 * Class SessionCache
 */
class SessionCache extends Cache
{

	private $cacheType = "Session"; // Modify and generate init{$cacheType} function
	private $defaultTTL = 1200; // In seconds

	/**
	 * Initialize Session caching or invalidate cache
	 */
	private function initSession() {
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
	 * @throws Exception
	 */
	private function getSession($key) {
		if (isset($_SESSION[RS_CACHE_KEY][$key])) {
			return $_SESSION[RS_CACHE_KEY][$key];
		}
		if ($this->throwException) {
			throw new Exception("No (" . $key . ") cache key found in the cache.");
		}

		return false;
	}

	/**
	 * Set session cache data
	 *
	 * @param $data
	 * @param $key
	 * @param $update
	 * @param $ttl
	 * @throws Exception
	 * @return bool
	 */
	private function addSession($data, $key, $update = true, $ttl) {
		if (isset($_SESSION[RS_CACHE_KEY][$key])) {
			if (!$update) {
				if ($this->throwException) {
					throw new Exception("The (" . $key . ") key in the cache, but overwrite ignored.");
				}

				return false;
			}
		}
		$_SESSION[RS_CACHE_KEY][$key] = array(
			'ttl'  => is_null($ttl) ? time() + $this->defaultTTL : $ttl,
			'data' => $data,
		);

		return true;
	}
}