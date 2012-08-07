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
 * Html Helper
 */

define('BR', '<br/>');

Class Html
{
	/**
	 *
	 * Build internal <a href></a> construct
	 *
	 * If controller, method or params are null it will
	 * get the current controller, method or params
	 * @param String $name
	 * @param String $controller
	 * @param String $method
	 * @param Array $params
	 * @param Array $attributes
	 * @param String $anchor
	 */
	public static function l($name, $controller = null, $method = null, $params = array(), $attributes = array(), $anchor = null)
	{
		if (is_null($controller))
			$controller	= Url::getController();

		if (is_null($method))
			$method	= Url::getMethod();

		$params		= (!is_array($params))		? array() : $params;
		$attributes	= (!is_array($attributes))	? array() : $attributes;


		// TODO: maybe need to escape the params for url - keep an eye on it
		$args = implode('/',  array_map(create_function('$param', 'return ($param);'), array_values($params)));
		$attr = implode(' ', array_map(create_function('$key, $val', 'return $key."=\"".$val."\"";'), array_keys($attributes), array_values($attributes)));
		$link = '/'.$controller.'/'.$method.'/'.$args;

		return '<a href="'.$link.'"'.$attr.'>'.$name.'</a>';
	}

	/**
	 *
	 * Build external <a href></a> construct
	 * @param Stromg $name
	 * @param String $link
	 * @param Array $attributes
	 */
	public static function el($name, $link, $attributes = array())
	{
		$attr	= implode(' ', array_map( create_function('$key, $val', 'return $key."=\"".$val."\"";'), array_keys($attributes), array_values($attributes)));

		return '<a '.$attr.' href="'.$link.'">'.$name.'</a>';
	}


	/**
	 * returns html image
	 *
	 * TODO: foreach is too slow, have to replace with create_function!
	 *
	 * @param unknown_type $src
	 * @param unknown_type $alt
	 * @param unknown_type $options
	 */
	public static function img($src, $alt = NULL, $options = null)
	{
		$opts = '';
		if ( is_array($options) )
		{
			foreach ($options as $key => $value)
			{
				$opts	.= $key.'="'.$value.'" ';
			}
		}
		return '<img border="0" src="'.$src.'" alt="'.$alt.'"' .$opts.' />';
	}


	public static function getLanguageSwitcher($ctrl, $mthd)
	{
		$all	= $GLOBALS['LANGUAGE_AVAILABLE'];
		$path	= $GLOBALS['LANGUAGE_IMG_PATH'];
		$switch	= '';

		foreach ($all as $lang=>$name)
		{
			$switch .= '<a href="/'.$ctrl.'/'.$mthd.'/'.$lang.'">';
			$switch .= '<img title="'.$name.'" src="'.$path.'/'.$lang.'.png" alt="'.$name.'" />';
			$switch .= '</a> ';
		}
		return $switch;
	}
}

?>