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
class Validate04Database extends aBootTemplate
{
	/* ******************************************** OVERRIDE INITIALIZE ********************************************/
	public static function initialize($options = null)
	{
		if ( $GLOBALS['SQL_ENABLE'] == true )
		{
			if ( !self::_checkVariableExistance() )
			{
				echo '<h1>Validation Error: Variable missing in Config.php</h2>';
				return false;
			}

			if ( !self::_checkVariableValue() )
			{
				echo '<h1>Validation Error: Variable with wrong value in Config.php</h2>';
				return false;
			}

			if ( !self::_checkSQLConnection() )
			{
				echo '<h1>Validation Error: SQL</h2>';
				return false;
			}
		}
		return true;
	}

	private static function _checkVariableExistance()
	{
		if ( !isset($GLOBALS['SQL_ENGINE']) )
		{
			self::$error  = '<b>$SQL_ENGINE</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['SQL_HOST']) )
		{
			self::$error  = '<b>$SQL_HOST</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['SQL_DB']) )
		{
			self::$error  = '<b>$SQL_DB</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['SQL_USER']) )
		{
			self::$error  = '<b>$SQL_USER</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['SQL_PASS']) )
		{
			self::$error  = '<b>$SQL_PASS</b> not defined in <b>config.php</b>';
			return false;
		}
		return true;
	}


	private static function _checkVariableValue()
	{
		// TODO: alter if more engines will be supported
		if ( $GLOBALS['SQL_ENGINE'] != 'mysql' )
		{
			self::$error  = '<b>$SQL_ENGINE</b> currently only supports <b>mysql</b>. You have set <i>'.$GLOBALS['SQL_ENGINE'].'</i> in <b>config.php</b>';
			return false;
		}
		return true;
	}

	private static function _checkSQLConnection()
	{
		// This is needed to be initialized here as well,
		// so we have access to tables in the controller and can verify them too
		// This won't be called if validation mode is off, so no worry for two calls
		if ( !\Sweany\Database::initialize() )
		{
			self::$error  = \Sweany\Database::getError();
			self::$error  .= '<br/>Cannot initialize Database connection';
			return false;
		}
		return true;
	}
}
