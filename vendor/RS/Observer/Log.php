<?php

/**
 * Class RS_Observer_Log
 */
class RS_Observer_Log implements IObserver
{

	public function update($object) {
		Core::log($object);
	}
}