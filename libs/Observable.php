<?php

/**
 * Class Observable
 */
abstract class Observable implements IObservable
{

	protected $observers = array();

	public function attach(IObserver $observer) {
		$this->observers[] = $observer;
	}

	public function notifyObservers($action) {
		foreach ($this->observers as $observer) {
			$observer->update($this, $action);
		}
	}
}