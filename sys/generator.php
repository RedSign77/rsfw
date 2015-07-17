<?php
if (!isset($_SESSION['core']) || $_SESSION['core'] != 1) {
	die("Core not loaded!");
}
$request = Request::getInstance();
$cache = SessionCache::getInstance();
$router = Router::getInstance();
$router->process($request);
echo $router;
echo $request;
/**
 * RS framework processing functionality
 */

/**
 * Run process files
 */

/**
 * Make view
 */
$object = $router->getTemplate();
$view = $object::getInstance();
$view->show();
