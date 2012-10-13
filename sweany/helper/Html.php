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
 * @version		0.8 2012-08-13 13:25
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
	 * You should use this function to build all links, if you intend
	 * to enable url rewriting later, otherwise your links will break down.
	 *
	 * If controller, method or params are null it will
	 * get the current controller, method or params.
	 *
	 * @param	string	$name			Name of the link text to display
	 * @param	string	$controller		Name of the controller class (not the url name)
	 * @param	string	$method			Name of the controller mehtod (not the url name)
	 * @param	mixed[]	$params			Assoc array of controller method parameters
	 * @param	mixed[]	$attributes		Assoc array of attributes (style=>'', title=>'', )
	 * @param	string	$anchor			<a> anchor to append
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
		$args = implode('/', array_map(create_function('$param', 'return ($param);'), array_values($params)));
		$attr = implode(' ', array_map(create_function('$key, $val', 'return $key."=\"".$val."\"";'), array_keys($attributes), array_values($attributes)));
		$link = '/'.$controller.'/'.$method.'/'.$args.$anchor;

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
	 * @param unknown_type $src
	 * @param unknown_type $alt
	 * @param unknown_type $options
	 */
	public static function img($src, $alt = null, $options = array())
	{
		$opts = implode(' ', array_map( create_function('$key, $val', 'return $key."=\"".$val."\"";'), array_keys($options), array_values($options)));

		return '<img border="0" src="'.$src.'" alt="'.$alt.'"' .$opts.' />';
	}


	public static function getLanguageSwitcher($pre = null, $post = null, $show_flags = true, $show_names =  false)
	{
		$all	= $GLOBALS['LANGUAGE_AVAILABLE'];
		$path	= $GLOBALS['LANGUAGE_IMG_PATH'];
		$url	= $GLOBALS['DEFAULT_SETTINGS_URL'];
		$switch	= '';

		foreach ($all as $lang=>$name)
		{
			// Current language does not require a click link
			if ( $lang == \Sweany\Language::getLangShort() )
			{
				$switch .= $pre;
				$switch .= '<img title="'.$name.'" src="'.$path.'/'.$lang.'.png" alt="'.$name.'" border="0" />';
				$switch .= ($show_names) ? '<span>'.$name.'</span>' : '';
				$switch .= $post;
			}
			// All other language need a link to change it
			else
			{
				$switch .= $pre;
				$switch .= '<a href="/'.$url.'/lang/'.$lang.'">';
				$switch .= '<img title="'.$name.'" src="'.$path.'/'.$lang.'.png" alt="'.$name.'" border="0" />';
				$switch .= ($show_names) ? $name : '';
				$switch .= '</a>';
				$switch .= $post;
			}
		}
		return $switch;
	}

	public static function nbsp($number = 1)
	{
		$nbsp = '';
		for ($i=0; $i<$number; ++$i)
		{
			$nbsp .= '&nbsp;';
		}
		return $nbsp;
	}
}
