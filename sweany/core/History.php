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
 * @package		sweany.core
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.7 2012-07-29 13:25
 *
 *
 * Class that stores the Users History
 */
Class History
{
	private static $sessionKey	= 'history';
	private static $lastPage	= array();

	public static function track()
	{
		$controller = \Core\Init\CoreUrl::getController();
		$method		= \Core\Init\CoreUrl::getMethod();
		$params		= \Core\Init\CoreUrl::getParams();

		// If no params are specified and the method name is equal to the default method name
		// we will remove it, so the redirect will look nicer by not specifying the method
		if ( !count($params) && $method == $GLOBALS['ANY_CONTROLLER_DEFAULT_METHOD'] )
		{
			$method = null;
			$params	= null;
		}
		$history = array('controller' => $controller, 'method' => $method, 'params' => $params);

		\Core\Init\CoreSession::set(self::$sessionKey, $history);
	}

	public static function getPrevPage()
	{
		if ( \Core\Init\CoreSession::exists(self::$sessionKey) )
		{
			return \Core\Init\CoreSession::get(self::$sessionKey);
		}
		// No previous page exists yet, so redirect to '/' by setting all params to null
		else
		{
			return array('controller' => null, 'method' => null, 'params' => null);
		}
	}

}
