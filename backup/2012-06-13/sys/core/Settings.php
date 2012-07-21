<?php
/**
*
* This core module will set the following settings:
* 	+ page debug mode and enable/disable loggin
* 	+ character encoding
* 	+ timezone
*
*/
class Settings extends CoreTemplate
{
	/* ******************************************** VARIABLES ********************************************/
	public static $logToFile	= false;
	public static $logToStdOut	= false;
	public static $logFile		= null;
	public static $debugLevel	= 0;

	private static $timezone	= null;
	private static $logDir		= TMP;



	/* ******************************************** OVERRIDE INITIALIZE ********************************************/
	public static function initialize()
	{
		if ( !self::_validateGlobals() )
			return false;

		//
		self::_activateUTF8Encoding();

		// INITIALIZE VALUES
		self::$debugLevel	= $GLOBALS['PROJECT_DEBUG_LEVEL'];
		self::$logFile		= self::$logDir.DS.$GLOBALS['LOG_FILE'];
		self::$logToFile	= $GLOBALS['LOG_TO_FILE'];
		self::$timezone		= $GLOBALS['DEFAULT_TIME_ZONE'];

		if ( self::$logToFile && !is_writable(self::$logFile) )
		{
			self::$error = self::$logFile.' is not writable.';
			return false;
		}

		self::_setDebugging();

		// TODO: need to synchronize with mysql database timezone
		self::_setTimeZone();

		return true;
	}



	/* ******************************************** P R I V A T E S ********************************************/

	private static function _activateUTF8Encoding()
	{
		ini_set('default_charset', 'UTF-8');
		mb_internal_encoding('UTF-8');
	}

	private static function _validateGlobals()
	{
		// ---------- LOGGING DEFINES
		if ( !isset($GLOBALS['PROJECT_DEBUG_LEVEL']) )
		{
			self::$error  = '$PROJECT_DEBUG_LEVEL not defined';
			return false;
		}
		if ( !isset($GLOBALS['LOG_TO_FILE']))
		{
			self::$error  = '$LOG_TO_FILE not defined';
			return false;
		}
		if ( !isset($GLOBALS['LOG_FILE']))
		{
			self::$error  = '$LOG_FILE not defined';
			return false;
		}
		if ( !isset($GLOBALS['DEFAULT_TIME_ZONE']) )
		{
			self::$error  = '$DEFAULT_TIME_ZONE not defined';
			return false;
		}
		
		if ( !is_writable(self::$logDir.DS.$GLOBALS['LOG_FILE']) )
		{
			self::$error  = 'Logfile '.$GLOBALS['LOG_FILE'].' not writable in '.self::$logDir;
			return false;			
		}
		

		// ---------- SQL DEFINES
		if ( !isset($GLOBALS['SQL_HOST']) )
		{
			self::$error  = '$SQL_HOST not defined';
			return false;
		}
		if ( !isset($GLOBALS['SQL_DB']) )
		{
			self::$error  = '$SQL_DB not defined';
			return false;
		}
		if ( !isset($GLOBALS['SQL_USER']) )
		{
			self::$error  = '$SQL_USER not defined';
			return false;
		}
		if ( !isset($GLOBALS['SQL_PASS']) )
		{
			self::$error  = '$SQL_PASS not defined';
			return false;
		}

		// ---------- DEFAULT CONTROLLER ENTRY POINT DEFINES
		if ( !isset($GLOBALS['DEFAULT_CONTROLLER']) )
		{
			self::$error  = '$DEFAULT_CONTROLLER not defined';
			return false;
		}
		if ( !isset($GLOBALS['DEFAULT_METHOD']) )
		{
			self::$error  = '$DEFAULT_METHOD not defined';
			return false;
		}
		if ( !isset($GLOBALS['ERROR_CONTROLLER']) )
		{
			self::$error  = '$ERROR_CONTROLLER not defined';
			return false;
		}
		if ( !isset($GLOBALS['ERROR_METHOD']) )
		{
			self::$error  = '$ERROR_METHOD not defined';
			return false;
		}

		// ---------- BACKEND PATH
		if ( !isset($GLOBALS['BACKEND_URL_PATH']) )
		{
			self::$error  = '$BACKEND_URL_PATH not defined';
			return false;
		}

		// ---------- PACKAGES
		if ( !isset($GLOBALS['USE_PACKAGES']) )
		{
			self::$error  = '$USE_PACKAGES not defined';
			return false;
		}

		// FORM ERROR CSS STYLES
		if ( !isset($GLOBALS['DEFAULT_FORM_TEXT_ERR_CSS']) )
		{
			self::$error = '$DEFAULT_FORM_TEXT_ERR_CSS not defined';
			return false;
		}
		if ( !isset($GLOBALS['DEFAULT_FORM_ELEMENT_ERR_CSS']) )
		{
			self::$error = '$DEFAULT_FORM_ELEMENT_ERR_CSS not defined';
			return false;
		}


		// ---------- HTML SKELETON STUFF
		if ( !isset($GLOBALS['HTML_DEFAULT_LAYOUT']) )
		{
			self::$error  = '$HTML_DEFAULT_LAYOUT not defined';
			return false;
		}
		if ( !isset($GLOBALS['HTML_DEFAULT_LANG_SHORT']) )
		{
			self::$error  = '$HTML_DEFAULT_LANG_SHORT not defined';
			return false;
		}
		if ( !isset($GLOBALS['HTML_DEFAULT_LANG_LONG']) )
		{
			self::$error  = '$HTML_DEFAULT_LANG_LONG not defined';
			return false;
		}
		if ( !isset($GLOBALS['HTML_DEFAULT_PAGE_TITLE']) )
		{
			self::$error  = '$HTML_DEFAULT_PAGE_TITLE not defined';
			return false;
		}
		if ( !isset($GLOBALS['HTML_DEFAULT_PAGE_KEYWORDS']) )
		{
			self::$error  = '$HTML_DEFAULT_PAGE_KEYWORDS not defined';
			return false;
		}
		if ( !isset($GLOBALS['HTML_DEFAULT_PAGE_DESCRIPTION']) )
		{
			self::$error  = '$HTML_DEFAULT_PAGE_DESCRIPTION not defined';
			return false;
		}

		// Password SALT
		if ( !isset($GLOBALS['MY_PWD_SALT']) )
		{
			self::$error  = '$MY_PWD_SALT not defined';
			return false;
		}
		else if ( strlen($GLOBALS['MY_PWD_SALT']) < 10 )
		{
			self::$error  = '$MY_PWD_SALT should have at least 10 characters';
			return false;
		}
		return true;
	}




	private static function _setDebugging()
	{
		if (self::$debugLevel > 0)
		{
			ini_set('track_errors', 1);
			ini_set("display_errors", 1);
			ini_set("html_errors", 1);

			error_reporting(E_ALL | E_STRICT);

			self::$logToStdOut = true;
		}
		else
		{
			ini_set('track_errors', 0);
			ini_set("display_errors", 0);
			error_reporting(0);

			self::$logToStdOut	= false;
		}
	}

	private static function _setTimeZone()
	{
		date_default_timezone_set(self::$timezone);
	}
}
?>