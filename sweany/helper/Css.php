<?php
/**
 * Sweany MVC PHP framework
 * Copyright (C) 2011-2012 cytopia.
 *
 * Sweany is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Sweany is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Sweany. If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright	Copyright 2011-2012, cytopia
 * @link		none yet
 * @package		sweany.core.lib
 * @author		cytopia <cytopia@everythingcli.org>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.7 2012-07-29 13:25
 *
 *
 * CSS Helper
 */
Class Css
{
	private static $css_files	= array();
	private static $css_inline	= array();


	/********************************************************* A C T I O N   F U N C T I O N S *********************************************************/

	public static function addFile($file)
	{
		$size = sizeof(self::$css_files);

		if ( $GLOBALS['ECSS_ENABLE'] )
		{
			$options	 = '';
			$options	.= ( $GLOBALS['ECSS_COMPRESSED'] )	? '&compressed' : '';
			$options	.= ( $GLOBALS['ECSS_COMMENTED'] )	? '&comment' : '';

			$protocol	= ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
			$domain		= $_SERVER['HTTP_HOST'];
			$file		= '/sweany/ecss.php?file='.$protocol.$domain.$file.$options;
		}
		self::$css_files[$size] = '<link rel="stylesheet" type="text/css" href="'.$file.'" />';
	}

	public static function addInlineCss($css_code)
	{
		self::$css_inline[] = $css_code;
	}

	public static function getFiles()
	{
		$code	= '';

		foreach (self::$css_files as $file) {
			$code .= "\t".$file."\n";
		}
		return $code;
	}

	public static function getInlineCss()
	{
		$code	= implode("\n", self::$css_inline);
		$pre	= '<style type="text/css">';
		$post	= '</style>';

		$code	= isset($code[0]) ? $pre.$code.$post : '';

		return $code;
	}
}
