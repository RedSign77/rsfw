<?php
if (!isset($_SESSION['core']) || $_SESSION['core'] != 1) {
	die("Core not loaded!");
}
$request = Request::getInstance();
$cache = SessionCache::getInstance();
$router = Router::getInstance();
$router->process($request);
echo $router;
$user = new RS_User();
$user->attach(new RS_Observer_Log());
$user->login('signred@gmail.com', 'PhoeniX1');
