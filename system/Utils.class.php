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
     *	Converts a number into a string of a defined codec
     *	@param 	float 	$number
     *	@param 	string 	$codec
     *	@return string
     */
    public static function IntToStr($number, $codec)
    {
        if (empty($number))
            $number = 0;
		if (empty($codec))
            throw new Exception('Utils::IntToStr | No codec defined');

        if($number == 0)
            return substr($codec, 0, 1);

		$negative = 0;
        if($number < 0)
        {
            $number = abs($number);
            $negative=1;
        }

        $output = "";
        if(strstr($number, '.'))
        {
            $split = explode(".", $number);
            $number = $split[0];
            $output = $split[1];
        }

        preg_match_all('/./', $codec, $matches);
        $matches = $matches[0];
        if(count($matches) < 2)
            return;

        $length = count($matches);
        $places = 0;
        while($number >= pow($length, $places))
            $places++;

        $string = "";
        $i = 0;

        for($i=$stellen; $i>0; $i--)
        {
            $index = floor($number/pow($length, $i-1));
            $string .= $matches[$index];
            $number = $number-pow($length, $i-1)*$index;
        }
        return ($negative ? '.' : '') . $string . (strlen($output) ? '.' . Utils::IntToStr($output, $codec) : '');
    }

    /**
     *	Converts a string into a number of a defined codec
     *	@param 	string 	$string
     *	@param 	string 	$codec
     *	@return float
     */
    public static function StrToInt($string, $codec = NULL)
    {
        if (empty($string))
            throw new Exception('Utils::StrToInt | No string defined');
        if (empty($codec))
            throw new Exception('Utils::StrToInt | No codec defined');

        $negative = 0;
        if(substr($string, 0, 1) == '.')
        {
            $string = substr($string, 1);
            $negative = 1;
        }

        $dec = "";
        if(strstr($string, '.'))
        {
            $split = explode(".", $string);
            $string = $split[0];
            $dec = $split[1];
        }

        preg_match_all('/./', $codec, $array);
        $array = $array[0];
        if(count($array) < 2)
            return 0;

        if(!str_replace($array, "", $string) == "")
            return 0;

        $strlength = strlen($string);
        $length = count($array);
        $int = 0;
        for($i=0; $i < $strlength; $i++)
        {
            $int += array_search(substr($string, $i, 1), $array) * (pow($length, ($strlength - ($i + 1))));
        }

        return ($int * ($negative ? -1 : 1)) . (strlen($dec) ? '.' . Utils::StrToInt($dec, $codec) : '');
    }

    /**
     *	Creates a random string based on a codec
     *	@param 	int 	$length
     *	@param 	string 	$codec
     *	@return string
     */
    static public function createRandomStr($lenght, $codec = NULL)
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
	static public function substr($str, $start, $end = null)
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
	static public function strlen($str)
    {
        return mb_strlen($str, 'UTF-8');
    }

    /**
     *	Make a string lowercase
     *	@param 	string 	$str
     *	@return string
     */
    static public function strtolower($str)
    {
        return mb_strtolower($str, 'UTF-8');
    }

    /**
     *	Make a string uppercase
     *	@param 	string 	$str
     *	@return string
     */
    static public function strtoupper($str)
    {
        return mb_strtoupper($str, 'UTF-8');
    }
}
