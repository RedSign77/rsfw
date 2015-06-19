<?php
/**
 * RS Framework index
 * Set error reporting
 */
//ini_set('error_reporting', E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('error_reporting', E_ALL);
ini_set('error_log', 'files/log/error_log_' . date("Ym", time()) . '.log');
// Default settings
ob_start();
include_once 'sys/constants.php';
include_once 'sys/core.php';
spl_autoload_extensions('.php');
spl_autoload_register('loadClasses');
// Show page with routing and data
include_once 'sys/generator.php';
ob_end_flush();
