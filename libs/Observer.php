<?php
/**
 * Class Observer
 */
class Observer extends Singleton
{

	private $events = array();

	protected function __construct()
	{

	}

	private function validEvent($key) {
		return !in_array($key, $this->events);
	}

	private function generateKey(Event $event) {
		return true;
	}

	public function sense(Event $event) {
		if ($this->validEvent($event->getKey())) {
			$this->events[$this->generateKey($event)] = $event;
			return true;
		}
		return false;
	}

}