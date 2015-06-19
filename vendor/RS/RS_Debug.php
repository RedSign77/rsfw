<?php

/**
 * Class RS_Debug
 */
class RS_Debug extends Singleton
{
	protected function __construct()
	{

	}

	public function __toStirng()
	{
		$ret = "";
		$cache = SessionCache::getInstance();
		return $ret;
	}
}