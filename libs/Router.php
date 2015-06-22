<?php

/**
 * Class Router
 */
class Router extends Singleton
{

	private $router = array();
	private $page = array();

	/**
	 * Construct
	 */
	protected function __construct() {
		$this->readRoutingArray();
	}

	/**
	 * Read routing array from config
	 *
	 * @throws Exception
	 */
	private function readRoutingArray() {
		$this->router = Core::readConfigJSON("routing");
	}

	/**
	 * Process request
	 *
	 * @param Request $request
	 */
	public function process(Request $request) {
		if (isset($this->router[$request->getController()])) {
			$this->page = $this->router[$request->getController()];

			return;
		}
		$request->reset();
	}

	/**
	 * For dump or error monitoring
	 *
	 * @return string
	 */
	public function __toString() {
		$ret = "<pre>";
		$ret .= "<b>" . get_class($this) . " class</b>";
		$ret .= "<br>Page: <br>" . var_export($this->page, true);
		//$ret .= "<br>Router array: <br>" . var_export($this->router, true);
		$ret .= "</pre>";
		return $ret;
	}

}