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
 * Sweany is distributed in the hope that it will be useful,
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
 * Javascript Helper
 */
Class Javascript
{
	private static $js_files		= array();
	private static $js_funcs		= array();
	private static $js_vars			= array();
	private static $js_code_bottom	= null;

	private static $onPageLoad		= null;


	/********************************************************* A C T I O N   F U N C T I O N S *********************************************************/

	public static function setOnPageLoadFunction($functionName)
	{
		self::$onPageLoad = $functionName;
	}
	public static function getOnPageLoadFunction()
	{
		// isset [0] is the fastest language construct to check against string
		if ( isset(self::$onPageLoad[0]) ) {
			return ' onload="'.self::$onPageLoad.'"';
		}
		return '';
	}

	// Add global variables
	public static function addVars($vars = array())
	{
		$size = sizeof(self::$js_vars);
		foreach ($vars as $var => $value)
		{
			if ( is_numeric($value) ) {
				self::$js_vars[$size] = 'var '.$var.'='.$value.';';
			} else {
				self::$js_vars[$size] = 'var '.$var.'=\''.addslashes($value).'\';';
			}
			$size++;
		}
	}

	public static function addFunction($function)
	{
		$size = sizeof(self::$js_funcs);
		self::$js_funcs[$size] = $function;
	}
	
	
	public static function addToBottom($code)
	{
		self::$js_code_bottom .= $code;
	}

	public static function addFile($file)
	{
		// This makes it possible to override existing files
		// and not having them included twice or more times.
		self::$js_files[$file] = '';
	}



	/********************************************************* G E T T E R   F U N C T I O N S *********************************************************/	
	
	public static function getVars()
	{
		if ( !sizeof(self::$js_vars) )
			return '';

		$pre	= '<script type="text/javascript">';
		$post	= '</script>';
		$code	= '';

		foreach ( self::$js_vars as $var )
			$code .= $var;

		return "\t".$pre."\n".$code."\n\t".$post."\n";
	}

	public static function getFunctions()
	{
		if ( !sizeof(self::$js_funcs) )
			return '';

		$pre	= '<script type="text/javascript">';
		$post	= '</script>';
		$code	= '';

		foreach ( self::$js_funcs as $function ) {
			$code .= $function."\n";
		}

		return "\t".$pre."\n".$code."\n\t".$post."\n";
	}

	public static function getFiles()
	{
		$code	= '';

		foreach (self::$js_files as $file=>$null) {
			$code .= "\t".'<script type="text/javascript" src="'.$file.'"></script>'."\n";
		}
		return $code;
	}

	public static function getBottomCode()
	{
		if ( !sizeof(self::$js_code_bottom) ) {
			return '';
		}

		$pre	= '<script type="text/javascript">';
		$post	= '</script>';
		$code	= self::$js_code_bottom;

		return "\t".$pre."\n".$code."\n\t".$post."\n";
	}
}
