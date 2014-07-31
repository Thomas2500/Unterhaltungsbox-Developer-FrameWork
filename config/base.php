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


// Codecs for user class
//     You can create your own codecs with using
//     echo UDFrameWork\system\Utils::createCodec();
define("USER_C_USERID",       "pb2tOmwXS4qj0GDVLCW5YUu3kcaP1BgyARnZfQ8JhNelEsTKxMrz6dFvo9HiI7");
define("USER_C_SESSIONID",    "4T8RHGJPMOVqm2wU0yf5bhvlKikBrDaeCu9QFdgopYEX6nZAL1xzcNW3IS7tjs");
