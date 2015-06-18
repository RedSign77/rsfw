<?php
/**
 * Interface IEvent
 */
interface IEvent
{
	public function getKey();

	public function dispatch();

	public function active();

}