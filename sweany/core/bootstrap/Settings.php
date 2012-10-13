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
namespace Sweany;

class Settings extends aBootTemplate
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
	public static function initialize($options = null)
	{
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


		// DEBUGGING MODE
		self::_setDebugging();

		// CHARACTER ENCODING
		if ( !self::_activateUTF8Encoding() )
		{
			self::$error = '<h2>Error Setting Encoding</h2>'.self::$error;
			return false;
		}

		// TIMEZONE
		if ( !self::_setTimeZone() )
		{
			self::$error = '<h2>Error Setting Timezone</h2>'.self::$error;
			return false;
		}

		// DATE OUTPUT FOR YOUR LOCALE
		if ( !self::_setLocale() )
		{
			self::$error = '<h2>Error Setting Locale</h2>'.self::$error;
			return false;
		}
		// Use custom output function in debug mode, otherwise use compression
		self::$ob_callback = (self::$showPhpErrors) ? array('\Sweany\ErrorHandler', 'ob_error_handler') : 'ob_gzhandler';

		return true;
	}



	/* ******************************************** P R I V A T E S ********************************************/

	private static function _activateUTF8Encoding()
	{
		ini_set('default_charset', 'UTF-8');

		if ( !mb_internal_encoding('UTF-8') )
		{
			self::$error = 'Cannot set internal encoding to UTF-8.<br/>mb_internal_encoding(\'UTF-8\'); failed unexpectedly';
			return false;
		}
		return true;
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
		if ( !date_default_timezone_set(self::$timezone) )
		{
			self::$error = 'You have specified an invalid timezone format in config.php: <strong>'.self::$timezone.'</strong>';
			return false;
		}
		return true;
	}


	private static function _setLocale()
	{
		// Try to set everything to an utf-8 standard
		if ( !setlocale(LC_ALL, 'en_US.UTF-8') )
		{
			// Didn't work, use the default
			if ( !setlocale(LC_ALL, null) )
			{
				self::$error = 'Cannot set locale, something is wrong wit your system';
				return false;
			}
		}

		// Overwrite time locales by config.php settings, so that we have
		// timebased output from your language
		// Note: This will be overwritten by the language module (if activated)
		if ( !setlocale(LC_TIME, self::$locale) )
		{
			self::$error = 'You have defined a locale format in config.php that is not supported by your system: <strong>'.self::$locale.'</strong><br/>Consider adjusting that value.';
			return false;
		}
		return true;
	}
}
