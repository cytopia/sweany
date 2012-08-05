<?php
/**
 * This core module will set the following settings:
 * 	+ page debug mode and enable/disable loggin
 * 	+ character encoding
 * 	+ timezone
 *
 *
 * Sweany: MVC-like PHP Framework with blocks and tables (entities)
 * Copyright 2011-2012, Patu
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	Copyright 2011-2012, Patu
 * @link		none yet
 * @package		sweany.core
 * @author		Patu
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @version		0.7 2012-08-03 13:25
 *
 */
namespace Core\Init;

class Validator extends CoreAbstract
{


	/* ******************************************** OVERRIDE INITIALIZE ********************************************/
	public static function initialize()
	{
		if ( !self::validateGlobals() )
		{
			echo '<h1>Validation Error</h2>';
			return false;
		}

		if ( !self::validateLanguage() )
		{
			echo '<h1>Validation Error</h2>';
			return false;
		}

		if ( !self::validateSql() )
		{
			echo '<h1>Validation Error</h2>';
			return false;
		}

		if ( !self::validateUsers() )
		{
			echo '<h1>Validation Error</h2>';
			return false;
		}
		if ( !self::validateOnlineUsers() )
		{
			echo '<h1>Validation Error</h2>';
			return false;
		}
		if ( !self::validateLogVisitors() )
		{
			echo '<h1>Validation Error</h2>';
			return false;
		}
		if ( !self::validateDefaultEntryPoint() )
		{
			echo '<h1>Validation Error</h2>';
			return false;
		}

		return true;
	}



	/* ******************************************** VALIDATORS ********************************************/


	private static function validateGlobals()
	{

		if ( version_compare(phpversion(), '5.3.0', '<') )
		{
			self::$error  = 'You need at least PHP 5.3.0. Your version is: '.phpversion();
			return false;
		}

		/***************************************************************************
		*
		*  DEBUGGING
		*
		***************************************************************************/

		// ---------- Validation Mode
		if ( !isset($GLOBALS['FAST_CORE_MODE']) )
		{
			self::$error  = '<b>$VALIDATION_MODE</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !($GLOBALS['FAST_CORE_MODE'] == 0 || $GLOBALS['FAST_CORE_MODE'] == 1) )
		{
			self::$error  = '<b>$FAST_CORE_MODE</b> has a wrong value in <b>config.php</b>. Can only be <b>0</b> or <b>1</b>.';
			return false;
		}

		// ---------- Validation Mode
		if ( !isset($GLOBALS['VALIDATION_MODE']) )
		{
			self::$error  = '<b>$VALIDATION_MODE</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !($GLOBALS['VALIDATION_MODE'] == 0 || $GLOBALS['VALIDATION_MODE'] == 1) )
		{
			self::$error  = '<b>$VALIDATION_MODE</b> has a wrong value in <b>config.php</b>. Can only be <b>0</b> or <b>1</b>.';
			return false;
		}

		// ---------- Display PHP Erros
		if ( !isset($GLOBALS['SHOW_PHP_ERRORS']))
		{
			self::$error  = '<b>$SHOW_PHP_ERRORS</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( $GLOBALS['SHOW_PHP_ERRORS'] < 0 || $GLOBALS['SHOW_PHP_ERRORS'] > 3 )
		{
			self::$error  = '<b>$SHOW_PHP_ERRORS</b> has a wrong value in <b>config.php</b>. Can only be <b>0</b>, <b>1</b>, <b>2</b> or <b>3</b>.';
			return false;
		}

		// ---------- Display Framework Errors
		if ( !isset($GLOBALS['SHOW_FRAMEWORK_ERRORS']))
		{
			self::$error  = '<b>$SHOW_FRAMEWORK_ERRORS</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( $GLOBALS['SHOW_FRAMEWORK_ERRORS'] < 0 || $GLOBALS['SHOW_FRAMEWORK_ERRORS'] > 3 )
		{
			self::$error  = '<b>$SHOW_FRAMEWORK_ERRORS</b> has a wrong value in <b>config.php</b>. Can only be <b>0</b>, <b>1</b>, <b>2</b> or <b>3</b>.';
			return false;
		}

		// ---------- Display SQL Errors
		if ( !isset($GLOBALS['SHOW_SQL_ERRORS']))
		{
			self::$error  = '<b>$SHOW_SQL_ERRORS</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( $GLOBALS['SHOW_SQL_ERRORS'] < 0 || $GLOBALS['SHOW_SQL_ERRORS'] > 3 )
		{
			self::$error  = '<b>$SHOW_SQL_ERRORS</b> has a wrong value in <b>config.php</b>. Can only be <b>0</b>, <b>1</b>, <b>2</b> or <b>3</b>.';
			return false;
		}

		// ---------- Log PHP Erros
		if ( !isset($GLOBALS['LOG_PHP_ERRORS']))
		{
			self::$error  = '<b>$LOG_PHP_ERRORS</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( $GLOBALS['LOG_PHP_ERRORS'] < 0 || $GLOBALS['LOG_PHP_ERRORS'] > 3 )
		{
			self::$error  = '<b>$LOG_PHP_ERRORS</b> has a wrong value in <b>config.php</b>. Can only be <b>0</b>, <b>1</b>, <b>2</b> or <b>3</b>.';
			return false;
		}

		// ---------- Log Framework Errors
		if ( !isset($GLOBALS['LOG_FRAMEWORK_ERRORS']))
		{
			self::$error  = '<b>$LOG_FRAMEWORK_ERRORS</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( $GLOBALS['LOG_FRAMEWORK_ERRORS'] < 0 || $GLOBALS['LOG_FRAMEWORK_ERRORS'] > 3 )
		{
			self::$error  = '<b>$LOG_FRAMEWORK_ERRORS</b> has a wrong value in <b>config.php</b>. Can only be <b>0</b>, <b>1</b>, <b>2</b> or <b>3</b>.';
			return false;
		}

		// ---------- Log SQL Errors
		if ( !isset($GLOBALS['LOG_SQL_ERRORS']))
		{
			self::$error  = '<b>$LOG_SQL_ERRORS</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( $GLOBALS['LOG_SQL_ERRORS'] < 0 || $GLOBALS['LOG_SQL_ERRORS'] > 3 )
		{
			self::$error  = '<b>$LOG_SQL_ERRORS</b> has a wrong value in <b>config.php</b>. Can only be <b>0</b>, <b>1</b>, <b>2</b> or <b>3</b>.';
			return false;
		}

		// ---------- File loggin Mode
		if ( !isset($GLOBALS['FILE_LOG_CORE']))
		{
			self::$error  = '<b>$FILE_LOG_CORE</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !file_exists(LOG_PATH.DS.$GLOBALS['FILE_LOG_CORE']) )
		{
			self::$error  = 'Logfile <b>'.$GLOBALS['FILE_LOG_CORE'].'</b> does not exist in '.LOG_PATH.DS;
			return false;
		}
		if ( !is_writable(LOG_PATH.DS.$GLOBALS['FILE_LOG_CORE']) )
		{
			self::$error  = 'Logfile <b>'.$GLOBALS['FILE_LOG_CORE'].'/<b> not writable in '.LOG_PATH.DS;
			return false;
		}
		if ( !file_exists(LOG_PATH.DS.$GLOBALS['FILE_LOG_USER']) )
		{
			self::$error  = 'Logfile <b>'.$GLOBALS['FILE_LOG_USER'].'</b> does not exist in '.self::$logDir.DS;
			return false;
		}
		if ( !isset($GLOBALS['FILE_LOG_USER']))
		{
			self::$error  = '<b>$FILE_LOG_USR</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !is_writable(LOG_PATH.DS.$GLOBALS['FILE_LOG_USER']) )
		{
			self::$error  = 'Logfile <b>'.$GLOBALS['FILE_LOG_USER'].'</b> not writable in '.LOG_PATH.DS;
			return false;
		}


		/***************************************************************************
		*
		*  Default Defines
		*
		***************************************************************************/


		// ---------- Time Zone
		if ( !isset($GLOBALS['DEFAULT_TIME_ZONE']) )
		{
			self::$error  = '<b>$DEFAULT_TIME_ZONE</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !(bool)(in_array($GLOBALS['DEFAULT_TIME_ZONE'], timezone_identifiers_list())) )
		{
			self::$error  = '<b>$DEFAULT_TIME_ZONE</b> has a wrong value in <b>config.php</b>';
			return false;
		}

		// ---------- Controller
		if ( !isset($GLOBALS['DEFAULT_CONTROLLER']) )
		{
			self::$error  = '<b>$DEFAULT_CONTROLLER</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !file_exists(PAGES_CONTROLLER_PATH.DS.$GLOBALS['DEFAULT_CONTROLLER'].'.php') )
		{
			self::$error  = 'The defined $DEFAULT_CONTROLLER: <b>'.$GLOBALS['DEFAULT_CONTROLLER'].'.php</b> does not exist in '.PAGES_CONTROLLER_PATH.DS;
			return false;
		}
		// ---------- Method
		if ( !isset($GLOBALS['DEFAULT_METHOD']) )
		{
			self::$error  = '<b>$DEFAULT_METHOD</b> not defined in <b>config.php</b>';
			return false;
		}

		if ( !isset($GLOBALS['ANY_CONTROLLER_DEFAULT_METHOD']) )
		{
			self::$error  = '<b>$ANY_CONTROLLER_DEFAULT_METHOD</b> not defined in <b>config.php</b>';
			return false;
		}

		if ( !isset($GLOBALS['DEFAULT_LAYOUT']) )
		{
			self::$error  = '<b>$DEFAULT_LAYOUT</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !is_file(USR_LAYOUTS_PATH.DS.'view'.DS.$GLOBALS['DEFAULT_LAYOUT']) )
		{
			self::$error = '$DEFAULT_LAYOUT: '.USR_LAYOUTS_PATH.DS.'view'.DS.$GLOBALS['DEFAULT_LAYOUT'].' does not exist';
			return false;
		}

		if ( !isset($GLOBALS['DEFAULT_INFO_MESSAGE_URL']) )
		{
			self::$error  = '<b>$DEFAULT_INFO_MESSAGE_URL</b> not defined in <b>config.php</b>';
			return false;
		}
		// TODO: also check if there is any controller in 'pages' or 'plugins' with the same name

		if ( !isset($GLOBALS['DEFAULT_SETTINGS_URL']) )
		{
			self::$error  = '<b>$DEFAULT_SETTINGS_URL</b> not defined in <b>config.php</b>';
			return false;
		}
		// TODO: also check if there is any controller in 'pages' or 'plugins' with the same name



		/***************************************************************************
		*
		*  Email Settings
		*
		***************************************************************************/

		if ( !isset($GLOBALS['EMAIL_SYSTEM_FROM_NAME']) )
		{
			self::$error = '<b>$EMAIL_SYSTEM_FROM_NAME</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['EMAIL_SYSTEM_FROM_ADDRESS']) )
		{
			self::$error = '<b>$EMAIL_SYSTEM_FROM_ADDRESS</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !filter_var($GLOBALS['EMAIL_SYSTEM_FROM_ADDRESS'], FILTER_VALIDATE_EMAIL) )
		{
			self::$error = '<b>$EMAIL_SYSTEM_FROM_ADDRESS</b> is not a valid email';
			return false;
		}
		if ( !isset($GLOBALS['EMAIL_SYSTEM_REPLY_ADDRESS']) )
		{
			self::$error = '<b>$EMAIL_SYSTEM_REPLY_ADDRESS</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !filter_var($GLOBALS['EMAIL_SYSTEM_REPLY_ADDRESS'], FILTER_VALIDATE_EMAIL) )
		{
			self::$error = '<b>$EMAIL_SYSTEM_REPLY_ADDRESS</b> is not a valid email';
			return false;
		}
		if ( !isset($GLOBALS['EMAIL_SYSTEM_RETURN_EMAIL']) )
		{
			self::$error = '<b>$EMAIL_SYSTEM_RETURN_EMAIL</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !filter_var($GLOBALS['EMAIL_SYSTEM_RETURN_EMAIL'], FILTER_VALIDATE_EMAIL) )
		{
			self::$error = '<b>$EMAIL_SYSTEM_RETURN_EMAIL</b> is not a valid email';
			return false;
		}


		/***************************************************************************
		 *
		*  HTML Skeleton Defines
		*
		***************************************************************************/
		if ( !isset($GLOBALS['HTML_DEFAULT_SKELETON']) )
		{
			self::$error = '<b>$HTML_DEFAULT_SKELETON</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !file_exists(USR_SKELETONS_PATH.DS.$GLOBALS['HTML_DEFAULT_SKELETON']) )
		{
			self::$error  = 'Html skeleton <b>'.$GLOBALS['HTML_DEFAULT_SKELETON'].'</b> does not exist in '.USR_SKELETONS_PATH.DS;
			return false;
		}
		if ( !isset($GLOBALS['HTML_DEFAULT_LANG_SHORT']) )
		{
			self::$error = '<b>$HTML_DEFAULT_LANG_SHORT</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !in_array($GLOBALS['HTML_DEFAULT_LANG_SHORT'], self::_get_iso639_1_languageCode()) )
		{
			self::$error = '<b>$HTML_DEFAULT_LANG_SHORT: '.$GLOBALS['HTML_DEFAULT_LANG_SHORT'].'</b> is not a valid code by <b>ISO-639-1</b>';
			return false;
		}
		if ( !isset($GLOBALS['HTML_DEFAULT_LANG_LONG']) )
		{
			self::$error = '<b>$HTML_DEFAULT_LANG_LONG</b> not defined in <b>config.php</b>';
			return false;
		}
		$lang = explode('-', $GLOBALS['HTML_DEFAULT_LANG_LONG']);
		if ( sizeof($lang) != 2 )
		{
			self::$error = '<b>$HTML_DEFAULT_LANG_LONG: '.$GLOBALS['HTML_DEFAULT_LANG_LONG'].'</b> has a wrong format. (<ISO639-1 Langcode>-<ISO3166 CountryCode>';
			return false;
		}
		if (!in_array($lang[0], self::_get_iso639_1_languageCode()))
		{
			self::$error = 'Wrong Language Code in <b>$HTML_DEFAULT_LANG_LONG: '.$lang[0].'</b>';
			return false;
		}
		if (!in_array($lang[1], self::_get_iso3166_countryCode()))
		{
			self::$error = 'Wrong Country Code in <b>$HTML_DEFAULT_LANG_LONG: '.$lang[1].'</b>';
			return false;
		}

		if ( !isset($GLOBALS['HTML_DEFAULT_PAGE_TITLE']) )
		{
			self::$error = '<b>$HTML_DEFAULT_PAGE_TITLE</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['HTML_DEFAULT_PAGE_KEYWORDS']) )
		{
			self::$error = '<b>$HTML_DEFAULT_PAGE_KEYWORDS</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['HTML_DEFAULT_PAGE_DESCRIPTION']) )
		{
			self::$error = '<b>$HTML_DEFAULT_PAGE_DESCRIPTION</b> not defined in <b>config.php</b>';
			return false;
		}

		/***************************************************************************
		*
		*  Form CSS
		*
		***************************************************************************/
		if ( !isset($GLOBALS['DEFAULT_FORM_TEXT_ERR_CSS']) )
		{
			self::$error = '<b>$DEFAULT_FORM_TEXT_ERR_CSS</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['DEFAULT_FORM_ELEMENT_ERR_CSS']) )
		{
			self::$error = '<b>$DEFAULT_FORM_ELEMENT_ERR_CSS</b> not defined in <b>config.php</b>';
			return false;
		}
		return true;
	}



	private static function validateLanguage()
	{
		if ( !isset($GLOBALS['LANGUAGE_ENABLE']) )
		{
			self::$error = '<b>$LANGUAGE_ENABLE</b> not defined in <b>config.php</b>';
			return false;
		}

		if ( !isset($GLOBALS['LANGUAGE_DEFAULT_SHORT']) )
		{
			self::$error = '<b>$LANGUAGE_DEFAULT_SHORT</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !in_array($GLOBALS['LANGUAGE_DEFAULT_SHORT'], self::_get_iso639_1_languageCode()) )
		{
			self::$error = '<b>$LANGUAGE_DEFAULT_SHORT: '.$GLOBALS['LANGUAGE_DEFAULT_SHORT'].'</b> is not a valid code by <b>ISO-639-1</b>';
			return false;
		}

		if ( !isset($GLOBALS['LANGUAGE_DEFAULT_LONG']) )
		{
			self::$error = '<b>$HTML_DEFAULT_LANG_LONG</b> not defined in <b>config.php</b>';
			return false;
		}
		$lang = explode('-', $GLOBALS['LANGUAGE_DEFAULT_LONG']);
		if ( sizeof($lang) != 2 )
		{
			self::$error = '<b>$LANGUAGE_DEFAULT_LONG: '.$GLOBALS['LANGUAGE_DEFAULT_LONG'].'</b> has a wrong format. (<ISO639-1 Langcode>-<ISO3166 CountryCode>';
			return false;
		}
		if (!in_array($lang[0], self::_get_iso639_1_languageCode()))
		{
			self::$error = 'Wrong Language Code in <b>$LANGUAGE_DEFAULT_LONG: '.$lang[0].'</b>';
			return false;
		}
		if (!in_array($lang[1], self::_get_iso3166_countryCode()))
		{
			self::$error = 'Wrong Country Code in <b>$LANGUAGE_DEFAULT_LONG: '.$lang[1].'</b>';
			return false;
		}

		if ( !isset($GLOBALS['LANGUAGE_AVAILABLE']) )
		{
			self::$error = '<b>$LANGUAGE_AVAILABLE</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['LANGUAGE_IMG_PATH']) )
		{
			self::$error = '<b>$LANGUAGE_IMG_PATH</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !file_exists(ROOT.DS.'www'.DS.$GLOBALS['LANGUAGE_IMG_PATH']) )
		{
			self::$error = '$LANGUAGE_IMG_PATH: <b>'.$GLOBALS['LANGUAGE_IMG_PATH'].'</b> does not exist in: '.ROOT.DS.'www'.DS;
			return false;
		}

		// check language array keys
		$langObj = array();
		libxml_use_internal_errors(true);

		foreach ( $GLOBALS['LANGUAGE_AVAILABLE'] as $key => $name)
		{
			if (!in_array($key, self::_get_iso639_1_languageCode()))
			{
				self::$error = '<b>$LANGUAGE_AVAILABLE</b> key <b>'.$key.'</b> is not valid by ISO 639-1.';
				return false;
			}
			if ( !strlen($name) )
			{
				self::$error = '<b>$LANGUAGE_AVAILABLE</b> value <b>'.$name.'</b> of key <b>'.$key.'</b> is not set';
				return false;
			}
			// Check Language flag
			if ( !file_exists(ROOT.DS.'www'.DS.$GLOBALS['LANGUAGE_IMG_PATH'].DS.$key.'.png') )
			{
				self::$error = '<b>Missing Language Flag Image:</b> '.ROOT.DS.'www'.DS.$GLOBALS['LANGUAGE_IMG_PATH'].DS.$key.'.png';
				return false;
			}
			// Check Language xml
			if ( !file_exists(USR_LANGUAGES_PATH.DS.$key.'.xml') )
			{
				self::$error = '<b>Missing Language XML file:</b> '.USR_LANGUAGES_PATH.DS.$key.'.xml';
				return false;
			}

			// Check xml syntax
			$langObj[$key] = simplexml_load_file(USR_LANGUAGES_PATH.DS.$key.'.xml');
			if (!$langObj[$key])
			{
				self::$error = 'Loading of '.USR_LANGUAGES_PATH.DS.$key.'.xml'.' has failed<br/>';
				foreach(libxml_get_errors() as $error)
				{
					self::$error.= $error->message.'<br/>';
				}
				return false;
			}

			// Check basic xml elements
			if ( $langObj[$key]->xpath('/root') === false)
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root</b>';
				return false;
			}
			if ( $langObj[$key]->xpath('/root/core') === false)
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core</b>';
				return false;
			}
			if ( $langObj[$key]->xpath('/root/core/settings') === false)
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/settings</b>';
				return false;
			}

			// /root/core/settings
			$langTmp = $langObj[$key]->xpath('/root/core/settings');
			if ( !isset($langTmp[0]->lang_name) )
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/settings/lang_name</b>';
				return false;
			}
			if ( !isset($langTmp[0]->lang_short) )
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/settings/lang_short</b>';
				return false;
			}
			if ( !isset($langTmp[0]->lang_long) )
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/settings/lang_long</b>';
				return false;
			}


			if ( $langObj[$key]->xpath('/root/core/default') === false)
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/default</b>';
				return false;
			}

			if ( $langObj[$key]->xpath('/root/core/default/page[@id="notFound"]') === false)
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/default/page id="notFound"</b>';
				return false;
			}

			$langTmp = $langObj[$key]->xpath('/root/core/default/page[@id="notFound"]');
			if ( !isset($langTmp[0]->title) )
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/default/page[@id="notFound"]/title</b>';
				return false;
			}
			if ( !isset($langTmp[0]->text) )
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/default/page[@id="notFound"]/text</b>';
				return false;
			}

			if ( $langObj[$key]->xpath('/root/core/default/page[@id="redirect"]') === false)
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/default/page id="redirect"</b>';
				return false;
			}

			$langTmp = $langObj[$key]->xpath('/root/core/default/page[@id="redirect"]');
			if ( !isset($langTmp[0]->title) )
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/default/page[@id="redirect"]/title</b>';
				return false;
			}
			if ( !isset($langTmp[0]->text) )
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/default/page[@id="redirect"]/text</b>';
				return false;
			}

			if ( $langObj[$key]->xpath('/root/core/default/page[@id="changeLanguage"]') === false)
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/default/page id="changeLanguage"</b>';
				return false;
			}

			$langTmp = $langObj[$key]->xpath('/root/core/default/page[@id="changeLanguage"]');
			if ( !isset($langTmp[0]->title) )
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/default/page[@id="changeLanguage"]/title</b>';
				return false;
			}
			if ( !isset($langTmp[0]->text) )
			{
				self::$error = '<b>'.$key.'.xml</b> does not have required section <b>/root/core/default/page[@id="changeLanguage"]/text</b>';
				return false;
			}

		}
		// TODO: need to compare all language xml files, to check if one has some sections missing
		return true;
	}



	/***************************************************************************
	 *
	*  Database
	*
	***************************************************************************/

	private static function validateSql()
	{
		// ---------- Check database
		if ( !isset($GLOBALS['SQL_ENABLE']) )
		{
			self::$error  = '<b>$SQL_ENABLE</b> not defined in <b>config.php</b>';
			return false;
		}
		// ---------- Check database
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

		// This is needed to be initialized here as well,
		// so we have access to tables in the controller and can verify them too
		// This won't be called if validation mode is off, so no worry for two calls
		if ( !\Core\Init\CoreMySql::initialize() )
		{
			self::$error  = \Core\Init\CoreMySql::getError();
			self::$error  .= '<br/>Cannot initialize Database connection';
			return false;
		}

		return true;
	}

	private static function validateUsers()
	{
		// Password SALT
		if ( !isset($GLOBALS['USER_PWD_SALT']) )
		{
			self::$error  = '<b>$USER_PWD_SALT</b> not defined in <b>config.php</b>';
			return false;
		}
		else if ( strlen($GLOBALS['USER_PWD_SALT']) < 10 )
		{
			self::$error  = '<b>$USER_PWD_SALT</b> should have at least 10 characters';
			return false;
		}


		// CHECK SQL TABLES
		if ( count(\Core\Init\CoreMySql::select("show tables  from `$GLOBALS[SQL_DB]` like 'users'; ")) < 1 )
		{
			self::$error  = 'MySQL: <b>users</b> table does not exist';
			return false;
		}
		if ( count(\Core\Init\CoreMySql::select("show tables  from `$GLOBALS[SQL_DB]` like 'user_failed_logins'; ")) < 1 )
		{
			self::$error  = 'MySQL: <b>user_failed_logins</b> table does not exist';
			return false;
		}
		return true;
	}
	private static function validateOnlineUsers()
	{
		// Password SALT
		if ( !isset($GLOBALS['USER_ONLINE_COUNT_ENABLE']) )
		{
			self::$error  = '<b>$USER_ONLINE_COUNT_ENABLE</b> not defined in <b>config.php</b>';
			return false;
		}

		// CHECK SQL TABLES
		if ( count(\Core\Init\CoreMySql::select("show tables  from `$GLOBALS[SQL_DB]` like 'user_online'; ")) < 1 )
		{
			self::$error  = 'MySQL: <b>user_online</b> table does not exist';
			return false;
		}
		return true;
	}
	private static function validateLogVisitors()
	{
		// Password SALT
		if ( !isset($GLOBALS['SQL_LOG_VISITORS']) )
		{
			self::$error  = '<b>$SQL_LOG_VISITORS</b> not defined in <b>config.php</b>';
			return false;
		}

		// CHECK SQL TABLES
		if ( count(\Core\Init\CoreMySql::select("show tables  from `$GLOBALS[SQL_DB]` like 'visitors'; ")) < 1 )
		{
			self::$error  = 'MySQL: <b>visitors</b> table does not exist';
			return false;
		}
		return true;
	}



	private static function validateDefaultEntryPoint()
	{
		/*
		require(PAGES_CONTROLLER_PATH.DS.$GLOBALS['DEFAULT_CONTROLLER'].'.php');
		$defObject = new $GLOBALS['DEFAULT_CONTROLLER'];

		if ( !method_exists($defObject,$GLOBALS['DEFAULT_METHOD']) )
		{
			self::$error  = 'Default Controller/Method: <b>'.$GLOBALS['DEFAULT_CONTROLLER'].'->'.$GLOBALS['DEFAULT_METHOD'].'()</b> is not callable';
			return false;
		}
		unset($defObject);
		*/
		return true;
	}






	/* ******************************************** PRIVATES ********************************************/

	private static function _get_iso3166_countryCode()
	{
		$arr   = array();

		$arr[] = 'AF';
		$arr[] = 'AX';
		$arr[] = 'AL';
		$arr[] = 'DZ';
		$arr[] = 'AS';
		$arr[] = 'AD';
		$arr[] = 'AO';
		$arr[] = 'AI';
		$arr[] = 'AQ';
		$arr[] = 'AG';
		$arr[] = 'AR';
		$arr[] = 'AM';
		$arr[] = 'AW';
		$arr[] = 'AU';
		$arr[] = 'AT';
		$arr[] = 'AZ';
		$arr[] = 'BS';
		$arr[] = 'BH';
		$arr[] = 'BD';
		$arr[] = 'BB';
		$arr[] = 'BY';
		$arr[] = 'BE';
		$arr[] = 'BZ';
		$arr[] = 'BJ';
		$arr[] = 'BM';
		$arr[] = 'BT';
		$arr[] = 'BO';
		$arr[] = 'BA';
		$arr[] = 'BW';
		$arr[] = 'BV';
		$arr[] = 'BR';
		$arr[] = 'IO';
		$arr[] = 'BN';
		$arr[] = 'BG';
		$arr[] = 'BF';
		$arr[] = 'BI';
		$arr[] = 'KH';
		$arr[] = 'CM';
		$arr[] = 'CA';
		$arr[] = 'CV';
		$arr[] = 'KY';
		$arr[] = 'CF';
		$arr[] = 'TD';
		$arr[] = 'CL';
		$arr[] = 'CN';
		$arr[] = 'CX';
		$arr[] = 'CC';
		$arr[] = 'CO';
		$arr[] = 'KM';
		$arr[] = 'CG';
		$arr[] = 'CD';
		$arr[] = 'CK';
		$arr[] = 'CR';
		$arr[] = 'CI';
		$arr[] = 'HR';
		$arr[] = 'CU';
		$arr[] = 'CY';
		$arr[] = 'CZ';
		$arr[] = 'DK';
		$arr[] = 'DJ';
		$arr[] = 'DM';
		$arr[] = 'DO';
		$arr[] = 'EC';
		$arr[] = 'EG';
		$arr[] = 'SV';
		$arr[] = 'GQ';
		$arr[] = 'ER';
		$arr[] = 'EE';
		$arr[] = 'ET';
		$arr[] = 'FK';
		$arr[] = 'FO';
		$arr[] = 'FJ';
		$arr[] = 'FI';
		$arr[] = 'FR';
		$arr[] = 'GF';
		$arr[] = 'PF';
		$arr[] = 'TF';
		$arr[] = 'GA';
		$arr[] = 'GM';
		$arr[] = 'GE';
		$arr[] = 'DE';
		$arr[] = 'GH';
		$arr[] = 'GI';
		$arr[] = 'GR';
		$arr[] = 'GL';
		$arr[] = 'GD';
		$arr[] = 'GP';
		$arr[] = 'GU';
		$arr[] = 'GT';
		$arr[] = 'GG';
		$arr[] = 'GN';
		$arr[] = 'GW';
		$arr[] = 'GY';
		$arr[] = 'HT';
		$arr[] = 'HM';
		$arr[] = 'VA';
		$arr[] = 'HN';
		$arr[] = 'HK';
		$arr[] = 'HU';
		$arr[] = 'IS';
		$arr[] = 'IN';
		$arr[] = 'ID';
		$arr[] = 'IR';
		$arr[] = 'IQ';
		$arr[] = 'IE';
		$arr[] = 'IM';
		$arr[] = 'IL';
		$arr[] = 'IT';
		$arr[] = 'JM';
		$arr[] = 'JP';
		$arr[] = 'JE';
		$arr[] = 'JO';
		$arr[] = 'KZ';
		$arr[] = 'KE';
		$arr[] = 'KI';
		$arr[] = 'KP';
		$arr[] = 'KR';
		$arr[] = 'KW';
		$arr[] = 'KG';
		$arr[] = 'LA';
		$arr[] = 'LV';
		$arr[] = 'LB';
		$arr[] = 'LS';
		$arr[] = 'LR';
		$arr[] = 'LY';
		$arr[] = 'LI';
		$arr[] = 'LT';
		$arr[] = 'LU';
		$arr[] = 'MO';
		$arr[] = 'MK';
		$arr[] = 'MG';
		$arr[] = 'MW';
		$arr[] = 'MY';
		$arr[] = 'MV';
		$arr[] = 'ML';
		$arr[] = 'MT';
		$arr[] = 'MH';
		$arr[] = 'MQ';
		$arr[] = 'MR';
		$arr[] = 'MU';
		$arr[] = 'YT';
		$arr[] = 'MX';
		$arr[] = 'FM';
		$arr[] = 'MD';
		$arr[] = 'MC';
		$arr[] = 'MN';
		$arr[] = 'ME';
		$arr[] = 'MS';
		$arr[] = 'MA';
		$arr[] = 'MZ';
		$arr[] = 'MM';
		$arr[] = 'NA';
		$arr[] = 'NR';
		$arr[] = 'NP';
		$arr[] = 'NL';
		$arr[] = 'AN';
		$arr[] = 'NC';
		$arr[] = 'NZ';
		$arr[] = 'NI';
		$arr[] = 'NE';
		$arr[] = 'NG';
		$arr[] = 'NU';
		$arr[] = 'NF';
		$arr[] = 'MP';
		$arr[] = 'NO';
		$arr[] = 'OM';
		$arr[] = 'PK';
		$arr[] = 'PW';
		$arr[] = 'PS';
		$arr[] = 'PA';
		$arr[] = 'PG';
		$arr[] = 'PY';
		$arr[] = 'PE';
		$arr[] = 'PH';
		$arr[] = 'PN';
		$arr[] = 'PL';
		$arr[] = 'PT';
		$arr[] = 'PR';
		$arr[] = 'QA';
		$arr[] = 'RE';
		$arr[] = 'RO';
		$arr[] = 'RU';
		$arr[] = 'RW';
		$arr[] = 'SH';
		$arr[] = 'KN';
		$arr[] = 'LC';
		$arr[] = 'PM';
		$arr[] = 'VC';
		$arr[] = 'WS';
		$arr[] = 'SM';
		$arr[] = 'ST';
		$arr[] = 'SA';
		$arr[] = 'SN';
		$arr[] = 'RS';
		$arr[] = 'SC';
		$arr[] = 'SL';
		$arr[] = 'SG';
		$arr[] = 'SK';
		$arr[] = 'SI';
		$arr[] = 'SB';
		$arr[] = 'SO';
		$arr[] = 'ZA';
		$arr[] = 'GS';
		$arr[] = 'ES';
		$arr[] = 'LK';
		$arr[] = 'SD';
		$arr[] = 'SR';
		$arr[] = 'SJ';
		$arr[] = 'SZ';
		$arr[] = 'SE';
		$arr[] = 'CH';
		$arr[] = 'SY';
		$arr[] = 'TW';
		$arr[] = 'TJ';
		$arr[] = 'TZ';
		$arr[] = 'TH';
		$arr[] = 'TL';
		$arr[] = 'TG';
		$arr[] = 'TK';
		$arr[] = 'TO';
		$arr[] = 'TT';
		$arr[] = 'TN';
		$arr[] = 'TR';
		$arr[] = 'TM';
		$arr[] = 'TC';
		$arr[] = 'TV';
		$arr[] = 'UG';
		$arr[] = 'UA';
		$arr[] = 'AE';
		$arr[] = 'GB';
		$arr[] = 'US';
		$arr[] = 'UM';
		$arr[] = 'UY';
		$arr[] = 'UZ';
		$arr[] = 'VU';
		$arr[] = 'VA';
		$arr[] = 'VE';
		$arr[] = 'VN';
		$arr[] = 'VG';
		$arr[] = 'VI';
		$arr[] = 'WF';
		$arr[] = 'EH';
		$arr[] = 'YE';
		$arr[] = 'CD';
		$arr[] = 'ZM';
		$arr[] = 'ZW';
		return $arr;
	}

	private static function _get_iso639_1_languageCode()
	{
		$arr   = array();
		$arr[] = 'aa';
		$arr[] = 'ab';
		$arr[] = 'af';
		$arr[] = 'ak';
		$arr[] = 'sq';
		$arr[] = 'am';
		$arr[] = 'ar';
		$arr[] = 'an';
		$arr[] = 'hy';
		$arr[] = 'as';
		$arr[] = 'av';
		$arr[] = 'ae';
		$arr[] = 'ay';
		$arr[] = 'az';
		$arr[] = 'ba';
		$arr[] = 'bm';
		$arr[] = 'eu';
		$arr[] = 'be';
		$arr[] = 'bn';
		$arr[] = 'bh';
		$arr[] = 'bi';
		$arr[] = 'bo';
		$arr[] = 'bs';
		$arr[] = 'br';
		$arr[] = 'bg';
		$arr[] = 'my';
		$arr[] = 'ca';
		$arr[] = 'cs';
		$arr[] = 'ch';
		$arr[] = 'ce';
		$arr[] = 'zh';
		$arr[] = 'cu';
		$arr[] = 'cv';
		$arr[] = 'kw';
		$arr[] = 'co';
		$arr[] = 'cr';
		$arr[] = 'cy';
		$arr[] = 'cs';
		$arr[] = 'da';
		$arr[] = 'de';
		$arr[] = 'dv';
		$arr[] = 'nl';
		$arr[] = 'dz';
		$arr[] = 'el';
		$arr[] = 'en';
		$arr[] = 'eo';
		$arr[] = 'et';
		$arr[] = 'eu';
		$arr[] = 'ee';
		$arr[] = 'fo';
		$arr[] = 'fa';
		$arr[] = 'fj';
		$arr[] = 'fi';
		$arr[] = 'fr';
		$arr[] = 'fy';
		$arr[] = 'ff';
		$arr[] = 'ka';
		$arr[] = 'de';
		$arr[] = 'gd';
		$arr[] = 'ga';
		$arr[] = 'gl';
		$arr[] = 'gv';
		$arr[] = 'el';
		$arr[] = 'gn';
		$arr[] = 'gu';
		$arr[] = 'ht';
		$arr[] = 'ha';
		$arr[] = 'he';
		$arr[] = 'hz';
		$arr[] = 'hi';
		$arr[] = 'ho';
		$arr[] = 'hr';
		$arr[] = 'hu';
		$arr[] = 'hy';
		$arr[] = 'ig';
		$arr[] = 'is';
		$arr[] = 'io';
		$arr[] = 'ii';
		$arr[] = 'iu';
		$arr[] = 'ie';
		$arr[] = 'ia';
		$arr[] = 'id';
		$arr[] = 'ik';
		$arr[] = 'is';
		$arr[] = 'it';
		$arr[] = 'jv';
		$arr[] = 'ja';
		$arr[] = 'kl';
		$arr[] = 'kn';
		$arr[] = 'ks';
		$arr[] = 'ka';
		$arr[] = 'kr';
		$arr[] = 'kk';
		$arr[] = 'km';
		$arr[] = 'ki';
		$arr[] = 'rw';
		$arr[] = 'ky';
		$arr[] = 'kv';
		$arr[] = 'kg';
		$arr[] = 'ko';
		$arr[] = 'kj';
		$arr[] = 'ku';
		$arr[] = 'lo';
		$arr[] = 'la';
		$arr[] = 'lv';
		$arr[] = 'li';
		$arr[] = 'ln';
		$arr[] = 'lt';
		$arr[] = 'lb';
		$arr[] = 'lu';
		$arr[] = 'lg';
		$arr[] = 'mk';
		$arr[] = 'mh';
		$arr[] = 'ml';
		$arr[] = 'mi';
		$arr[] = 'mr';
		$arr[] = 'ms';
		$arr[] = 'mk';
		$arr[] = 'mg';
		$arr[] = 'mt';
		$arr[] = 'mo';
		$arr[] = 'mn';
		$arr[] = 'mi';
		$arr[] = 'ms';
		$arr[] = 'my';
		$arr[] = 'na';
		$arr[] = 'nv';
		$arr[] = 'nr';
		$arr[] = 'nd';
		$arr[] = 'ng';
		$arr[] = 'ne';
		$arr[] = 'nl';
		$arr[] = 'nn';
		$arr[] = 'nb';
		$arr[] = 'no';
		$arr[] = 'ny';
		$arr[] = 'oc';
		$arr[] = 'oj';
		$arr[] = 'or';
		$arr[] = 'om';
		$arr[] = 'os';
		$arr[] = 'pa';
		$arr[] = 'fa';
		$arr[] = 'pi';
		$arr[] = 'pl';
		$arr[] = 'pt';
		$arr[] = 'ps';
		$arr[] = 'qu';
		$arr[] = 'rm';
		$arr[] = 'ro';
		$arr[] = 'ro';
		$arr[] = 'rn';
		$arr[] = 'ru';
		$arr[] = 'sg';
		$arr[] = 'sa';
		$arr[] = 'sr';
		$arr[] = 'hr';
		$arr[] = 'si';
		$arr[] = 'sk';
		$arr[] = 'sk';
		$arr[] = 'sl';
		$arr[] = 'se';
		$arr[] = 'sm';
		$arr[] = 'sn';
		$arr[] = 'sd';
		$arr[] = 'so';
		$arr[] = 'st';
		$arr[] = 'es';
		$arr[] = 'sq';
		$arr[] = 'sc';
		$arr[] = 'sr';
		$arr[] = 'ss';
		$arr[] = 'su';
		$arr[] = 'sw';
		$arr[] = 'sv';
		$arr[] = 'ty';
		$arr[] = 'ta';
		$arr[] = 'tt';
		$arr[] = 'te';
		$arr[] = 'tg';
		$arr[] = 'tl';
		$arr[] = 'th';
		$arr[] = 'bo';
		$arr[] = 'ti';
		$arr[] = 'to';
		$arr[] = 'tn';
		$arr[] = 'ts';
		$arr[] = 'tk';
		$arr[] = 'tr';
		$arr[] = 'tw';
		$arr[] = 'ug';
		$arr[] = 'uk';
		$arr[] = 'ur';
		$arr[] = 'uz';
		$arr[] = 've';
		$arr[] = 'vi';
		$arr[] = 'vo';
		$arr[] = 'cy';
		$arr[] = 'wa';
		$arr[] = 'wo';
		$arr[] = 'xh';
		$arr[] = 'yi';
		$arr[] = 'yo';
		$arr[] = 'za';
		$arr[] = 'zh';
		$arr[] = 'zu';
		return $arr;
	}
}
