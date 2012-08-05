<?php
/**
 * Strings
 *
 *
 * Sweany: MVC-like PHP Framework with blocks and tables (entities)
 * Copyright 2011-2012, Patu
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	Copyright 2011-2012, Patu
 * @link		none yet
 * @package		sweany.lib
 * @author		Patu
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @version		0.7 2012-07-29 13:25
 *
 */
class Strings
{
	public static function removeEmptyLines($string)
	{
		return preg_replace('/^\n+|^[\t\s]*\n+/m','',$string);
	}

	public static function tabToSpace($string)
	{
		return str_replace("\t", " ", $string);
	}

	public static function removeTags($string)
	{
		return htmlentities(trim($string), ENT_COMPAT, 'UTF-8');
	}

	public static function shorten($string, $length, $add_dots = false)
	{
		// return the string if it is shorter anyway
		if ( strlen($string) <= $length )
			return $string;

		// add dots if desirec;
		$dots	= ($add_dots) ? '...': '';

		// cut the string
		$string = substr($string, 0, $length);

		// This will remove the last word from the string
		// as it might have been (99%) broken during cutting
		$string = substr($string, 0, strrpos($string, " "));
		return $string.$dots;
	}

	public static function removeLines($string, $needles = array())
	{
		$lines	= explode("\n", $string);
		$tmp	= array();

		foreach ($lines as $line)
		{
			$found	= false;

			foreach ($needles as $needle)
			{
				if ( strpos($line, $needle) !== false )
					$found = true;
			}

			if ( !$found )
				$tmp[] = $line;
		}
		return implode("\n", $tmp);
	}
}

?>