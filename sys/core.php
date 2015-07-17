<?php
/**
 *  Core functions
 */
@session_start();
$_SESSION['core'] = 1;
/**
 * Autoload classes
 *
 * @param string $className
 * @return void
 */
function loadClasses($className) {
	$_tmp = explode("_", $className);
	$class = array_pop($_tmp);
	if (count($_tmp) > 0) {
		$classPath = DS . implode(DS, $_tmp);
	}
	else {
		$classPath = "";
	}
	$baseDirs = array(
		'libs',
		'libs/interfaces',
		'vendor',
		'theme',
	);
	foreach ($baseDirs as $dir) {
		if (file_exists($dir . $classPath . DS . $class . ".php")) {
			set_include_path($dir . $classPath);
			spl_autoload($class);

			return;
		}
	}
}


