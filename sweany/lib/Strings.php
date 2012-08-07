<?php
/**
 * Sweany MVC PHP framework
 * Copyright (C) 2011-2012 Patu.
 *
 * Sweany is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Sweaby is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Sweany. If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright	Copyright 2011-2012, Patu
 * @link		none yet
 * @package		sweany.core.lib
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.7 2012-07-29 13:25
 *
 *
 * Strings
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