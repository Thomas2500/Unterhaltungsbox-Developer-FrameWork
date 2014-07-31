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

class Utils
{

    /**
     *	Converts a positive number into a string of a defined codec
     *	@param 	int 	$number
     *	@param 	string 	$codec
     *	@return string
     */
    public static function IntToStr($number, $codec)
    {
        if (empty($number))
            $number = 0;
        if (empty($codec))
            throw new Exception('Utils::IntToStr - No codec defined');

        if($number <= 0)
            return substr($codec, 0, 1);

        $chars = str_split($codec);
        $length = count($chars);

        $places = 0;
        while($number >= pow($length, $places))
            $places++;

        $string = "";
        for($i = $places; $i > 0; $i--)
        {
            $index   = floor($number / pow($length, $i - 1));
            $string .= $chars[$index];
            $number  = $number - pow($length, $i - 1) * $index;
        }
        return $string;
    }

    /**
     *	Converts a string into a positive number of a defined codec
     *	@param 	string 	$string
     *	@param 	string 	$codec
     *	@return mixed
     */
    public static function StrToInt($string, $codec = NULL)
    {
        if (empty($string))
            throw new Exception('Utils::StrToInt - No string defined');
        if (empty($codec))
            throw new Exception('Utils::StrToInt - No codec defined');

        $codec = str_split($codec);

        if(str_replace($codec, "", $string) != "")
            return false;

        $string_length = strlen($string);
        $length = count($codec);
        $number = 0;
        for($i = 0; $i < $string_length; $i++)
        {
            $number += array_search(substr($string, $i, 1), $codec) * (pow($length, ($string_length - ($i + 1))));
        }

        return intval($number);
    }

    /**
     *	Creates a random codec
     *	@param 	string 	$chars
     *	@return string
     */
    public static function createCodec($chars = null)
    {
        if (empty($chars))
            $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

        $chars = str_split($chars);
        shuffle($chars);
        return implode($chars);
    }

    /**
     *	Creates a random string based on a codec
     *	@param 	int 	$length
     *	@param 	string 	$codec
     *	@return string
     */
    public static function createRandomStr($lenght, $codec = NULL)
    {
        if (empty($codec))
            throw new Exception('Utils::StrToInt | No codec defined');

        $chr = "";
        for ($i = 0; $i < $lenght; $i++)
        {
            $chr .= Utils::IntToStr(mt_rand(0, strlen($codec) - 1));
            mt_rand(0, 1000);    // Call mt_rand for a better generation of random numbers
        }
        return $chr;
    }

    /**
     *	Return part of a string
     *	@param 	string 	$str
     *	@param 	int 	$start
     *  @param  int     $end
     *	@return string
     */
    public static function substr($str, $start, $end = null)
    {
        if ($end === null)
            $end = Utils::strlen($str);

        return mb_substr($str, $start, $end, 'UTF-8');
    }

    /**
     *	Get string length
     *	@param 	string 	$str
     *	@return string
     */
    public static function strlen($str)
    {
        return mb_strlen($str, 'UTF-8');
    }

    /**
     *	Make a string lowercase
     *	@param 	string 	$str
     *	@return string
     */
    public static function strtolower($str)
    {
        return mb_strtolower($str, 'UTF-8');
    }

    /**
     *	Make a string uppercase
     *	@param 	string 	$str
     *	@return string
     */
    public static function strtoupper($str)
    {
        return mb_strtoupper($str, 'UTF-8');
    }
}
