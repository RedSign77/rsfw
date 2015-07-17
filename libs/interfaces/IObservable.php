<?php

/**
 * Interface IObservable
 */
interface IObservable
{

	public function attach(IObserver $observer);

	public function notifyObservers();

}