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

// Hack protection
if (!defined("FW_PATH"))
    exit(file_get_contents("../pages/hack.txt"));

class Cookie
{

    /**
     *	Sets a cookie
     *	@param 	string 	$name
     *	@param 	string 	$value
     *	@param 	int 	$expire
     *	@param 	bool 	$crossdomain
     *	@param 	bool 	$secure
     *	@param 	bool 	$httponly
     */
    public static function set($name, $value, $expire = null, $crossdomain = false, $secure = false, $httponly = false)
    {
        // If expire is not defined, the cookie expires in 1 year
        if (empty($expire))
            $expire = time() + 3600 * 24 * 365;

        // Cookie will only be sent back by the browser when using an https connection
        if ($secure !== false)
            $secure = true;

        // Cookie will only available by php and the webserver. JavaScript and other client based applications can't access it.
        // HttpOnly is available for most common browsers. See https://www.owasp.org/index.php/HttpOnly#Browsers_Supporting_HttpOnly
        if ($httponly !== false)
            $httponly = true;

        if ($crossdomain !== false)
            if (defined("COOKIE_DOMAIN"))
                $crossdomain = COOKIE_DOMAIN;
            else
                throw new \Exception('Cookie::set - COOKIE_DOMAIN is not defined');
        else
            $crossdomain = null;

        setcookie($name, $value, $expire, "/", $crossdomain, $secure, $httponly);

        $_COOKIE[$name] = $value;
    }

    /**
     *	Gets a cookie
     *	@param 	string 	$name
     *	@return string
     */
    public static function get($name)
    {
        if (isset($_COOKIE[$name]))
            return $_COOKIE[$name];

        return;
    }

    /**
     *	Checks if a cookie exists
     *	@param 	string 	$name
     *	@return bool
     */
    public static function exists($name)
    {
        if (isset($_COOKIE[$name]))
            return true;

        return false;
    }

    /**
     *	Extends the expiry date of a cookie
     *	@param 	string 	$name
     *	@param 	int 	$expire
     *	@param 	bool 	$crossdomain
     *	@param 	bool 	$secure
     *	@param 	bool 	$httponly
     *	@return bool
     */
    public static function extend($name, $expire = null, $crossdomain = false, $secure = false, $httponly = false)
    {
        if (empty($_COOKIE[$name]))
            return false;

        Token::set($name, $_COOKIE[$name], $expire, $crossdomain, $secure, $httponly);
        return true;
    }

    /**
     *	Removes a cookie
     *	@param 	string 	$name
     */
    public static function remove($name)
    {
        unset($_COOKIE[$name]);
        setcookie($name, "", time()-1, "/");
    }

}
