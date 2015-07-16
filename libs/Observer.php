<?php
/**
 * Class Observer
 */
class Observer
{

	private $events = array();

	protected function __construct()
	{

	}

	private function validEvent($key) {
		return !in_array($key, $this->events);
	}

	public function hashKey(Event $event) {
		$key = $event->getKey();

		return $key;
	}

	public function attach(Event $event) {
		if ($this->validEvent($event->getKey())) {
			$this->events[$this->hashKey($event)] = $event;
			return true;
		}
		return false;
	}

}