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
 * @package		sweany.core.init
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.7 2012-07-29 13:25
 *
 *
 * This core module will extract
 * controller, function and params from the given URL
 *
 * It also takes care about encoding/decoding function parameter values
 */
namespace Core\Init;

class CoreUrl extends CoreAbstract
{
	/* ******************************************** VARIABLES ********************************************/

	public static $request		= null;

	private static $urlParams	= null;



	/* ******************************************** OVERRIDE INITIALIZE ********************************************/

	public static function initialize()
	{
		self::$request = $_SERVER['REQUEST_URI'];

		// remove first slah
		if ( isset(self::$request[0]) &&  self::$request[0] == '/' )
		{
			self::$request = substr(self::$request, 1, strlen(self::$request));
		}

		// if using $html->l() it will be double url encoded and / \ will also be double encoded
		// so we have to revert it, after exploding
		$params 	= explode('/', self::$request);
		$encoded	= array();

		// encode params
		foreach ($params as $param)
			if ( strlen($param) > 0 )
				$encoded[] = self::_encodeParam($param);

		self::$urlParams = $encoded;

		return true;
	}


	/* ******************************************** ACTIONS ********************************************/


	public static function getController()
	{
		global $CUSTOM_ROUTING;

		// No request exist, return null for frontpage
		if ( !isset(self::$urlParams[0]) )
			return null;

		// Custom seo controller name :-)
		if ( isset($CUSTOM_ROUTING[self::$urlParams[0]]['controller']) && strlen($CUSTOM_ROUTING[self::$urlParams[0]]['controller']) )
		{
			\SysLog::i('Seo-URL', '[SEO] <span style="color:yellow;">'.self::$urlParams[0].'</span> =&gt; <strong style="color:white;">class</strong> <strong style="color:green;">'.$CUSTOM_ROUTING[self::$urlParams[0]]['controller'].'</strong>');
			return $CUSTOM_ROUTING[self::$urlParams[0]]['controller'];
		}

		return self::$urlParams[0];
	}


	public static function getMethod()
	{
		// if no method has been supplied as url parameter
		// try the default method (usually 'index'), but can be set in config
		return isset(self::$urlParams[1]) ? self::$urlParams[1] : $GLOBALS['ANY_CONTROLLER_DEFAULT_METHOD'];
	}

	public static function getParams()
	{
		$params	= array();
		$size	= count(self::$urlParams);

		for ($i=2; $i<$size; $i++)
		{
			$params[]	= self::$urlParams[$i];
		}
		return $params;
	}
	public static function getRequest()
	{
		return self::$request;
	}


	/**
	 * Converts controller, method and params to propper url link
	 *
	 * @param string $controller	(optional)
	 * @param string $method		(optional)
	 * @param array  $params		(optional)
	 */
	public static function ControllerMethodAndParamsToUrlLink($controller = null, $method = null, $params = array())
	{
		// path is: '/' (root page)
		if ( is_null($controller) )
		{
			$link = '/';
		}
		// path is: '/ControllerName'
		else if ( !is_null($controller) && is_null($method) )
		{
			$link = '/'.$controller;
		}
		// path is: full path
		else
		{
			$args = (is_array($params)) ? implode('/', $params) : '';
			$link = '/'.$controller.'/'.$method;
			$link.= (strlen($args)) ? '/'.$args : '';
		}
		return $link;
	}


	public static function changeSingleParam($param_position, $value)
	{
		$arr	= self::getParams();
		$size 	= (count($arr) >= $param_position) ? count($arr) : $param_position;
		$params	= array();

		// fill up missing params with 0 if not set before $param_position
		for ($i=0; $i<$size; $i++)
		{
			if ( !isset($arr[$i]) && !strlen($arr[$i]) )
				$params[$i] = 0;
			else
				$params[$i] = $arr[$i];
		}
		$params[$param_position-1] = self::_encodeParam($value);

		return $params;
	}



	/* ******************************************** PRIVATES ********************************************/

	private static function _encodeParam($value)
	{
		return ($value);
	}
}
