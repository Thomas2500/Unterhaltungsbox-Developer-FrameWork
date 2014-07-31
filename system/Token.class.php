<?php

/**
 *
 * @copyright	2014 Unterhaltungsbox.com
 * @package	    com.unterhaltungsbox.udfw
 * @subpackage	system
 * @category 	UDFrameWork
 * @version     1
 * @author      Thomas2500
 *
 */

namespace UDFrameWork\system;

use \UDFrameWork\system\Cookie as Cookie;

// Hack protection
if (!defined("FW_PATH"))
    exit(file_get_contents("../pages/hack.txt"));

class Token
{
    private $storage;
    private $database;
    private $table;
    private $uid;

    /**
     *	Formats a string into an unix timestamp
     *	@param 	array 	$settings
     *	@param 	string 	$format
     *	@return int
     */
    public function __construct($settings)
    {
        if ($settings["type"] == 'session')
        {
            // Start session if it was not started
            if (function_exists('session_status'))
                if (session_status() == PHP_SESSION_NONE)
                    session_start();
            else
                if(session_id() === '')
                    session_start();

            // Define session
            $this->storage = 1;
        }
        else if ($settings["type"] == 'sql')
        {
            global $SQL;

            // Get database for token table
            if (empty($settings["database"]))
            {
                // If database is not set by $SQL connection, we don't know the database
                if (is_null($SQL->database()))
                    throw new \Exception('Token::_construct - No database selected!');
                else
                    $this->database = $SQL->database();
            }
            else
                $this->database = $settings["database"];

            // Get the name of the table. If nothing is set, we use "token" as table name
            if (!isset($settings["table"]))
                $this->table = "token";
            else
                $this->table = $settings["table"];

            if (isset($settings["uid"]) && !empty($settings["uid"]))
                $this->setUID($settings["uid"]);

            $this->storage = 2;
        }
        else
            throw new \Exception('Token::__construct - Unknown type of storage');
    }

    /**
     *	Sets the user identification
     *	@param 	string 	$uid
     */
    public function setUID($uid)
    {
        if (empty($uid))
            throw new \Exception('Token::setUID - uid was not defined');

        // Special session storages
        //     SESSION don't use special storage
        if ($this->storage === 1)
            return $this->uid = null;

        if ($uid === 'ip')
            return $uid = $_SERVER["REMOTE_ADDR"];
        else if ($uid === 'cookie')
        {

            if (Cookie::exists("token_uid"))
                $this->uid = Cookie::get("token_uid");
            else
            {
                $uid = md5(mt_rand());
                Cookie::set("token_uid", $uid);
                $this->uid = $uid;
            }
            return;
        }
        $this->uid = $uid;
    }

    /**
     *	Gets a token based in the UID and a given name
     *	@param 	string 	$name
     *	@return string
     */
    public function get($name)
    {
        if (empty($name))
            throw new \Exception('Token::get - Name not defined');

        if (empty($this->uid))
            throw new \Exception('Token::get - UID was not set');

        if ($this->storage === 1) // Session storage
        {
            return $_SESSION["token_" . $name];
        }
        else if ($this->storage === 2) // SQL storage
        {
            global $SQL;
            return $SQL->single("SELECT `token` FROM `".$this->database."`.`".$this->table."` WHERE `name` = :name AND `uid` = :uid LIMIT 1;", array("name" => $name, "uid" => $this->uid));
        }

    }

    /**
     *	Sets a new token
     *	@param 	string 	$name
     *	@param 	int 	$type
     *	@return string
     */
    public function set($name, $type = 0)
    {
        if (empty($name))
            throw new \Exception('Token::set - name not defined');

        if (empty($this->uid))
            throw new \Exception('Token::set - UID was not set');

        if ($type === 1)                 // 1 -> sha1 (40 chars)
            $token = sha1(mt_rand());
        else if ($type === 2)            // 2 -> crc32 (8 chars)
            $token = crc32(mt_rand());
        else                             // 1 -> MD5 [Default] (32 chars)
            $token = md5(mt_rand());

        if ($this->storage === 1)
        {
            $_SESSION["token_" . $name] = $token;
            return $token;
        }
        else if ($this->storage === 2)
        {
            global $SQL;

            $SQL->query("INSERT INTO `".$this->database."`.`".$this->table."` (`uid`, `name`, `token`, `creation`) VALUES (:uid, :name, :token, NOW());",
                array("uid" => $this->uid, "name" => $name, "token" => $token));
            return $token;
        }
    }

    /**
     *	Deletes an existing token
     *	@param 	string 	$name
     *	@return bool
     */
    public function delete($name)
    {
        if (empty($name))
            throw new \Exception('Token::delete - name not defined');

        if (empty($this->uid))
            throw new \Exception('Token::delete - UID was not set');

        if ($this->storage === 1)
        {
            unset($_SESSION["token_" . $name]);
            return true;
        }
        else if ($this->storage === 2)
        {
            global $SQL;
            return $SQL->query("DELETE FROM `".$this->database."`.`".$this->table."` WHERE `name` = :name AND `uid` = :uid", array("name" => $name, "uid" => $this->uid));
        }
    }

    /**
     *	Compares a given token with the saved token. If it matches, the saved token will be removed.
     *	@param 	string 	$name
     *  @param  string  $token
     *	@return bool
     */
    public function compare($name, $token)
    {
        if ($this->get($name) == $token)
        {
            $this->delete($name);
            return true;
        }
        return false;
    }

    /**
     *	Deletes old sessions in the database. Returns amount of affectec entries
     *	@return bool
     */
    public function cleanup()
    {
        if ($this->storage !== 2)
            return false;

        global $SQL;
        return $SQL->query("DELETE FROM `".$this->database."`.`".$this->table."` WHERE `creation` < (NOW() - INTERVAL 1 WEEK)");
    }
}
