<?php

/**
 * Class Cache
 */
abstract class Cache extends
    Singleton implements
    ICache {

	protected $throwException = false;
	protected $cacheType  = "NotDefined"; // Modify and generate init{$cacheType} function

    /**
     * Construct
     */
    protected function __construct() {
        $this->initialize();
    }

    /**
     * Default initialize cache
     */
    private function initialize() {
        if (method_exists($this, "init" . $this->cacheType)) {
            $this->{"init" . $this->cacheType}();
        }
    }

    /**
     * Add data to the cache
     *
     * @param      $data
     * @param string $key
	 * @param bool $update
     * @param int $ttl
     */
    public function add($data, $key = null, $update = true, $ttl = null) {
        if (method_exists($this, "add" . $this->cacheType)) {
			return $this->{"add" . $this->cacheType}($data, $key, $update, $ttl);
        }
    }

    /**
     *  Get data from the cache
     *
     * @param string $key
     */
    public function get($key) {
        if (method_exists($this, "get" . $this->cacheType)) {
            return $this->{"get" . $this->cacheType}($key);
        }
    }


    /**
     * Normal destruct
     */
    public function __destruct()
    {
        if (method_exists($this, "destruct" . $this->cacheType)) {
            return $this->{"destruct" . $this->cacheType}($key);
        }
    }

	/**
	 * For debugging
	 *
	 * @return string
	 */
	public function __toString()
	{
		$ret = "";
		$ret .= "<h3>Cache debug</h3>";
		$ret .= "<p>Cache Type: ".$this->cacheType;
		$ret .= "<br />Throw Exception: ".($this->throwException ? "Yes" : "No");
		$ret .= "<br />Cache key: ".RS_CACHE_KEY."</p>";
		return $ret;
	}

}