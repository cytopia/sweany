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
 * All core modules (sys/core/*) must implement this class
 * and override their own 'initialize()' function.
 *
 */
namespace Sweany;

class aBootTemplate
{
	/**
	 *
	 * Will contain the error message (if any)
	 * that occured during core module initialization
	 * (see www/index.php for handling)
	 */
	protected static $error	= null;

	/**
	 *
	 * Initialize Function for the core moduls
	 * The inherited calsses MUST define their own init() function
	 * otherwise we will throw an error
	 */
	public static function initialize($options = null)
	{
		self::$error = 'initialize() function not defined';
		return false;
	}


	/**
	 * Some core modules do need a destroy function]
	 * E.g. MySQL needs to close the link at the end
	 * So that we do not forget about this, we have to override
	 * This destroy function as it will be called at the end in (index.php)
	 * If it is not overriden we will point it out by being childish and dont start
	 * the page
	 */
	public static function cleanup()
	{
	}


	/**
	*
	* Used to return the error that happened during Core initialization
	*/
	public static function getError()
	{
		return self::$error;
	}
}
