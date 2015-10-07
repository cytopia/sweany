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
 * @package		sweany.core.init
 * @author		cytopia <cytopia@everythingcli.org>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.7 2012-07-29 13:25
 *
 *
 * Database Wrapper.
 */
namespace Sweany;

class Database extends aBootTemplate
{
	private static $db		= null;
	private static $engine	= null;
	private static $class	= null;

	/*********************************************** OVERRIDE INITIALIZE ***********************************************/

	public static function initialize($options = null)
	{
		$options['host']		= $GLOBALS['SQL_HOST'];
		$options['database']	= $GLOBALS['SQL_DB'];
		$options['user']		= $GLOBALS['SQL_USER'];
		$options['pass']		= $GLOBALS['SQL_PASS'];

		self::$engine			= $GLOBALS['SQL_ENGINE'];
		self::$class			= '\Sweany\\'.self::$engine;

		$engine					= self::$engine;
		$class					= self::$class;
		
		// TODO: if validator mode is on
		// check if file exists
		require_once(CORE_DATABASE.DS.'iDBO.php');
		require_once(CORE_DATABASE.DS.$engine.'.php');
		
		// Check if the database engine implements required interface class
		if ( !$class::_implemented )
		{
			self::$error = 'Database engine: \''.$engine.'\' does not implement interface iDataBase';
			return false;
		}

		// Initialize Database Connection
		if ( !$class::initialize($options) )
		{
			self::$error = $class::getError();
			return false;
		}

		return true;
	}


	/*********************************************** OVERRIDE CLEANUP ***********************************************/

	public static function cleanup()
	{
		$class = self::$class;
		$class::cleanup();
	}
	
	public static function getInstance()
	{
		if ( !self::$db )
		{
			$class		= self::$class;
			self::$db	= new $class;
		}
		return self::$db;
	}
}
