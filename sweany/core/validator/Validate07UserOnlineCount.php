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
class Validate07UserOnlineCount extends aBootTemplate
{
	/* ******************************************** OVERRIDE INITIALIZE ********************************************/
	public static function initialize($options = null)
	{
		if ( $GLOBALS['USER_ONLINE_COUNT_ENABLE'] == true )
		{
			if ( !self::_checkVariableExistance() )
			{
				echo '<h1>Validation Error</h2>';
				echo self::$error;
				return false;
			}

			if ( !self::_checkVariableValue() )
			{
				echo '<h1>Validation Error: Variable with wrong value in Config.php</h2>';
				echo self::$error;
				return false;
			}

			if ( !self::_checkUserOnlineCount() )
			{
				echo '<h1>Validation Error</h2>';
				echo self::$error;
				return false;
			}
		}
		return true;
	}



	/* ******************************************** VALIDATORS ********************************************/


	private static function _checkVariableExistance()
	{
		if ( !isset($GLOBALS['USER_ONLINE_SINCE_MINUTES']) )
		{
			self::$error  = '<b>$USER_ONLINE_SINCE_MINUTES</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['USER_ONLINE_ADD_FAKE_GUESTS']) )
		{
			self::$error  = '<b>$USER_ONLINE_ADD_FAKE_GUESTS</b> not defined in <b>config.php</b>';
			return false;
		}
		return true;
	}


	private static function _checkVariableValue()
	{
		if ( !is_int($GLOBALS['USER_ONLINE_SINCE_MINUTES']) )
		{
			self::$error  = '<b>$USER_ONLINE_SINCE_MINUTES</b> must be an integer number in <b>config.php</b>';
			return false;
		}
		if ( $GLOBALS['USER_ONLINE_SINCE_MINUTES'] < 1 )
		{
			self::$error  = '<b>$USER_ONLINE_SINCE_MINUTES</b> must be greater than <b>1</b> in <b>config.php</b>';
			return false;
		}
		if ( !is_int($GLOBALS['USER_ONLINE_ADD_FAKE_GUESTS']) )
		{
			self::$error  = '<b>$USER_ONLINE_ADD_FAKE_GUESTS</b> must be an integer number in <b>config.php</b>';
			return false;
		}
		if ( $GLOBALS['USER_ONLINE_ADD_FAKE_GUESTS'] <= 0 )
		{
			self::$error  = '<b>$USER_ONLINE_ADD_FAKE_GUESTS</b> must be equal or greater than <b>0</b> in <b>config.php</b>';
			return false;
		}
		return true;
	}


	private static function _checkUserOnlineCount()
	{
		if ( $GLOBALS['SQL_ENABLE'] == false )
		{
			self::$error  = '<b>User Online Count Module:</b> You have to enable SQL Module in order to use it.';
			return false;
		}

		if ( $GLOBALS['USER_ENABLE'] == false )
		{
			self::$error  = '<b>User Online CountModule:</b> You have to enable User Module in order to use it.';
			return false;
		}

		$db = \Sweany\Database::getInstance();

		// CHECK SQL TABLES
		if ( count($db->select("show tables  from `$GLOBALS[SQL_DB]` like '".Settings::tblOnlineUsers."'; ")) < 1 )
		{
			self::$error  = 'MySQL: <b>'.Settings::tblOnlineUsers.'</b> table does not exist';
			return false;
		}
		return true;
	}
}
