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
 * Sweaby is distributed in the hope that it will be useful,
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
 * @version		0.7 2012-07-29 13:25
 *
 *
 * This core module will handle the Language.
 */


/**
 * TODO: need a global section in the xml file that is accessible additionally
 *       to the section used by each function. This makes sense
 *       as sometimes you will have a set of words used throughout the whole
 *       project.
 *       An alternative could be to just use the layout section for any global
 *       words. (Needs thinking and performance measuring)
 */
namespace Core\Init;

Class CoreLanguage extends CoreAbstract
{
	private static $instance 	= null;

	private static $langName	= null;
	private static $langShort	= null;
	private static $langLong	= null;
	private static $_language	= null;

	private $_path				= null;
	private $_id				= null;
	private $_section			= null;




	/****************************************** CORE MODULE INITIALIZER ******************************************/

	public static function initialize()
	{
		$short		= self::chooseLanguage();
		$success	= self::loadFile($short);
		return $success;
	}



	/****************************************** STATIC  FUNCTIONS ******************************************/
	/**
	 * These are required by the html skeleton to decide what language we use.
	 * Even if we disable the language module itself, then we will depend on the
	 * Default Settings from conf.php
	 */
	public static function getLangShort()
	{
		if ( $GLOBALS['LANGUAGE_ENABLE'] == true )
			return self::$langShort;
		else
			return $GLOBALS['HTML_DEFAULT_LANG_SHORT'];
	}

	public static function getLangLong()
	{
		if ( $GLOBALS['LANGUAGE_ENABLE'] == true )
			return self::$langLong;
		else
			return $GLOBALS['HTML_DEFAULT_LANG_LONG'];
	}


	/**
	 * Change the language (loads a different xml file)
	 *
	 * @param string $short_lang (language short code)
	 */
	public static function changeLanguage($short_lang = 'en')
	{
		\SysLog::i('Language', 'Change to '.$short_lang);
		\Core\Init\CoreSession::set('language', array('short' => $short_lang));

		// Need to reload the file (as it has changed)
		$short = self::chooseLanguage();
		self::loadFile($short);
	}




	/********************************************  C O N S T R U C T O R  ********************************************/

	public function __construct($plugin, $type, $controller)
	{
		$section	= (strlen($plugin)) ? 'plugins/'.$plugin : 'usr';
		$sub		= ($type == 'page') ? 'PageSection' : (($type == 'layout') ? 'LayoutSection' : 'BlockSection');
		$sub		= (strlen($plugin)) ? $sub : $sub.'/'.$controller;

		$path		= '/root/'.$section.'/'.$sub.'/'.$type;
		$this->_path= $path;

		\SysLog::i('Language', 'Creating instance for: ['.$type.'] in: '.$path);
	}



	/********************************************  P U B L I C S  ********************************************/

	/**
	 *
	 * Choose desired language section
	 *
	 * @param string $function		id of xml block
	 */
	public function set($function)
	{
		$this->_id	= $function;
		$path		= $this->_path.'[@id="'.$function.'"]';

		$this->_section = self::$_language->xpath($path);
	}

	/**
	 * Choose a page from the core/default section
	 *
	 * @param string $id (function name of default pages)
	 */
	public function setCore($id)
	{
		$this->_section = self::$_language->xpath('/root/core/default/page[@id="'.$id.'"]');
	}

	/**
	 * Generic getter.
	 * Returns a language define for a custom specified call
	 *
	 * @param string $path
	 * @param string $key
	 * @return string
	 */
	public function getCustom($path, $key)
	{
		$tmp = self::$_language->xpath($path);

		// As it is still an object, we will need to cast it into
		// a string. This is necessary for serialization,
		// otherwise there will be a lot of errors and strange results
		if ( !isset($tmp[0]) )
		{
			\SysLog::e('Language', '['.$path.'"]['.$key.'] does not exist');

			// return empty string to prevent php notice, if debugging is off
			if ( \Core\Init\CoreSettings::$showPhpErrors == 0)
				return '';
		}
		if ( !count($tmp[0]->$key ) )
		{
			\SysLog::e('Language', '['.$path.'"]['.$key.'] does not exist');

			// return empty string to prevent php notice, if debugging is off
			if ( \Core\Init\CoreSettings::$showPhpErrors == 0)
				return '';
		}
		if ( count($tmp[0]->$key) > 1)
			return (Array)$tmp[0]->$key;
		else
			return (String)$tmp[0]->$key;
	}


	/**
	 * Magic getter to access language keys in the form
	 * $class->key
	 *
	 * @param String $key
	 */
	public function __get($key)
	{
		// As it is still an object, we will need to cast it into
		// a string. This is necessary for serialization,
		// otherwise there will be a lot of errors and strange results
		if ( !isset($this->_section[0]) )
		{
			\SysLog::e('Language', '['.$this->_path.' id="'.$this->_id.'"]['.$key.'] does not exist');

			// return empty string to prevent php notice, if debugging is off
			if ( \Core\Init\CoreSettings::$showPhpErrors == 0)
				return '';
		}
		if ( !count($this->_section[0]->$key ) )
		{
			\SysLog::e('Language', '['.$this->_path.' id="'.$this->_id.'"]['.$key.'] does not exist');

			// return empty string to prevent php notice, if debugging is off
			if ( \Core\Init\CoreSettings::$showPhpErrors == 0)
				return '';
		}

		//return $this->_section[0]->$key;
		if ( count($this->_section[0]->$key) > 1)
			return $this->_section[0]->$key;
		else
			return (String)$this->_section[0]->$key;
	}




	/********************************************  P R I V A T E S  ********************************************/

	/**
	 * Choose language based on Session
	 * or use default
	 */
	private static function chooseLanguage()
	{
		// Session already exists, so we use it
		if ( \Core\Init\CoreSession::exists('language') )
		{
			$lang	= \Core\Init\CoreSession::get('language');
			$short	= $lang['short'];
			$file	= USR_LANGUAGES_PATH.DS.$short.'.xml';

			// In case it does not exist
			// Use the default language
			if ( !file_exists($file) )
			{
				\Log::setWarn('Language', '[Choose] File does not exist - Using default');
				$short = $GLOBALS['LANGUAGE_DEFAULT_SHORT'];
			}
		}
		// No Session yet, so create it based on the default language
		else
		{
			\SysLog::w('Language', '[Choose] Session does not exist - Using default');
			$short	= $GLOBALS['LANGUAGE_DEFAULT_SHORT'];
			\Core\Init\CoreSession::set('language', array('short' => $short));
		}
		return $short;
	}


	/**
	 * Load Language File
	 *
	 * @param String $lang_short ('en', 'de', etc)
	 */
	private static function loadFile($lang_short)
	{
		$xml_lang = USR_LANGUAGES_PATH.DS.$lang_short.'.xml';

		if ( file_exists($xml_lang) )
		{
			//Log::setInfo('Language', '[Load] '.$lang_short);
			self::$_language	= simplexml_load_file($xml_lang);
			$settings			= self::$_language->xpath('/root/core/settings');

			self::$langName		= $settings[0]->lang_name;
			self::$langShort	= $settings[0]->lang_short;
			self::$langLong		= $settings[0]->lang_long;

			return true;
		}
		else
		{
			self::$error = 'File does not exist: '.$xml_lang;
			return false;
		}
	}
}
