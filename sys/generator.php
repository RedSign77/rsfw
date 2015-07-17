<?php
if (!isset($_SESSION['core']) || $_SESSION['core'] != 1) {
	die("Core not loaded!");
}
try {
	$request = Request::getInstance();
	$cache = SessionCache::getInstance();
	$router = Router::getInstance();
	$router->process($request);
	echo $router;
	/**
	 * Check protection and user actions
	 */

	/**
	 * Run process files
	 */

	/**
	 * Make view
	 */
	$view = Singleton::getClass($router->getTemplate());
	$view->show();
} catch (Exception $e) {
	echo "Generator exception: ".$e->getMessage();
	die();
}