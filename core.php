<?php
/**
 *
 *  Main initiator of Unterhaltungsbox Developer FrameWork
 *
 * @copyright	2014 Unterhaltungsbox.com
 * @package	    com.unterhaltungsbox.udfw
 * @subpackage	core
 * @category 	UDFrameWork
 * @version     1.0
 * @author      Thomas2500
 *
 */

// IN DEVELOPMENT - Testing execution time
$time = microtime(true);

// Define current dir as firmware base path
if (!defined('FW_PATH'))
    define('FW_PATH', realpath(dirname(__FILE__)) . '/');

// Set UTF8 as default charset
header('Content-Type: text/html; charset=utf-8');

// Output framework name
header('X-Framework: UDFrameWork');

// Autoloader for php classes
require_once(FW_PATH . 'functions/autoload.php');

// Load settings to load
require_once(FW_PATH . 'config/main.php');





$runtime = microtime(true) - $time;

if ($runtime*1000*1000 < 100)
    echo $runtime*1000*1000 . ' µs';
else if ($runtime*1000 < 100)
    echo $runtime*1000 . ' ms';
else
    echo $runtime*1000 . ' s';
