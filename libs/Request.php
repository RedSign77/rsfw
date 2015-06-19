<?php

/**
 * Class Request
 */
class Request extends Singleton
{

	private $controller = RS_HOME;
	private $request = null;
	private $url = array();

	/**
	 * Constructor
	 */
	protected function __construct() {
		$this->request = isset($_GET['req']) ? $_GET['req'] : null;
		if (!is_null($this->request)) {
			$this->url = explode("/", $this->request);
			$this->controller = current($this->url);
		}
	}

	/**
	 * Get URL
	 *
	 * @return array
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * Get request
	 *
	 * @return null
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * Get Controller for routing
	 *
	 * @return mixed|string
	 */
	public function getController() {
		return $this->controller;
	}

	/**
	 * Reset if neccessary
	 */
	public function reset()
	{
		$this->controller = RS_HOME;
		$this->request = null;
		$this->url = array();
	}

	public function __toString()
	{
		$ret = "";
		$ret .= "<br>Request: ".$this->request;
		$ret .= "<br>URL: ".implode(", ", $this->url);
		$ret .= "<br>Controller: ".$this->controller;
		return $ret;
	}

}