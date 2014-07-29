<?php

/**
 *
 *  Base parameters for all files
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


// Main domain. Used for crossdomain cookies.
//     Don't use a dot in front of the string. It will be added automatically
//     Example: uframework.dp.loc
//         If using cookies, the cookies will be available on uframework.dp.loc
//         and all subdomains of udframework.dp.loc
define("COOKIE_DOMAIN", "udframework.dp.loc");
