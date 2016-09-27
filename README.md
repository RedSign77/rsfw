# RS Framework 2

#### Framework version: 0.6
#### PHP Version: 5.6

Programable PHP framework for fast, flexible and simple projects.

The framework contains a basic sample with a basic user handling and the neccessary logging features with observer pattern.

```

$request = Request::getInstance();
$cache = SessionCache::getInstance();
$router = Router::getInstance();
$router->process($request);
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
```
