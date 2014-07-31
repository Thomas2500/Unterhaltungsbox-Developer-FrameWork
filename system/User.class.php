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
use UDFrameWork\system\Cookie as Cookie;
use UDFrameWork\system\Utils as Utils;

// Hack protection
if (!defined("FW_PATH"))
    exit(file_get_contents("../pages/hack.txt"));

class User
{
    private $guest_session;
    private $database;
    private $table;

    // Names of the variables
    private $var_names = array();

    private $userID = 0;

    function __construct($settings)
    {
        global $SQL;

        if (empty($settings["session_id"]))
            throw new \Exception('User::__construct - session_id not defined');
        $this->var_names["session_id"] = $settings["session_id"];

        if (empty($settings["user_id"]))
            throw new \Exception('User::__construct - user_id not defined');
        $this->var_names["user_id"] = $settings["user_id"];

        if (empty($settings["session_key"]))
            throw new \Exception('User::__construct - session_key not defined');
        $this->var_names["session_key"] = $settings["session_key"];

        // Get database for user table
        if (empty($settings["database"]))
        {
            // If database is not set by $SQL connection, we don't know the database
            if (is_null($SQL->database()))
                throw new \Exception('User::_construct - No database selected!');
            else
                $this->database = $SQL->database();
        }
        else
            $this->database = $settings["database"];

        // Get the name of the table. If nothing is set, we use "user" as table name
        if (!isset($settings["table"]))
            $this->table = "user";
        else
            $this->table = $settings["table"];

        $this->guest_session = $settings["guest_session"];

        // Check if user has no session and guest_session is active
        if ($settings["guest_session"] === true && !Cookie::exists($this->var_names["session_id"]))
        {
            $this->createGuestSession();
            return;
        }
        else if ($settings["guest_session"] === false && !Cookie::exists($this->var_names["session_id"]))
            return;    // Do nothing if guest_session is of and visitor is a guest

        $userID     = Utils::StrToInt(Cookie::get($settings["user_id"]), USER_C_USERID);
        $sessionID  = Utils::StrToInt(Cookie::get($settings["session_id"]), USER_C_SESSIONID);

        $session = $SQL->bool("SELECT COUNT(*) FROM `".$this->database."`.`".$this->table."_session` WHERE `id` = :id AND `userID` = :userid AND `key` = :key",
            array("id" => $sessionID, "userid" => $userID, "key" => Cookie::get($settings["session_key"])));

        if ($session === false)
            $this->deleteSession();
        else
            $this->extendSession();
    }

    /**
     *	Creates an user session
     *	@param 	int 	$userID
     */
    public function createUserSession($userID)
    {
        global $SQL;

        $key = $this->createKey();
        $SQL->query("INSERT INTO `".$this->database."`.`".$this->table."_session` (`userID` , `key` , `lastuse` ) VALUES (:userid, :key, NOW() );",
            array("userid" => $userID, "key" => $key));

        $id = Utils::IntToStr($SQL->insert_id(), USER_C_SESSIONID);
        $userID = Utils::IntToStr($userID, USER_C_USERID);

        Cookie::set($this->var_names["session_id"],  $id);
        Cookie::set($this->var_names["user_id"],     $userID);
        Cookie::set($this->var_names["session_key"], $key);

        $this->userID = $userID;
    }

    /**
     *	Creates an guest session
     */
    public function createGuestSession()
    {
        $this->createUserSession(0);
        $this->userID = 0;
    }

    /**
     *	Extends an existing session and updates the lastuse timestamp in the database
     */
    private function extendSession()
    {
        global $SQL;

        $id     = Utils::StrToInt(Cookie::get($this->var_names["session_id"]), USER_C_SESSIONID);
        $userID = Utils::StrToInt(Cookie::get($this->var_names["user_id"]), USER_C_USERID);

        $SQL->query("UPDATE `".$this->database."`.`".$this->table."_session` SET `lastuse` = NOW() WHERE `id` = :id AND `userID` = :userid AND `key` = :key;",
            array("id" => $id, "userid" => $userID, "key" => Cookie::get($this->var_names["session_key"])));

        Cookie::extend($this->var_names["session_id"]);
        Cookie::extend($this->var_names["user_id"]);
        Cookie::extend($this->var_names["session_key"]);

        $this->userID = $userID;
    }

    /**
     *	Converts a string into a positive number of a defined codec
     *	@param 	string 	$string
     *	@param 	string 	$codec
     *	@return mixed
     */
    public function deleteSession()
    {
        global $SQL;

        $id     = Utils::StrToInt(Cookie::get($this->var_names["session_id"]), USER_C_SESSIONID);
        $userID = Utils::StrToInt(Cookie::get($this->var_names["user_id"]), USER_C_USERID);

        $SQL->query("DELETE FROM `".$this->database."`.`".$this->table."_session` WHERE `id` = :id AND `userID` = :userid AND `key` = :key;",
            array("id" => $id, "userid" => $userID, "key" => Cookie::get($this->var_names["session_key"])));

        Cookie::remove($this->var_names["session_id"]);
        Cookie::remove($this->var_names["user_id"]);
        Cookie::remove($this->var_names["session_key"]);

        $this->userID = 0;
    }

    /**
     *	Changes from guest session to user session
     *	@param 	int 	$userID
     */
    public function changeFromGuestToUser($userID)
    {
        $this->deleteSession();
        $this->createUserSession($userID);
        $this->userID = $userID;
    }

    /**
     *	Changes from user session to guest session, if user session is enabled
     */
    public function changeFromUserToGuest()
    {
        $this->deleteSession();

        if ($this->guest_session === true)
            $this->createGuestSession();

        $this->userID = 0;
    }

    /**
     *	Generates a random md5 sum
     *	@return string
     */
    public function createKey()
    {
        return md5(mt_rand());
    }

    /**
     *	Returns if current viewer is an user or a guest
     *	@return bool
     */
	public function isUser()
    {
		if ($this->userID === 0)
            return false;
		return true;
	}

    /**
     *	Returns the id of the current user
     *	@return int
     */
    public function userID()
    {
        return intval($this->userID);
    }

    public function cleanup()
    {
        global $SQL;

        // Delete user sessions that are older than 1 month and guest sessions
        // that are older that 3 days (Unused sessions!)
        return $SQL->query("DELETE FROM `".$this->database."`.`".$this->table."_session` WHERE (`lastuse` < (NOW() - INTERVAL 1 WEEK) AND `userID` = 0) OR `lastuse` < (NOW() - INTERVAL 1 MONTH)");
    }

}
