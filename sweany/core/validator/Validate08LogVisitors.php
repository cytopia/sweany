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
 * @package		sweany.core.validator
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.7 2012-08-08 11:25
 *
 *
 * This (optional) core will validate various settings of
 * the framework itself.
 */
namespace Sweany;
class Validate08LogVisitors extends aBootTemplate
{
	/* ******************************************** OVERRIDE INITIALIZE ********************************************/
	public static function initialize($options = null)
	{
		if ( $GLOBALS['SQL_LOG_VISITORS_ENABLE'] == true )
		{
			if ( !self::_checkLogVisitors() )
			{
				echo '<h1>Validation Error</h2>';
				echo self::$error;
				return false;
			}
		}
		return true;
	}



	/* ******************************************** VALIDATORS ********************************************/

	private static function _checkLogVisitors()
	{
		$db = \Sweany\Database::getInstance();

		if ( $GLOBALS['SQL_ENABLE'] == false )
		{
			self::$error  = '<b>Log Visitors Module:</b> You have to enable SQL Module in order to use it.';
			return false;
		}
		// CHECK SQL TABLES
		if ( count($db->select("show tables  from `$GLOBALS[SQL_DB]` like 'core_visitors'; ")) < 1 )
		{
			self::$error  = 'MySQL: <b>core_visitors</b> table does not exist';
			return false;
		}
		return true;
	}
}
