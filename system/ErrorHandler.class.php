<?php

/**
 *
 *  Handles all errors
 *
 * @copyright	2014 Unterhaltungsbox.com
 * @package	    com.unterhaltungsbox.udfw
 * @subpackage	system
 * @category 	UDFrameWork
 * @version     1.0
 * @author      Thomas2500
 *
 */

namespace UDFrameWork\system;

// Hack protection
if (!defined("FW_PATH"))
    exit(file_get_contents("../pages/hack.txt"));

class ErrorHandler
{

    public function __construct()
    {

        ini_set( 'display_errors', 1 );
        error_reporting( -1 );
        set_error_handler( array( '\UDFrameWork\system\ErrorHandler', 'captureNormal' ), E_ALL );
        set_exception_handler( array( '\UDFrameWork\system\ErrorHandler', 'captureException' ) );
        register_shutdown_function( array( '\UDFrameWork\system\ErrorHandler', 'captureShutdown' ) );

    }

    public static function captureNormal( $number, $message, $file, $line )
    {

    }

    public static function captureException( $exception )
    {

    }

    public static function captureShutdown( )
    {
        $error = error_get_last( );
    }
}
