<?php

/**
 *
 * @copyright	2014 Unterhaltungsbox.com
 * @package	    com.unterhaltungsbox.udfw
 * @subpackage	system
 * @category 	UDFrameWork
 * @version     1.0.1
 * @author      Thomas2500
 *
 */

namespace UDFrameWork\system;

// Hack protection
if (!defined("FW_PATH"))
    exit(file_get_contents("../pages/hack.txt"));

class Time{

    static public function isTimezoneValid($timezone)
    {
        try
        {
            new DateTimeZone($timezone);
        }
        catch(Exception $e)
        {
            return false;
        }
        return true;
    }

    /**
     * out ( (string) $format, (int) $timestamp, (string) $timezone )
     *
     *
     */
    public static function out($format = 'Y-m-d H:i:sP', $timestamp, $timezone = 'UTC')
    {
        if (empty($format))
            $format = 'Y-m-d H:i:sP';
        if (!Time::isTimezoneValid($timezone))
            return date($format, $timestamp) . " UTC";

        $dt = new DateTime();
        $dt->setTimestamp($timestamp);
        $dt->setTimezone(new DateTimeZone($timezone));
        return $dt->format($format);
    }


    public static function formatSecoundsToText($secounds)
    {
        if ($secounds < 60)
        {
            return $secounds . " secounds";
        }
        else if (floor($secounds/60) == 1)
        {
            return floor($secounds/60) . " minute";
        }
        else if ($secounds/60 < 60)
        {
            return floor($secounds/60) . " minutes";
        }
        else if ($secounds/(60*60) < 24)
        {
            return floor($secounds/(60*60)) . " hours";
        }
        else if ($secounds/(60*60*24) < 7)
        {
            return floor($secounds/(60*60*24)) . " days";
        }
        else if (floor($secounds/(60*60*24*7)) == 1)
        {
            return floor($secounds/(60*60*24*7)) . " week";
        }
        else if ($secounds/(60*60*24*7) < 4)
        {
            return floor($secounds/(60*60*24*7)) . " weeks";
        }
        else if (floor($secounds/(60*60*24*7*4)) == 1)
        {
            return floor($secounds/(60*60*24*7*4)) . " month";
        }
        else if ($secounds/(60*60*24*7*4) < 12)
        {
            return floor($secounds/(60*60*24*7*4)) . " months";
        }
        else if (floor($secounds/(60*60*24*7*4*12)) == 1)
        {
            return floor($secounds/(60*60*24*7*4*12)) . " year";
        }
        else
        {
            return floor($secounds/(60*60*24*7*4*12)) . " years";
        }

    }

}
