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
 * @version		0.9 2012-08-14 13:25
 *
 *
 * This core module will handle the Language.
 *
 * NOTE:
 * For performance reasons this class consists of a static and a dynamic part.
 *
 * + The static part will load the normal project specific language xml file.
 *
 * + Each created instance will then load the path (inside that xml file)
 *   of the current accessed section, as a full page load needs several different sections.
 *   (layout-section, page-section and block-section)
 *
 * + Additionally an instance can also load a plugin xml file, but we dont know yet, so we let
 *   the instance decide, whether or not it is needed.
 *   Then again, the new file will have to provide different sections as well.
 */


/**
 * TODO: need a global section in the xml file that is accessible additionally
 *       to the section used by each function. This makes sense
 *       as sometimes you will have a set of words used throughout the whole
 *       project.
 *       An alternative could be to just use the layout section for any global
 *       words. (Needs thinking and performance measuring)
 */
namespace Sweany;

Class Language extends aBootTemplate
{

	/*
	 * Information retrieved from the project main
	 * language xml file are stored here.
	 */
	private static $langName	= null;		// e.g.: English
	private static $langShort	= null;		// e.g.: en
	private static $langLong	= null;		// e.g.: en-US
	private static $locale		= null;		// e.g.: en_US.UTF-8


	/*
	 * This array will hold all language objects
	 * of 'default' (project specific xml object) and
	 * all other plugin language objects (if any)
	 */
	private static $language = array();


	/*
	 * static Language Store.
	 *
	 * This will hold the words from the global section
	 * to have it available from any page/block/layout function
	 */
	private static $_sstore	= null;


	/*
	 * In case the instance is a Plugin,
	 * it will hold the name of it,
	 * otherwise will be null
	 */
	private $_plugin	= null;


	/*
	 * Instance accesser
	 */
	private $_path		= null;		// xml path section
	private $_id		= null;		// xml id section
	private $_store		= null;		// provides the actual language words





	/****************************************** CORE MODULE INITIALIZER ******************************************/

	public static function initialize($options = null)
	{
		$short		= self::chooseLanguage();

		if ( !self::loadProjectFile($short) )
		{
			self::$error = '<strong>Cannot Load Language File<strong><br/>'.self::$error;
			return false;
		}

		if ( !self::_resetLocale() )
		{
			self::$error = '<strong>Cannot Set Language File specific locale<strong><br/>'.self::$error;
			return false;
		}

		return true;
	}


	public static function _resetLocale()
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
		\Sweany\SysLog::i('Language', 'Change to '.$short_lang);
		\Sweany\Session::set('language', array('short' => $short_lang));

		// Need to reload the file (as it has changed)
		$short = self::chooseLanguage();
		self::loadProjectFile($short);
	}




	/********************************************  C O N S T R U C T O R  ********************************************/

	public function __construct($plugin = null, $type, $controller)
	{
		if ($plugin)
		{
			$this->_plugin = $plugin;

			self::loadPluginFile(self::$langShort, $plugin);
		}

		$section	= (strlen($plugin)) ? 'plugins/'.$plugin : 'usr';
		$sub		= ($type == 'page') ? 'PageSection' : (($type == 'layout') ? 'LayoutSection' : 'BlockSection');
		$sub		= (strlen($plugin)) ? $sub : $sub.'/'.$controller;

		$path		= '/root/'.$section.'/'.$sub.'/'.$type;
		$this->_path= $path;

		if ($plugin)
		{
			\Sweany\SysLog::i('Language', '[Plugin] Setting path for: ['.$type.'] to: '.$path);
		}
		else
		{
			\Sweany\SysLog::i('Language', 'Setting path for: ['.$type.'] to: '.$path);
		}
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

		/*
		 * If dealing with a plugin, we have an instance lang variable,
		 * otherwise we will use the static one, that holds the project xml file.
		 */
		if ( $this->_plugin )
		{
			$this->_store = self::$language[$this->_plugin]->xpath($path);
		}
		else
		{
			$this->_store = self::$language['default']->xpath($path);
		}
	}

	/**
	 * Choose a page from the core/default section
	 *
	 * @param string $id (function name of default pages)
	 */
	public function setCore($id)
	{
		// default part is not available in plugin xml files, so no need to differentiate here
		$this->_store = self::$language['default']->xpath('/root/core/default/page[@id="'.$id.'"]');
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
		/*
		 * If dealing with a plugin, we have an instance lang variable,
		* otherwise we will use the static one, that holds the project xml file.
		*/
		if ( $this->_plugin )
		{
			$tmp = self::$language[$this->_plugin]->xpath($path);
		}
		else
		{
			$tmp = self::$language['default']->xpath($path);
		}

		// As it is still an object, we will need to cast it into
		// a string. This is necessary for serialization,
		// otherwise there will be a lot of errors and strange results
		if ( !isset($tmp[0]) )
		{
			\Sweany\SysLog::e('Language', '['.$path.'"]['.$key.'] does not exist');

			// return empty string to prevent php notice, if debugging is off
			if ( \Sweany\Settings::$showPhpErrors == 0)
				return '';
		}
		if ( !count($tmp[0]->$key ) )
		{
			\Sweany\SysLog::e('Language', '['.$path.'"]['.$key.'] does not exist');

			// return empty string to prevent php notice, if debugging is off
			if ( \Sweany\Settings::$showPhpErrors == 0)
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
		if ( !isset($this->_store[0]) )
		{
			\Sweany\SysLog::e('Language', '['.$this->_path.' id="'.$this->_id.'"]['.$key.'] does not exist');

			// return empty string to prevent php notice, if debugging is off
			if ( \Sweany\Settings::$showPhpErrors == 0)
				return '';
		}
		if ( !count($this->_store[0]->$key) && !count(self::$_sstore[0]->$key) )
		{
			\Sweany\SysLog::e('Language', '['.$this->_path.' id="'.$this->_id.'"]['.$key.'] does not exist');

			// return empty string to prevent php notice, if debugging is off
			if ( \Sweany\Settings::$showPhpErrors == 0)
				return '';
		}

		// Check if the value is available in specific store
		if ( count($this->_store[0]->$key) )
		{
			if ( count($this->_store[0]->$key) > 1)
				return $this->_store[0]->$key;
			else
				return (String)$this->_store[0]->$key;
		}
		else // otherwise use value from global store
		{
			if ( count(self::$_sstore[0]->$key) > 1)
				return self::$_sstore[0]->$key;
			else
				return (String)self::$_sstore[0]->$key;
		}
	}




	/********************************************  P R I V A T E   S T A T I C S  ********************************************/

	/**
	 * Choose language based on Session
	 * or use default
	 */
	private static function chooseLanguage()
	{
		// Session already exists, so we use it
		if ( \Sweany\Session::exists('language') )
		{
			$lang	= \Sweany\Session::get('language');
			$short	= $lang['short'];
			$file	= USR_LANGUAGES_PATH.DS.$short.'.xml';

			// In case it does not exist$settings
			// Use the default language
			if ( !file_exists($file) )
			{
				\Sweany\SysLog::w('Language', '[Choose] File does not exist - Using default');
				$short = $GLOBALS['LANGUAGE_DEFAULT_SHORT'];
			}
		}
		// No Session yet, so create it based on the default language
		else
		{
			\Sweany\SysLog::w('Language', '[Choose] Session does not exist - Using default');
			$short	= $GLOBALS['LANGUAGE_DEFAULT_SHORT'];
			\Sweany\Session::set('language', array('short' => $short));
		}
		return $short;
	}


	/**
	 * Plugin specific xml file load.
	 * Each Plugin has their own (if available) language xml file
	 * and we have to load all files (if for example there are several
	 * blocks from different plugins on one page).
	 *
	 * We also have to make sure, not to load a file twice to save performance.
	 *
	 * @param string $lang_short
	 * 		('en', 'de', etc)
	 * @param string $plugin
	 * 		name of the plugin
	 * @return boolean
	 * 		success
	 */
	private static function loadPluginFile($lang_short, $plugin)
	{
		/*
		 * In case we have several plugin xml files to load or already loaded,
		 * we need to keep track of the latest assigned file,
		 * as this one will serve the content
		 */
		//$this->_plugin = $plugin;


		/*
		 * In case the file has already been loaded,
		 * we will quit here and give some info to the syslogger
		 */
		if ( array_key_exists($plugin, self::$language) )
		{
			\Sweany\SysLog::i('Language', '[Plugin] '.$lang_short.'.xml already loaded for ['.$plugin.'-plugin]. Skipping...');
			return true;
		}
		else
		{
			/*
			 * File is not in the array, so not loaded.
			 * We need to make sure it exists and load it.
			 */

			$xml_file = USR_PLUGINS_PATH.DS.$plugin.DS.'languages'.DS.$lang_short.'.xml';

			if ( file_exists($xml_file) )
			{
				self::$language[$plugin] = simplexml_load_file($xml_file);
				\Sweany\SysLog::i('Language', '[Plugin] First Load of '.$lang_short.'.xml for ['.$plugin.'-plugin]');
				return true;
			}
			else
			{
				\Sweany\SysLog::e('Language', '[Plugin] File does not exist for ['.$plugin.'-plugin]: '.$xml_file);
				return false;
			}
		}
	}


	/**
	 *
	 * Project specific xml file load.
	 * Loads the language xml file from usr/languages.
	 *
	 * @param string $lang_short
	 * 		Language 2-letter specifier (e.g.: 'en', 'de', ...)
	 * @return boolean
	 * 		success
	 */
	private static function loadProjectFile($lang_short)
	{
		$xml_file = USR_LANGUAGES_PATH.DS.$lang_short.'.xml';

		if ( file_exists($xml_file) )
		{
			//Log::setInfo('Language', '[Load] '.$xml_file);
			self::$language['default']	= simplexml_load_file($xml_file);
			$settings					= self::$language['default']->xpath('/root/core/settings');

			self::$langName		= $settings[0]->lang_name;
			self::$langShort	= $settings[0]->lang_short;
			self::$langLong		= $settings[0]->lang_long;
			self::$locale		= $settings[0]->locale;

			self::$_sstore		= self::$language['default']->xpath('/root/global');

			return true;
		}
		else
		{
			self::$error = 'File does not exist: '.$xml_file;
			return false;
		}
	}
}
