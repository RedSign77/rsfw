<?php

/**
 * Class Observable
 */
abstract class Observable implements IObservable
{

	protected $observers = array();

	protected function __construct() {
		$this->autoAttach();
	}

	public function attach(IObserver &$observer) {
		$this->observers[] = $observer;
	}

	public function notifyObservers($action) {
		foreach ($this->observers as $observer) {
			$observer->update($this, $action);
		}
	}

	private function autoAttach() {
		$observers = Core::readConfigJSON(get_class($this));
		if ($observers && count($observers) > 0) {
			foreach ($observers as $observer) {
				$this->attach(new $observer());
			}
		}
	}
}