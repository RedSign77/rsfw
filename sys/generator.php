<?php
if (!isset($_SESSION['core']) || $_SESSION['core'] != 1) {
	die("Core not loaded!");
}
$request = Request::getInstance();
$cache = SessionCache::getInstance();
$router = Router::getInstance();
$router->process($request);
echo $router;
/**
 * RS framework processing functionality
 */
/**
 * Generate view for data
 */
$object = $router->getTemplate();
$view = $object::getInstance();
/**
 * Run process files
 */
$processes = $router->getProcess();
if (count($processes) > 0) {
	foreach ($processes as $process) {
		if (is_executable('process/' . $process)) {
			include_once 'process/' . $process;
		}
	}
}
/**
 * Make view
 */
$view->show();
