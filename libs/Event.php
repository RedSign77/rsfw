<?php

/**
 * Class Event
 */
abstract class Event implements IEvent
{

	private $key = "";
	private $state = true;

	public function __construct($key = null) {
		if (is_null($key)) {
			$this->key = Core::generateHash(32);
		}
		else {
			$this->key = Core::postSlug($key);
		}
	}

	public function getKey() {
		return $this->key;
	}

	public function dispatch() {
	}

	public function active() {
		return $this->state;
	}
}
