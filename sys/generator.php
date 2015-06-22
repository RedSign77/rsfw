<?php
if (!isset($_SESSION['core']) || $_SESSION['core'] != 1) {
    die("Core not loaded!");
}
$request = Request::getInstance();
$cache = SessionCache::getInstance();
$router = Router::getInstance();
$router->process($request);
echo $router;
