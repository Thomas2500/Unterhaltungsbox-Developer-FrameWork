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

    /**
     *	Checks if a given timezone like Europe/Vienna is valid
     *	@param 	string 	$timezone
     *	@return bool
     */
    public static function isTimezoneValid($timezone)
    {
        try
        {
            new \DateTimeZone($timezone);
        }
        catch(\Exception $e)
        {
            return false;
        }
        return true;
    }

    /**
     *	Checks if a given time is valid
     *	@param 	string 	$time
     *	@param 	string 	$format
     *	@return bool
     */
    public static function isTimeValid($time, $format = null)
    {
        // For format, use http://php.net/manual/en/datetime.createfromformat.php#refsect1-datetime.createfromformat-parameters
        try
        {
            if (is_null($format))
            {
                $d = new \DateTime($time);
                if ($d === false)
                    return false;
            }
            else
                if (\DateTime::createFromFormat($format, $time) === false)
                    return false;
        }
        catch (\Exception $e)
        {
            return false;
        }
        return true;
    }

    /**
     *	Converts an unix timestamp in an user friendly time depending on the given timezone
     *	@param 	int 	$timestamp
     *	@param 	string 	$format
     *	@param 	string 	$timezone
     *	@return string
     */
    public static function out($timestamp, $format = null, $timezone = 'UTC')
    {
        if (empty($format))
            $format = 'Y-m-d H:i:sP';

        if (!Time::isTimezoneValid($timezone))
            return date($format, $timestamp) . " UTC";

        $dt = new \DateTime();
        $dt->setTimestamp($timestamp);
        $dt->setTimezone(new \DateTimeZone($timezone));
        return $dt->format($format);
    }

    /**
     *	Formats secounds into a nice user friendly format
     *	@param 	int 	$secounds
     *	@return string
     */
    public static function formatSecoundsToText($secounds)
    {
        if ($secounds <= 0)
            return '0 seconds';

        $timetable = array(
            12 * 30 * 24 * 60 * 60  =>  'year',
            30 * 24 * 60 * 60       =>  'month',
            24 * 60 * 60            =>  'day',
            60 * 60                 =>  'hour',
            60                      =>  'minute',
            1                       =>  'second'
        );

        foreach ($timetable as $secs => $string)
        {
            $tg = $secounds / $secs;
            if ($tg >= 1)
            {
                $r = round($tg);
                return $r . ' ' . $string . ($r > 1 ? 's' : '');
            }
        }
    }

    /**
     *	Formats a string into an unix timestamp
     *	@param 	string 	$time
     *	@param 	string 	$format
     *	@return int
     */
    public static function formatStringToTimestamp($time, $format = null)
    {
        if (is_null($format))
            $date = new \DateTime($time);
        else
            $date = \DateTime::createFromFormat($format, $time);

        if ($date === false)
            throw new \Exception('Invalid time, format combination');

        return $date->getTimestamp();
    }

}
