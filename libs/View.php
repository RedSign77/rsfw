<?php

/**
 * Class View
 */
class View extends Singleton
{

	private static $views;
	private static $blocks = "Blocks";
	private static $block_before = "{{";
	private static $block_after = "}}";
	protected $javascripts = array();
	protected $css = array();
	protected $author = "RedSign";
	protected $keywords = "";
	protected $description = "";


	public function __construct() {
	}

	/**
	 * Parse block file and fill data
	 *
	 * @param      $block
	 * @param null $data
	 * @return mixed
	 */
	private function parseBlock($block, $data = null) {
		if (is_array($data) && count($data) > 0) {
			foreach ($data as $key => $val) {
				$block = str_replace(self::$block_before . $key . self::$block_after, $val, $block);
			}
		}

		return $block;
	}

	/**
	 * Get block in html
	 *
	 * @param      $blockName
	 * @param null $data
	 * @return string
	 * @throws Exception
	 */
	public function getBlock($blockName, $data = null) {
		$block = file_get_contents(Core::themeUrl() . self::$blocks . "/" . $blockName . ".html");
		if ($block !== false) {
			return $this->parseBlock($blockName, $data);
		}
		throw new Exception("Block: " . $blockName . ".html not defined.");
	}

	/**
	 * Add RS block fulfilled with data
	 *
	 * @param $blockName
	 * @param $data
	 * @throws Exception $exception
	 * @return string $block
	 */
	public function addBlock($blockName, $data = null) {
		$block = file_get_contents(Core::themeUrl() . self::$blocks . "/" . $blockName . ".html");
		if ($block !== false) {
			$this->addView('block_' . $blockName, $this->parseBlock($block, $data));
		}
		else {
			throw new Exception("Block: " . $blockName . ".html not defined.");
		}
	}

	/**
	 * Add html to View
	 *
	 * @param $view
	 * @param $data
	 */
	public function addView($view, $data) {
		self::$views[$view] = $data;
	}

	/**
	 * Add JS file
	 *
	 * @param $jsPath
	 */
	public function addJs($jsPath)
	{
		$this->javascripts[] = $jsPath;
	}

	/**
	 * Add CSS file
	 *
	 * @param $cssPath
	 */
	public function addCss($cssPath)
	{
		$this->css[] = $cssPath;
	}

	/**
	 * Returns the main html data
	 *
	 * @return string
	 */
	public function show() {
		if (method_exists($this, '_beforeShow')) {
			$this->_beforeShow();
		}
		if (count(self::$views) > 0) {
			if (method_exists($this, '_afterShow')) {
				$this->_afterShow();
			}
			echo implode("\n", self::$views);

			return;
		}
		echo "No view loaded.";
	}

	/**
	 * Get CSS files in a string
	 *
	 * @return string
	 */
	protected function getCSS() {
		$ret = "";
		if (count($this->css) > 0) {
			foreach ($this->css as $css) {
				$ret .= '<link rel="stylesheet" href="' . Core::themeUrl() . $css . '"></link>';
			}
		}

		return $ret;
	}

	/**
	 * Get Js files in a string
	 *
	 * @return string
	 */
	protected function getJavascripts() {
		$ret = "";
		if (count($this->javascripts) > 0) {
			foreach ($this->javascripts as $js) {
				$ret .= '<script type="text/javascript" src="' . Core::themeUrl() . $js . '"></script>';
			}
		}

		return $ret;
	}

}