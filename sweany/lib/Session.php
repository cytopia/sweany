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
 * Session Helper
 */
class Session
{

	/* ******************************************** GET ********************************************/

	public static function getId()
	{
		return ( session_id() );
	}

	public static function get($key)
	{
		return ( isset($_SESSION[$key]) ) ? $_SESSION[$key] : NULL;
	}

	public static function getSubKey($section, $key, $position = NULL)
	{
		if ( $position )
		{
			$tmp = isset($_SESSION[$section][$position][$key]) ? $_SESSION[$section][$position][$key] : array();
		}
		else
		{
			$tmp = isset($_SESSION[$section][$key]) ? $_SESSION[$section][$key] : array();
		}

		return ( $tmp );
	}



	/* ******************************************** SET ********************************************/
	public static function set($key, $val)
	{
		$_SESSION[$key] = $val;
	}



	/* ******************************************** CHECK ********************************************/
	public static function exists($key)
	{
		return isset($_SESSION[$key]);
	}



	/* ******************************************** DELETE ********************************************/

	public static function del($key)
	{
		if ( isset($_SESSION[$key]) )
			unset($_SESSION[$key]);
	}

	public static function delSubKey($key, $sub_key)
	{
		if ( isset($_SESSION[$key][$sub_key]) )
			unset($_SESSION[$key][$sub_key]);
	}

	public static function destroy()
	{
		// Delete the whole session and the session cookie (with its file)
		if ( ini_get("session.use_cookies") )
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
