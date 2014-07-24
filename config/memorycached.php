<?php

/**
 *
 *  Configuration for memcache(d) connections
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

$MEMCONFIG = array();

//////////////////////////////////////////////////////////////////////////
//////                    Required settings                         //////
//////////////////////////////////////////////////////////////////////////

$MEMCONFIG[] = array(
    "host" => "localhost",
    "port" => 11211
);










$MEMC = new \UDFrameWork\system\MemoryCached($MEMCONFIG);

unset($MEMCONFIG);
