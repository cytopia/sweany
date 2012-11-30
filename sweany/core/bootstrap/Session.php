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
 * This core module will activate the Session.
 */
namespace Sweany;

class Session extends aBootTemplate
{

	/* ******************************************** OVERRIDE INITIALIZE ********************************************/

	public static function initialize($options = null)
	{
		if ( session_start() )
		{
			return true;
		}
		else
		{
			switch (session_status())
			{
				case PHP_SESSION_DISABLED:	self::$error = 'PHP_SESSION_DISABLED';	return false;
				case PHP_SESSION_NONE : 	self::$error = 'PHP_SESSION_NONE';		return false;
				case PHP_SESSION_ACTIVE :	self::$error = 'PHP_SESSION_ACTIVE';	return false;
				default:					self::$error = 'Unknown Session Error'; return false;
			}
		}
	}



	/* ******************************************** GET ********************************************/

	public static function id()
	{
		return session_id();
	}


	public static function get($key = null, $subKey = null, $subSubKey = null, $subSubSubKey = null)
	{
		// TODO: change to NOT OR as it is faster!
		if ( $subSubSubKey && $subSubKey && $subKey && $key )
		{
			return isset($_SESSION[$key][$subKey][$subSubKey][$subSubSubKey]) ? $_SESSION[$key][$subKey][$subSubKey][$subSubSubKey] : null;
		}
		else if ( $subSubKey && $subKey && $key )
		{
			return isset($_SESSION[$key][$subKey][$subSubKey]) ? $_SESSION[$key][$subKey][$subSubKey] : null;
		}
		else if ( $subKey && $key )
		{
			return isset($_SESSION[$key][$subKey]) ? $_SESSION[$key][$subKey] : null;
		}
		else if ( $key )
		{
			return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
		}
		else
		{
			return $_SESSION;
		}
	}


	/* ******************************************** SET ********************************************/
	
	/**
	 *
	 *	$key = 'string';
	 *	$key = array('string' => 'string');
	 */
	public static function set($key, $val)
	{
		if ( is_array($key) ) {
			
			$subKey = current($key);
			$key	= key($key);
			
			$_SESSION[$key][$subKey] = $val;

		} else {
			$_SESSION[$key] = $val;
		}
	}



	/* ******************************************** CHECK ********************************************/
	public static function exists($key, $subKey = null, $subSubKey = null, $subSubSubKey = null)
	{
		// TODO: change to NOT OR as it is faster!
		if ( $subSubSubKey && $subSubKey && $subKey )
		{
			return isset($_SESSION[$key][$subKey][$subSubKey][$subSubSubKey]);
		}
		else if ( $subSubKey && $subKey  )
		{
			return isset($_SESSION[$key][$subKey][$subSubKey]);
		}
		else if ( $subKey )
		{
			return isset($_SESSION[$key][$subKey]);
		}
		else
		{
			return isset($_SESSION[$key]);
		}
	}



	/* ******************************************** DELETE ********************************************/

	public static function del($key, $subKey = null, $subSubKey = null, $subSubSubKey = null)
	{
		// TODO: change to NOT OR as it is faster!
		if ( $subSubSubKey && $subSubKey && $subKey && isset($_SESSION[$key][$subKey][$subSubKey][$subSubSubKey]) )
		{
			unset($_SESSION[$key][$subKey][$subSubKey][$subSubSubKey]);
		}
		else if ( $subSubKey && $subKey && isset($_SESSION[$key][$subKey][$subSubKey]) )
		{
			unset($_SESSION[$key][$subKey][$subSubKey]);
		}
		else if ( $subKey && isset($_SESSION[$key][$subKey]))
		{
			unset($_SESSION[$key][$subKey]);
		}
		else if (isset($_SESSION[$key]))
		{
			unset($_SESSION[$key]);
		}
	}


	public static function destroy()
	{
		// Delete the whole session and the session cookie (with its file)
		if (ini_get("session.use_cookies"))
		{
			$params = session_get_cookie_params();
			setcookie(
				session_name(),
				'',
				time() - 42000,
				$params['path'],
				$params['domain'],
				$params['secure'],
				$params['httponly']
			);
		}
		session_destroy();
	}
}