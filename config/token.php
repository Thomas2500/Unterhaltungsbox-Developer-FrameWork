<?php

/**
 *
 *  Configuration for token handling
 *
 * @copyright	2014 Unterhaltungsbox.com
 * @package	    com.unterhaltungsbox.udfw
 * @subpackage	config
 * @category 	UDFrameWork
 * @version     1.0
 * @author      Thomas2500
 *
 */

// Hack protection
if (!defined("FW_PATH"))
    exit(file_get_contents("../pages/hack.txt"));

$CSA = array();

//////////////////////////////////////////////////////////////////////////
//////                    Required settings                         //////
//////////////////////////////////////////////////////////////////////////

// Type of the storage of the token data
//     Can be 'sql' or 'session'
$CSA["type"] = "sql";

//// ------ Required settings if using 'sql' ------ ////

// Default database to use. If not set, it uses the default
//   database from $SQL defined in database.php
$CSA["database"] = "udfw";

// Default table to use. If not set, it uses "token" as table
$CSA["table"] = "token";

// User identification. Will be used to map tokens to users.
//     uid can be set afterwards with $TOKEN->setUID( ... )
//     uid can have a maximum length of 32 chars
//
//     "ip" will use the ip of the user.
//         ATTENTION! If more then one user uses the token functionality,
//           it can cause mistakes. (Recreation of tokens while viewing)
//
//     "cookie" will generate a cookie and sends it to the user.
//         ATTENTION! The user must have enabled cookies.
//         Otherwise it will create a new uid on every visit
$CSA["uid"] = "";



$TOKEN = new UDFrameWork\system\Token($CSA);

unset($CSA);
