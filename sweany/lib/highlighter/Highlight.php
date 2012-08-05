<?php
/**
 * Highlighter
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