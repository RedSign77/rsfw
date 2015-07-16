<?php

/**
 * Class Observable
 */
abstract class Observable implements IObservable
{

	protected $observers = array();

	public function attach(Observer $observer) {
		$this->observers[] = $observer;
	}

	public function notifyObservers() {
		foreach ($this->observers as $observer) {
			$observer->update();
		}
	}
}