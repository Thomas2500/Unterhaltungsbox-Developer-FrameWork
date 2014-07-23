<?php

/**
 *
 *  Shutdown (exit, die, timeout, finish) handler
 *
 * @copyright	2014 Unterhaltungsbox.com
 * @package	    com.unterhaltungsbox.udfw
 * @subpackage	system
 * @category 	UDFrameWork
 * @version     1.0
 * @author      Thomas2500
 *
 */

// Hack protection
if (!defined("FW_PATH"))
    exit(file_get_contents("../pages/hack.txt"));


namespace UDFrameWork\lib\system;

class Shutdown
{

    static public function Finish()
    {
        global $SQL;

        if (function_exists("fastcgi_finish_request"))
            fastcgi_finish_request();
    }

}
