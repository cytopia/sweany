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
 * @version		0.7 2012-08-08 11:08
 *
 *
 * This core module will set the following settings:
 * 	+ page debug mode and enable/disable loggin
 * 	+ character encoding
 * 	+ timezone
 */
namespace Core\Init;

class CoreSettings extends CoreAbstract
{
	/* ******************************************** VARIABLES ********************************************/
	public static $showPhpErrors	= false;
	public static $showSqlErrors	= false;
	public static $showFwErrors		= false;

	public static $logPhpErrors		= false;
	public static $logSqlErrors		= false;
	public static $logFwErrors		= false;

	private static $coreLogFile		= null;
	private static $userLogFile		= null;

	private static $timezone		= null;
	private static $locale			= null;


	// used for output buffering
	public static $ob_callback		= null;

	/* ******************************************** OVERRIDE INITIALIZE ********************************************/
	public static function initialize()
	{
		self::_activateUTF8Encoding();


		// INITIALIZE VALUES
		self::$showPhpErrors	= $GLOBALS['SHOW_PHP_ERRORS'];
		self::$showSqlErrors	= $GLOBALS['SHOW_SQL_ERRORS'];
		self::$showFwErrors		= $GLOBALS['SHOW_FRAMEWORK_ERRORS'];

		self::$logPhpErrors		= $GLOBALS['LOG_PHP_ERRORS'];
		self::$logSqlErrors		= $GLOBALS['LOG_SQL_ERRORS'];
		self::$logFwErrors		= $GLOBALS['LOG_FRAMEWORK_ERRORS'];

		self::$coreLogFile		= LOG_PATH.DS.$GLOBALS['FILE_LOG_CORE'];
		self::$userLogFile		= LOG_PATH.DS.$GLOBALS['FILE_LOG_USER'];

		// Language/Date specific settings
		self::$timezone			= $GLOBALS['DEFAULT_TIME_ZONE'];
		self::$locale			= $GLOBALS['DEFAULT_LOCALE'];

		// TODO: need to synchronize with mysql database timezone
		self::_setTimeZone();

		if ( !self::_setLocale() )
		{
			self::$error = 'Cannot set locale, wrong format';
			return false;
		}

		self::$ob_callback = (self::$showPhpErrors) ? 'ob_error_handler' : 'ob_gzhandler';

		return true;
	}



	/* ******************************************** P R I V A T E S ********************************************/

	private static function _activateUTF8Encoding()
	{
		ini_set('default_charset', 'UTF-8');
		mb_internal_encoding('UTF-8');
	}


	private static function _setDebugging()
	{
		if (self::$showPhpErrors)
		{
			ini_set('track_errors', 1);
			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);

			switch (self::$showPhpErrors)
			{
				case 3: error_reporting(-1); break;
				case 2: error_reporting(E_ERROR | E_WARNING | E_PARSE); break;
				case 1: error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE); break;
			}
		}
		else
		{
			ini_set('track_errors', 0);
			ini_set("display_errors", 0);
			error_reporting(0);
		}
	}

	private static function _setTimeZone()
	{
		date_default_timezone_set(self::$timezone);
	}

	private static function _setLocale()
	{
		$ret1 = setlocale(LC_ALL, 'en_US.UTF-8');
		$ret2 = setlocale(LC_TIME, self::$locale);

		return ($ret1 && $ret2);
	}
}
