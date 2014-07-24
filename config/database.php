<?php

/**
 *
 *  Configuration for database connections
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

$SQC = array();
$SQE = array();

//////////////////////////////////////////////////////////////////////////
//////                    Required settings                         //////
//////////////////////////////////////////////////////////////////////////

// Database host.                                              (default: "localhost")
// For faster connections please use "localhost" instead of "127.0.0.1"
$SQC["host"] = "localhost";

// Database user name
//     To improve security, please don't use root!
$SQC["username"] = "user";

// Database user password
$SQC["password"] = "password";


//////////////////////////////////////////////////////////////////////////
//////                    Optional settings                         //////
//////////////////////////////////////////////////////////////////////////

// Which database driver should be used?                       (default: "mysql")
//     Usually mysql, could be sqlite, sqlite2, ...
//     For MariaDB, use the mysql driver
$SQE["driver"] = "mysql";

// On which port is the database listening?                    (default: automatic "")
//     Will be ignored if using a sqlite* driver
$SQE["port"] = "";

// In which file should the database be saved?                 (default: ":memory:")
//     Only available when using sqlite* driver,
//      will be ignored by other drivers
//     For a permanent file, use something like "/path/to/file.sqlite"
$SQE["file"] = ":memory:";

// Which database should be used by default?                   (default: none "")
$SQE["database"] = "";




// Initialisize database connection
$SQL = new \UDFrameWork\system\SQL($SQC["host"], $SQC["username"], $SQC["password"], $SQE);

unset($SQC, $SQE);
