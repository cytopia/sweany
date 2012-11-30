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
 * Request Helper
 */
class Request
{
	public static function get($key, $default_val = null)
	{
		if ( isset($_REQUEST[$key]) ) {
			return ( $_REQUEST[$key] );
		} else {
			return ( $default_val );
		}
	}

	/**
	 *
	 *  Informs whether or not the controller request
	 *  was done via ajax.
	 */
	public static function isAjax()
	{
		return ( isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' );
	}

	public static function isPost()
	{}
	public static function isGet()
	{}
	public static function isPut()
	{}
	public static function isDelete()
	{}
	public static function isSSL()
	{}
	public static function isXml()
	{}
	public static function isRss()
	{}
	public static function isAtom()
	{}
	public static function isMobile()
	{}
	public static function isWap()
	{}
}
