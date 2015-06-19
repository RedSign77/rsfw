<?php

/**
 * Class RS_Debug
 */
class RS_Debug extends Singleton
{
	protected function __construct()
	{

	}

	public function __toString()
	{
		$ret = "";
		$cache = SessionCache::getInstance();
		$ret .= $cache;
		return $ret;
	}
}