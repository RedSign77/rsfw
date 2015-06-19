<?php

/**
 * Class Router
 */
class Router extends Singleton
{

	private $router = array();
	private $page = array();

	protected function __construct() {
		$this->readRoutingArray();
	}

	private function readRoutingArray() {
		$this->router = Core::readConfigJSON("routing");
	}

	public function process(Request $request) {
		if (isset($this->router[$request->getController()])) {
			$this->page = $this->router[$request->getController()];

			return;
		}
		$request->reset();
	}

}