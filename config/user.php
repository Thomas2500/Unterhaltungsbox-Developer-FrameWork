<?php

/**
 *
 *  Configuration for user handling
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

$BUC = array();

//////////////////////////////////////////////////////////////////////////
//////                    Required settings                         //////
//////////////////////////////////////////////////////////////////////////

// Default database to use. If not set, it uses the default
//   database from $SQL defined in database.php
$BUC["database"] = "udfw";

// Default table to use. If not set, it uses "user" as table
$BUC["table"] = "user";

//             ----------- Cookie names -----------
// Do not use context related names!

// Variable name of the user session ID (coded) [Value example: j]
$BUC["session_id"] = "fwsese";

// Variable name of the user id (coded)         [Value example: nP]
$BUC["user_id"] = "dskflj";

// Variable name of the session key.            [Value example: dskfujhkaakhgfkasdkadalkhsd]
$BUC["session_key"] = "safdfh";


// Should a session be created for guests?    (Default false)
$BUC["guest_session"] = false;



$USER = new \UDFrameWork\system\User($BUC);

unset($BUC);
