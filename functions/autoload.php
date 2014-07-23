<?php
/**
 *
 *  Autoloader for currently not loaded classes
 *
 * @copyright	2014 Unterhaltungsbox.com
 * @package	    com.unterhaltungsbox.udfw
 * @subpackage	functions
 * @category 	UDFrameWork
 * @version     1.0
 * @author      Thomas2500
 *
 */

// Hack protection
if (!defined("FW_PATH"))
	exit(file_get_contents("../pages/hack.txt"));


spl_autoload_register(function ($className)
{
	if (strpos($className, 'UDFrameWork\\') !== false) // UDFrameWork/somedir/someclass to somedir/someclass
		$className = str_replace('UDFrameWork/', '', str_replace('\\', DIRECTORY_SEPARATOR, $className));

	if (file_exists(FW_PATH . $className . '.class.php')) // UDFrameWork/somedir/someclass to file
	{
		return require_once(FW_PATH . $className . '.class.php');
	}

	if (strpos($className, '/') !== false) //
		$className = substr($className, strrpos($className, '/')+1);

	// Classes defined by enduser
	if (file_exists(FW_PATH . 'userclass/' . $className . '.class.php')) // userclassess
	{
		require_once(FW_PATH . 'userclass/' . $className . '.class.php');
	}
    elseif (defined("SUBCLASSDIR"))
    {
        if (strpos(SUBCLASSDIR, ":") === false)
        {
            if (file_exists(SUBCLASSDIR . $className . '.class.php'))
            {
        		require_once(SUBCLASSDIR . $className . '.class.php');
        	}
        }
        else
        {
            $m = explode(":", SUBCLASSDIR);
            foreach($m as $dir)
            {
                if (file_exists($dir . $className . '.class.php'))
                {
            		require_once($dir . $className . '.class.php');
            	}
            }
        }
	}
});
