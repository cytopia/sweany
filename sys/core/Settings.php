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
		if ( !isset($GLOBALS['DEFAULT_LAYOUT']) )
		{
			self::$error  = '$DEFAULT_LAYOUT not defined';
			return false;
		}
		if ( !is_file(LAYOUT.DS.'view'.DS.$GLOBALS['DEFAULT_LAYOUT']) )
		{
			self::$error = '$DEFAULT_LAYOUT: '.LAYOUT.DS.'view'.DS.$GLOBALS['DEFAULT_LAYOUT'].' does not exist';
			return false;
		}
		
		/*
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
		if ( !isset($GLOBALS['REDIRECT_CONTROLLER']) )
		{
			self::$error  = '$REDIRECT_CONTROLLER not defined';
			return false;
		}
		if ( !isset($GLOBALS['REDIRECT_METHOD']) )
		{
			self::$error  = '$REDIRECT_METHOD not defined';
			return false;
		}*/
		if ( !isset($GLOBALS['ANY_CONTROLLER_DEFAULT_METHOD']) )
		{
			self::$error = '$ANY_CONTROLLER_DEFAULT_METHOD not defined';
			return false;
		}

		if ( !is_file(VIEW.DS.'FrameworkDefault'.DS.'url_not_found.tpl.php') )
		{
			self::$error = 'The default <strong>error view</strong> does not exist in: '.VIEW.DS.'FrameworkDefault'.DS.'url_not_found.tpl.php';
			return false;
		}
		if ( !is_file(VIEW.DS.'FrameworkDefault'.DS.'info_message.tpl.php') )
		{
			self::$error = 'The default <strong>info message view</strong> does not exist in: '.VIEW.DS.'FrameworkDefault'.DS.'info_message.tpl.php';
			return false;
		}

		if ( !isset($GLOBALS['DEFAULT_INFO_MESSAGE_URL']) )
		{
			self::$error = '$DEFAULT_INFO_MESSAGE_URL not defined';
			return false;
		}

		// EMAIL SETTINGS
		if ( !isset($GLOBALS['EMAIL_SYSTEM_FROM_NAME']) )
		{
			self::$error = '$EMAIL_SYSTEM_FROM_NAME not defined';
			return false;
		}
		if ( !isset($GLOBALS['EMAIL_SYSTEM_FROM_ADDRESS']) )
		{
			self::$error = '$EMAIL_SYSTEM_FROM_ADDRESS not defined';
			return false;
		}
		if ( !filter_var($GLOBALS['EMAIL_SYSTEM_FROM_ADDRESS'], FILTER_VALIDATE_EMAIL) )
		{
			self::$error = '$EMAIL_SYSTEM_FROM_ADDRESS is not a valid email';
			return false;
		}
		if ( !isset($GLOBALS['EMAIL_SYSTEM_REPLY_ADDRESS']) )
		{
			self::$error = '$EMAIL_SYSTEM_REPLY_ADDRESS not defined';
			return false;
		}
		if ( !filter_var($GLOBALS['EMAIL_SYSTEM_REPLY_ADDRESS'], FILTER_VALIDATE_EMAIL) )
		{
			self::$error = '$EMAIL_SYSTEM_REPLY_ADDRESS is not a valid email';
			return false;
		}
		if ( !isset($GLOBALS['EMAIL_SYSTEM_RETURN_EMAIL']) )
		{
			self::$error = '$EMAIL_SYSTEM_RETURN_EMAIL not defined';
			return false;
		}
		if ( !filter_var($GLOBALS['EMAIL_SYSTEM_RETURN_EMAIL'], FILTER_VALIDATE_EMAIL) )
		{
			self::$error = '$EMAIL_SYSTEM_RETURN_EMAIL is not a valid email';
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