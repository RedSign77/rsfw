<?php

/**
 * Interface IObservable
 */
interface IObservable
{

	public function attach(Observer $observer);

	public function notifyObservers();

}