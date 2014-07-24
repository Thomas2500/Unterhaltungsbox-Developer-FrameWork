<?php

/**
 *
 * @copyright	2014 Unterhaltungsbox.com
 * @package	    com.unterhaltungsbox.udfw
 * @subpackage	system
 * @category 	UDFrameWork
 * @version     2.1
 * @author      Thomas2500
 *
 */

namespace UDFrameWork\system;
use Memcached;

// Hack protection
if (!defined("FW_PATH"))
    exit(file_get_contents("../pages/hack.txt"));

/*
Notes:
    Memcache has size limits for key and value.
        key supports a maximum length from about 250 chars
        value supports a maximum size of 1MB - 42 bytes
            on newer versions, it's configurable

    The maximum timeout (not 0) can be 2,592,000 secounds (30 days)
        Above it will be a unix timestamp


*/

class MemoryCached
{
    // Memcached object
    private $session;

    // Initialisize connection to memcache server
    // Host can be a UNIX domain socket, but port must be 0
    /**
     *	Connects to a given server
     *	@param 	array 	$connection
     */
    public function __construct($connection)
    {
        /* $connection
        {
            [0] => { host: "localhost", port: 11211, weight: 1 },
            [1] => { socket: "unix:///path/to/memcached.sock", weight: 2 },
            ...
        }
        */
        $this->session = new Memcached;
        foreach ($connection as $line)
        {
            if (isset($line["host"])) // UPD/IP connection
            {
                if (!isset($line["port"]))
                    $line["port"] = 11211;

                if (!isset($line["weight"]))
                    $line["weight"] = 1;

                $this->session->addServer($line["host"], $line["port"], $line["weight"]);
            }
            else if (isset($line["socket"]))
            {
                if (!isset($line["weight"]))
                    $line["weight"] = 2;

                $this->session->addServer($line["socket"], 0, $line["weight"]);
            }
        }
    }

    /**
     *	Gets the value to a stored key.
     *	@param 	string 	$key
     *	@return mixed
     */
    public function get($key)
    {        return $this->session->get($key);
    }

    /**
     *	Returns the result code of the last operation.
     *	@return int
     */
    public function getStatus()
    {
        /*
            00 = MEMCACHED_SUCCESS
            01 = MEMCACHED_FAILURE
            02 = MEMCACHED_HOST_LOOKUP_FAILURE // getaddrinfo() and getnameinfo() only
            03 = MEMCACHED_CONNECTION_FAILURE
            04 = MEMCACHED_CONNECTION_BIND_FAILURE // DEPRECATED see MEMCACHED_HOST_LOOKUP_FAILURE
            05 = MEMCACHED_WRITE_FAILURE
            06 = MEMCACHED_READ_FAILURE
            07 = MEMCACHED_UNKNOWN_READ_FAILURE
            08 = MEMCACHED_PROTOCOL_ERROR
            09 = MEMCACHED_CLIENT_ERROR
            10 = MEMCACHED_SERVER_ERROR // Server returns "SERVER_ERROR"
            11 = MEMCACHED_ERROR // Server returns "ERROR"
            12 = MEMCACHED_DATA_EXISTS
            13 = MEMCACHED_DATA_DOES_NOT_EXIST
            14 = MEMCACHED_NOTSTORED
            15 = MEMCACHED_STORED
            16 = MEMCACHED_NOTFOUND
            17 = MEMCACHED_MEMORY_ALLOCATION_FAILURE
            18 = MEMCACHED_PARTIAL_READ
            19 = MEMCACHED_SOME_ERRORS
            20 = MEMCACHED_NO_SERVERS
            21 = MEMCACHED_END
            22 = MEMCACHED_DELETED
            23 = MEMCACHED_VALUE
            24 = MEMCACHED_STAT
            25 = MEMCACHED_ITEM
            26 = MEMCACHED_ERRNO
            27 = MEMCACHED_FAIL_UNIX_SOCKET // DEPRECATED
            28 = MEMCACHED_NOT_SUPPORTED
            29 = MEMCACHED_NO_KEY_PROVIDED // Deprecated. Use MEMCACHED_BAD_KEY_PROVIDED!
            30 = MEMCACHED_FETCH_NOTFINISHED
            31 = MEMCACHED_TIMEOUT
            32 = MEMCACHED_BUFFERED
            33 = MEMCACHED_BAD_KEY_PROVIDED
            34 = MEMCACHED_INVALID_HOST_PROTOCOL
            35 = MEMCACHED_SERVER_MARKED_DEAD
            36 = MEMCACHED_UNKNOWN_STAT_KEY
            37 = MEMCACHED_E2BIG
            38 = MEMCACHED_INVALID_ARGUMENTS
            39 = MEMCACHED_KEY_TOO_BIG
            40 = MEMCACHED_AUTH_PROBLEM
            41 = MEMCACHED_AUTH_FAILURE
            42 = MEMCACHED_AUTH_CONTINUE
            43 = MEMCACHED_PARSE_ERROR
            44 = MEMCACHED_PARSE_USER_ERROR
            45 = MEMCACHED_DEPRECATED
            46 = MEMCACHED_IN_PROGRESS
            47 = MEMCACHED_SERVER_TEMPORARILY_DISABLED
            48 = MEMCACHED_SERVER_MEMORY_ALLOCATION_FAILURE
            49 = MEMCACHED_MAXIMUM_RETURN // Always add new error code before
            11 = MEMCACHED_CONNECTION_SOCKET_CREATE_FAILURE = MEMCACHED_ERROR
        */
        return $this->session>getResultCode();
    }

    /**
     *	Store an item
     *	@param 	string 	$key
     *	@param 	mixed 	$value
     *	@param 	int 	$timeout
     *	@return bool
     */
    public function set($key, $value, $timeout = 0)
    {
        return $this->session->set($key, $value, $timeout);
    }

    /**
     *	Add an item under a new key
     *	@param 	string 	$key
     *	@param 	mixed 	$value
     *	@param 	int 	$timeout
     *	@return bool
     */
    public function add($key, $value, $timeout = 0)
    {
        return $this->session->add($key, $value, $timeout);
    }

    /**
     *	Replaces a stored key:value pair with an other value
     *	@param 	string 	$key
     *	@param 	mixed 	$value
     *	@param 	int 	$timeout
     *	@return bool
     */
    public function replace($key, $value, $timeout = 0)
    {
        return $this->session->replace($key, $value, $timeout);
    }

    /**
     *	Replaces a stored key:value pair with an other value
     *	@param 	string 	$key
     *	@param 	int 	$delay
     *	@return bool
     */
    public function delete($key, $delay = 0)
    {
        return $this->session->replace($key, $delay);
    }

    /**
     *	Set a new expiration on an item
     *	@param 	string 	$key
     *	@param 	int 	$expiration
     *	@return bool
     */
    public function touch($key, $expiration = 0)
    {
        return $this->session->touch($key, $expiration);
    }
}
