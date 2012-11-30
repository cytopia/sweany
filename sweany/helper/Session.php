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

	public static function id()
	{
		return session_id();
	}

	public static function get($key = null, $subKey = null, $subSubKey = null, $subSubSubKey = null)
	{
		return \Sweany\Session::get($key, $subKey, $subSubKey, $subSubSubKey);
	}




	/* ******************************************** SET ********************************************/
	public static function set($key, $val)
	{
		return \Sweany\Session::set($key, $val);
	}



	/* ******************************************** CHECK ********************************************/

	public static function exists($key, $subKey = null, $subSubKey = null, $subSubSubKey = null)
	{
		return \Sweany\Session::exists($key, $subKey, $subSubKey, $subSubSubKey);
	}



	/* ******************************************** DELETE ********************************************/

	public static function del($key, $subKey = null, $subSubKey = null, $subSubSubKey = null)
	{
		\Sweany\Session::del($key, $subKey, $subSubKey, $subSubSubKey);
	}


	public static function destroy()
	{
		\Sweany\Session::destroy();
	}
}
