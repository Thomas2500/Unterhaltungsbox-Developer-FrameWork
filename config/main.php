<?php

/**
 *
 *  Main configuration for ther configurations
 *
 * @copyright	2014 Unterhaltungsbox.com
 * @package	    com.unterhaltungsbox.udfw
 * @subpackage	settings
 * @category 	UDFrameWork
 * @version     1.0
 * @author      Thomas2500
 *
 */

// Hack protection
if (!defined('FW_PATH'))
    exit(file_get_contents('../pages/hack.txt'));

// Which config files should be automatically loaded?
$load_config = array(
    'base',
    'database',
    'memorycached',
    'token'
);




foreach ($load_config as $line)
{
    require_once(FW_PATH . 'config/' . $line . '.php');
}

unset($load_config);
