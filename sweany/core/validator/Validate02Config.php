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
class Validate02Config extends aBootTemplate
{


	/* ******************************************** OVERRIDE INITIALIZE ********************************************/
	public static function initialize($options = null)
	{
		if ( !self::_checkVariableExistance() )
		{
			echo '<h1>Validation Error: Variable missing in Config.php</h2>';
			echo self::$error;
			return false;
		}
		if ( !self::_checkVariableValue() )
		{
			echo '<h1>Validation Error: Variable with wrong value in Config.php</h2>';
			echo self::$error;
			return false;
		}
		return true;
	}



	/* ******************************************** VALIDATORS ********************************************/


	private static function _checkVariableExistance()
	{
		/***************************************************************************
		 *
		 *  Debug Defines
		 *
		 ***************************************************************************/

		// ---------- Fast Core Mode
		if ( !isset($GLOBALS['RUNTIME_MODE']) )
		{
			self::$error  = '<b>$RUNTIME_MODE</b> not defined in <b>config.php</b>';
			return false;
		}

		// ---------- Validation Mode
		if ( !isset($GLOBALS['VALIDATION_MODE']) )
		{
			self::$error  = '<b>$VALIDATION_MODE</b> not defined in <b>config.php</b>';
			return false;
		}

		// ---------- Display Erros
		if ( !isset($GLOBALS['SHOW_PHP_ERRORS']) )
		{
			self::$error  = '<b>$SHOW_PHP_ERRORS</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['SHOW_FRAMEWORK_ERRORS']) )
		{
			self::$error  = '<b>$SHOW_FRAMEWORK_ERRORS</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['SHOW_SQL_ERRORS']) )
		{
			self::$error  = '<b>$SHOW_SQL_ERRORS</b> not defined in <b>config.php</b>';
			return false;
		}

		// ---------- Log Erros
		if ( !isset($GLOBALS['LOG_PHP_ERRORS']) )
		{
			self::$error  = '<b>$LOG_PHP_ERRORS</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['LOG_FRAMEWORK_ERRORS']) )
		{
			self::$error  = '<b>$LOG_FRAMEWORK_ERRORS</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['LOG_SQL_ERRORS']) )
		{
			self::$error  = '<b>$LOG_SQL_ERRORS</b> not defined in <b>config.php</b>';
			return false;
		}

		// ---------- Log Files
		if ( !isset($GLOBALS['FILE_LOG_CORE']) )
		{
			self::$error  = '<b>$FILE_LOG_CORE</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['FILE_LOG_USER']) )
		{
			self::$error  = '<b>$FILE_LOG_USER</b> not defined in <b>config.php</b>';
			return false;
		}

		// ---------- Error Behaviour
		if ( !isset($GLOBALS['BREAK_ON_ERROR']) )
		{
			self::$error  = '<b>$BREAK_ON_ERROR</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['DEBUG_CSS']) )
		{
			self::$error  = '<b>$DEBUG_CSS</b> not defined in <b>config.php</b>';
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

		// ---------- Layout
		if ( !isset($GLOBALS['DEFAULT_LAYOUT']) )
		{
			self::$error  = '<b>$DEFAULT_LAYOUT</b> not defined in <b>config.php</b>';
			return false;
		}


		/***************************************************************************
		 *
		 *  URL Defines
		 *
		 ***************************************************************************/

		if ( !isset($GLOBALS['DEFAULT_CONTROLLER']) )
		{
			self::$error  = '<b>$DEFAULT_CONTROLLER</b> not defined in <b>config.php</b>';
			return false;
		}

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

		if ( !isset($GLOBALS['DEFAULT_INFO_MESSAGE_URL']) )
		{
			self::$error  = '<b>$DEFAULT_INFO_MESSAGE_URL</b> not defined in <b>config.php</b>';
			return false;
		}

		if ( !isset($GLOBALS['DEFAULT_SETTINGS_URL']) )
		{
			self::$error  = '<b>$DEFAULT_SETTINGS_URL</b> not defined in <b>config.php</b>';
			return false;
		}


		/***************************************************************************
		 *
		 *  Email Defines
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
		if ( !isset($GLOBALS['EMAIL_SYSTEM_REPLY_ADDRESS']) )
		{
			self::$error = '<b>$EMAIL_SYSTEM_REPLY_ADDRESS</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['EMAIL_SYSTEM_RETURN_EMAIL']) )
		{
			self::$error = '<b>$EMAIL_SYSTEM_RETURN_EMAIL</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['EMAIL_STORE_SEND_MESSAGES']) )
		{
			self::$error = '<b>$EMAIL_STORE_SEND_MESSAGES</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['EMAIL_DO_NOT_SEND']) )
		{
			self::$error = '<b>$EMAIL_DO_NOT_SEND</b> not defined in <b>config.php</b>';
			return false;
		}


		/***************************************************************************
		 *
		 *  HTML Skeleton Defines
		 *
		 ***************************************************************************/

		// ---------- Skeleton File
		if ( !isset($GLOBALS['HTML_DEFAULT_SKELETON']) )
		{
			self::$error = '<b>$HTML_DEFAULT_SKELETON</b> not defined in <b>config.php</b>';
			return false;
		}

		// ---------- Languages
		if ( !isset($GLOBALS['HTML_DEFAULT_LANG_SHORT']) )
		{
			self::$error = '<b>$HTML_DEFAULT_LANG_SHORT</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['HTML_DEFAULT_LANG_LONG']) )
		{
			self::$error = '<b>$HTML_DEFAULT_LANG_LONG</b> not defined in <b>config.php</b>';
			return false;
		}

		// ---------- HTML Defines
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
		 *  Locale Defines
		 *
		 ***************************************************************************/

		if ( !isset($GLOBALS['DEFAULT_LOCALE']) )
		{
			self::$error  = '<b>$DEFAULT_LOCALE</b> not defined in <b>config.php</b>';
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

		/***************************************************************************
		 *
		 *  Included Technology Defines
		 *
		 ***************************************************************************/
		if ( !isset($GLOBALS['ECSS_ENABLE']) )
		{
			self::$error = '<b>$ECSS_ENABLE</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['ECSS_COMPRESSED']) )
		{
			self::$error = '<b>$ECSS_COMPRESSED</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['ECSS_COMMENTED']) )
		{
			self::$error = '<b>$ECSS_COMMENTED</b> not defined in <b>config.php</b>';
			return false;
		}

		/***************************************************************************
		 *
		 *  Core Module Defines
		 *
		 ***************************************************************************/

		// Note: Specific validation is done in each module validator seperately

		if ( !isset($GLOBALS['LANGUAGE_ENABLE']) )
		{
			self::$error = '<b>$LANGUAGE_ENABLE</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['SQL_ENABLE']) )
		{
			self::$error = '<b>$SQL_ENABLE</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['USER_ENABLE']) )
		{
			self::$error = '<b>$USER_ENABLE</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['USER_ONLINE_COUNT_ENABLE']) )
		{
			self::$error = '<b>$USER_ONLINE_COUNT_ENABLE</b> not defined in <b>config.php</b>';
			return false;
		}
		if ( !isset($GLOBALS['SQL_LOG_VISITORS_ENABLE']) )
		{
			self::$error = '<b>$SQL_LOG_VISITORS_ENABLE</b> not defined in <b>config.php</b>';
			return false;
		}
		return true;
	}


	private static function _checkVariableValue()
	{
		/***************************************************************************
		 *
		 *  Debug Defines
		 *
		 ***************************************************************************/

		// ---------- Fast Core Mode
		if ( !($GLOBALS['RUNTIME_MODE'] == SWEANY_DEVELOPMENT ||
			   $GLOBALS['RUNTIME_MODE'] == SWEANY_PRODUCTION ||
			   $GLOBALS['RUNTIME_MODE'] == SWEANY_PRODUCTION_FAST_CORE ||
			   $GLOBALS['RUNTIME_MODE'] == SWEANY_PRODUCTION_DAEMON ) )
		{
			self::$error  = '<b>$RUNTIME_MODE</b> has a wrong value in <b>config.php</b>. Can only be <b>SWEANY_DEVELOPMENT</b>, <b>SWEANY_PRODUCTION</b>, <b>SWEANY_PRODUCTION_FAST_CORE</b> or <b>SWEANY_PRODUCTION_DAEMON</b>.';
			return false;
		}
		
		// --------- TODO: implement PRODUCTION_FAST_CORE
		if ( $GLOBALS['RUNTIME_MODE'] == SWEANY_PRODUCTION_FAST_CORE )
		{
			self::$error  = '<b>$RUNTIME_MODE</b> is set to <b>SWEANY_PRODUCTION_FAST_CORE</b> in <b>config.php</b><br/>Sorry, the fast core mode has not yet been implemented.';
			return false;
		}
		// --------- TODO: implement PRODUCTION_DAEMON
		if ( $GLOBALS['RUNTIME_MODE'] == SWEANY_PRODUCTION_DAEMON )
		{
			self::$error  = '<b>$RUNTIME_MODE</b> is set to <b>SWEANY_PRODUCTION_DAEMON</b> in <b>config.php</b><br/>Sorry, the daemon mode has not yet been implemented.';
			return false;
		}

		// ---------- Validation Mode
		if ( !($GLOBALS['VALIDATION_MODE'] == 0 || $GLOBALS['VALIDATION_MODE'] == 1) )
		{
			self::$error  = '<b>$VALIDATION_MODE</b> has a wrong value in <b>config.php</b>. Can only be <b>0</b> or <b>1</b>.';
			return false;
		}

		// ---------- Display Erros
		if ( $GLOBALS['SHOW_PHP_ERRORS'] < 0 || $GLOBALS['SHOW_PHP_ERRORS'] > 3 )
		{
			self::$error  = '<b>$SHOW_PHP_ERRORS</b> has a wrong value in <b>config.php</b>. Can only be <b>0</b>, <b>1</b>, <b>2</b> or <b>3</b>.';
			return false;
		}
		if ( $GLOBALS['SHOW_FRAMEWORK_ERRORS'] < 0 || $GLOBALS['SHOW_FRAMEWORK_ERRORS'] > 3 )
		{
			self::$error  = '<b>$SHOW_FRAMEWORK_ERRORS</b> has a wrong value in <b>config.php</b>. Can only be <b>0</b>, <b>1</b>, <b>2</b> or <b>3</b>.';
			return false;
		}
		if ( $GLOBALS['SHOW_SQL_ERRORS'] < 0 || $GLOBALS['SHOW_SQL_ERRORS'] > 3 )
		{
			self::$error  = '<b>$SHOW_SQL_ERRORS</b> has a wrong value in <b>config.php</b>. Can only be <b>0</b>, <b>1</b>, <b>2</b> or <b>3</b>.';
			return false;
		}

		// ---------- Log Erros
		if ( $GLOBALS['LOG_PHP_ERRORS'] < 0 || $GLOBALS['LOG_PHP_ERRORS'] > 3 )
		{
			self::$error  = '<b>$LOG_PHP_ERRORS</b> has a wrong value in <b>config.php</b>. Can only be <b>0</b>, <b>1</b>, <b>2</b> or <b>3</b>.';
			return false;
		}
		if ( $GLOBALS['LOG_FRAMEWORK_ERRORS'] < 0 || $GLOBALS['LOG_FRAMEWORK_ERRORS'] > 3 )
		{
			self::$error  = '<b>$LOG_FRAMEWORK_ERRORS</b> has a wrong value in <b>config.php</b>. Can only be <b>0</b>, <b>1</b>, <b>2</b> or <b>3</b>.';
			return false;
		}
		if ( $GLOBALS['LOG_SQL_ERRORS'] < 0 || $GLOBALS['LOG_SQL_ERRORS'] > 3 )
		{
			self::$error  = '<b>$LOG_SQL_ERRORS</b> has a wrong value in <b>config.php</b>. Can only be <b>0</b>, <b>1</b>, <b>2</b> or <b>3</b>.';
			return false;
		}

		// ---------- Log Files
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
		if ( !is_writable(LOG_PATH.DS.$GLOBALS['FILE_LOG_USER']) )
		{
			self::$error  = 'Logfile <b>'.$GLOBALS['FILE_LOG_USER'].'</b> not writable in '.LOG_PATH.DS;
			return false;
		}

		// ---------- Error Behaviour
		if ( !($GLOBALS['BREAK_ON_ERROR'] == 0 || $GLOBALS['BREAK_ON_ERROR'] == 1) )
		{
			self::$error  = '<b>$BREAK_ON_ERROR</b> in <b>config.php</b> has a wrong value, can only be <b>0</b> or <b>1</b>.';
			return false;
		}
		if ( !($GLOBALS['DEBUG_CSS'] == 0 || $GLOBALS['DEBUG_CSS'] == 1) )
		{
			self::$error  = '<b>$DEBUG_CSS</b> in <b>config.php</b> has a wrong value, can only be <b>0</b> or <b>1</b>.';
			return false;
		}
		if ( !file_exists(ROOT.DS.'www'.DS.'js'.DS.'debug.js') )
		{
			self::$error  = 'CSS Debug File <b>'.$GLOBALS['DEBUG_CSS'].'</b> does not exist in '.ROOT.DS.'www'.DS.'js'.DS.'debug.js';
			return false;
		}


		/***************************************************************************
		 *
		 *  Default Defines
		 *
		 ***************************************************************************/

		// ---------- Time Zone
		if ( !(bool)(in_array($GLOBALS['DEFAULT_TIME_ZONE'], timezone_identifiers_list())) )
		{
			self::$error  = '<b>$DEFAULT_TIME_ZONE</b> has a wrong value in <b>config.php</b>';
			return false;
		}

		// ---------- Layout
		if ( !is_file(USR_LAYOUTS_PATH.DS.'view'.DS.$GLOBALS['DEFAULT_LAYOUT']) )
		{
			self::$error = '$DEFAULT_LAYOUT: '.USR_LAYOUTS_PATH.DS.'view'.DS.$GLOBALS['DEFAULT_LAYOUT'].' does not exist';
			return false;
		}


		/***************************************************************************
		 *
		 *  URL Defines
		 *
		 ***************************************************************************/

		if ( !file_exists(PAGES_CONTROLLER_PATH.DS.$GLOBALS['DEFAULT_CONTROLLER'].'.php') )
		{
			self::$error  = 'The defined $DEFAULT_CONTROLLER: <b>'.$GLOBALS['DEFAULT_CONTROLLER'].'.php</b> does not exist in '.PAGES_CONTROLLER_PATH.DS;
			return false;
		}



		/***************************************************************************
		 *
		 *  Email Defines
		 *
		 ***************************************************************************/


		if ( !filter_var($GLOBALS['EMAIL_SYSTEM_FROM_ADDRESS'], FILTER_VALIDATE_EMAIL) )
		{
			self::$error = '<b>$EMAIL_SYSTEM_FROM_ADDRESS</b> is not a valid email';
			return false;
		}
		if ( !filter_var($GLOBALS['EMAIL_SYSTEM_REPLY_ADDRESS'], FILTER_VALIDATE_EMAIL) )
		{
			self::$error = '<b>$EMAIL_SYSTEM_REPLY_ADDRESS</b> is not a valid email';
			return false;
		}
		if ( !filter_var($GLOBALS['EMAIL_SYSTEM_RETURN_EMAIL'], FILTER_VALIDATE_EMAIL) )
		{
			self::$error = '<b>$EMAIL_SYSTEM_RETURN_EMAIL</b> is not a valid email';
			return false;
		}
		if ( !is_numeric($GLOBALS['EMAIL_STORE_SEND_MESSAGES']) || $GLOBALS['EMAIL_STORE_SEND_MESSAGES']<0 || $GLOBALS['EMAIL_STORE_SEND_MESSAGES']>1 )
		{
			self::$error = '<b>$EMAIL_STORE_SEND_MESSAGES</b> must be <b>0</b> or <b>1</b> in <b>config.php</b>';
			return false;
		}
		if ( ($GLOBALS['EMAIL_STORE_SEND_MESSAGES']) && !$GLOBALS['SQL_ENABLE'])
		{
			self::$error = '<b>$EMAIL_STORE_SEND_MESSAGES</b> is enabled, but <b>SQL_ENABLE</b> is not enabled in <b>config.php</b>';
			return false;
		}
		if ( !is_numeric($GLOBALS['EMAIL_DO_NOT_SEND']) || $GLOBALS['EMAIL_DO_NOT_SEND']<0 || $GLOBALS['EMAIL_DO_NOT_SEND']>1 )
			{
			self::$error = '<b>$EMAIL_DO_NOT_SEND</b> must be <b>0</b> or <b>1</b> in <b>config.php</b>';
			return false;
		}

		/***************************************************************************
		 *
		 *  HTML Skeleton Defines
		 *
		 ***************************************************************************/

		// ---------- Skeleton File
		if ( !file_exists(USR_SKELETONS_PATH.DS.$GLOBALS['HTML_DEFAULT_SKELETON']) )
		{
			self::$error  = 'Html skeleton <b>'.$GLOBALS['HTML_DEFAULT_SKELETON'].'</b> does not exist in '.USR_SKELETONS_PATH.DS;
			return false;
		}

		// ---------- Languages
		if ( !in_array($GLOBALS['HTML_DEFAULT_LANG_SHORT'], self::_get_iso639_1_languageCode()) )
		{
			self::$error = '<b>$HTML_DEFAULT_LANG_SHORT: '.$GLOBALS['HTML_DEFAULT_LANG_SHORT'].'</b> is not a valid code by <b>ISO-639-1</b>';
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

		/***************************************************************************
		 *
		 *  Included Technology Defines
		 *
		 ***************************************************************************/
		if ( !is_file(ROOT.DS.'www'.DS.'css'.DS.'ecss.php') )
		{
			self::$error = '<b>ecss.php</b> is missing in www/css/';
			return false;
		}
		if ( $GLOBALS['ECSS_ENABLE'] !== true && $GLOBALS['ECSS_ENABLE'] !== false )
		{
			self::$error = '<b>$ECSS_ENABLE</b> can only be true or false in <b>config.php</b>';
			return false;
		}
		if ( $GLOBALS['ECSS_COMPRESSED'] < 0 || $GLOBALS['ECSS_COMPRESSED'] > 1)
		{
			self::$error = '<b>$ECSS_COMPRESSED</b> can only be 0 or 1 in <b>config.php</b>';
			return false;
		}
		if ( $GLOBALS['ECSS_COMMENTED'] < 0 || $GLOBALS['ECSS_COMMENTED'] > 1)
		{
			self::$error = '<b>$ECSS_COMMENTED</b> can only be 0 or 1 in <b>config.php</b>';
			return false;
		}

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
