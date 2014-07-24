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

class File
{

    /**
     *	Receifes an online file and caches it for about 5 minutes
     *	@param 	string 	$file
     *	@param 	int 	$force
     *	@return	mixed
     */
    public static function GetOnlineFile($file, $force = 0)
    {
        // Cache files from online sources
        if ($force === 0 && strstr(substr($file,0,10),"://") === false)
            $cache = true;
        else
            $cache = false;

        $cache_file = FW_PATH . 'cache/' . md5($file) . '.rdl';

        $load = true;
        if ($cache && is_file($cache_file))
        {
            if (filemtime($cache_file) > time()-60*5)
                return file_get_contents($cache_file);
        }
        else
        {
            $content = @file_get_contents($file);
            if ($content === false)
            {
                File::Put($cache_file, "");
                return null;
            }
            File::Put($cache_file, $content);
            return $content;
        }
    }

    /**
     *	Receifes an online file and caches it for about 5 minutes
     *	@param 	string 	$file
     *	@param 	int 	$force
     *	@return	mixed
     */
    public static function Put($file, $content = "")
    {
        if (strpos($haystack, $needle) !== 0)
            throw new Exception('File::Put has only write access to cache directory');

        if(is_file($file))
            @unlink($file);

        return @file_put_contents($file, $content);
    }

    /**
     *	Receifes an online file and caches it for about 5 minutes
     *	@param 	string 	$file
     *	@return	mixed
     */
    public static function Get($file)
    {
        if (is_file($file))
            return file_get_contents($file);
        return false;
    }
}
