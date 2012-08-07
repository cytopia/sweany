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
 * Highlighter
 */
class Highlight
{
	public static function generic($string)
	{
		// keywords
		$string = self::_keysBold($string, array('Configuration index', 'Interface', 'Endpoint'), 'navy');
		$string = self::_keysBold($string, array('Configuration index', 'Interface', 'Endpoint'), 'navy');

		// numbers
		$string = self::_hex($string, 'orange');
//		$string = self::_int_float($string, 'orange');

		// strings
//		$string = self::_string($string, 'purple');

		return $string;
	}



	/******************************************** PROTECTED FUNCTIONS ********************************************/

	protected static function rep($string, $tag_open, $tag_close, $rep_open, $rep_close)
	{
		$find		= '#'.$tag_open.'(.+?)'.$tag_close.'#msi';
		$replace	= $rep_open.'\1'.$rep_close;

		return preg_replace($find, $replace, $string);
	}
	protected static function _keysBold($string, $keys = array(), $color)
	{
		foreach ($keys as $key)
			$string = str_replace($key, "<font color=$color><strong>$key</strong></font>", $string);

		return $string;
	}
	protected static function _keysNormal($string, $keys = array(), $color)
	{
		foreach ($keys as $key)
			$string = preg_replace("/(\W)($key)(\W)/", "\\1<font color=$color>\\2</font>\\3", $string);

		return $string;
	}
	protected static function _hex($string, $color)
	{
		return preg_replace("/(0x[0-9A-Fa-f]+)/i", '<font color='.$color.'>\\1</font>', $string);
	}
	protected static function _int_float($string, $color)
	{
		return preg_replace("/([^a-z_])([0-9]+(?:\.[0-9]+)?(?:e[+-][0-9]+)?[fdl]?)/i",    "\\1<font color=$color>\\2</font>",    $string);
	}
	private static function _string($string, $color)
	{
		$string = preg_replace("/(\".*?[^\\\\]?\")/se", "'<font color=$color>'.stripslashes(strip_tags('\\1')).'</font>'", $string);
        $string = preg_replace("/('.*?[^\\\']')/se", "'<font color=$color>'.stripslashes(strip_tags('\\1')).'</font>'", $string);
		return $string;
	}
}

?>