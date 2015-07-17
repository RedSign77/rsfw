<?php

/**
 * Class RS_Observer_Log
 */
class RS_Observer_Log implements IObserver
{

	public function update($object, $action) {
		if (method_exists($this, $action."Event")) {
			$this->{$action."Event"}($object);
		}
	}

	private function loginBeforeEvent($object) {
		Core::log("Before login.");
	}

	private function loginAfterEvent($object) {
		Core::log("After login.");
	}

}