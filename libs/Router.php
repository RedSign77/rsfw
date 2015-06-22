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
			// Pre-process page
			$this->page = $this->router[$request->getController()];
			$this->page['params'] = $this->preProcess($request);
			// Page generator if available
			return;
		}
		$request->reset();
	}

	/**
	 * Process URL parameters and GET, POST AND FILES parameters
	 *
	 * @param Request $request
	 * @return array
	 */
	private function preProcess(Request $request)
	{
		$ret = array(
			'get' => array(),
			'post' => array(),
			'files' => array(),
			'url' => $request->getUrl(),
		);
		if (isset($_GET) && count($_GET) >0) {
			$_params = $_GET;
			unset($_params['req']);
			$ret['get'] = $_params;
		}
		if (isset($_POST) && count($_POST) >0) {
			$_params = $_POST;
			$ret['post'] = $_params;
		}
		if (isset($_FILES) && count($_FILES) >0) {
			$_params = $_FILES;
			$ret['files'] = $_params;
		}
		return $ret;
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