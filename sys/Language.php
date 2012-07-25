<?php
Class Language
{
	private static $_instance 	= null;
	private $_language			= array();
	private $_section			= array();

	
	/**
	 * Singleton
	 * @param String $section
	 */
	public static function singleton($section)
	{
		if ( Settings::$debugLevel )
			$start = getmicrotime();
		
		if ( !isset(self::$_instance) )	// No instance availible
		{
			$classname			= __CLASS__;
			self::$_instance	= new $classname;
		}
	
		self::$_instance->setSection($section);
	

		if ( Settings::$debugLevel )
			Log::setInfo('Language', 'Complete Loading time', null, $start);
		
		return self::$_instance;
	}
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$lang = $this->chooseLanguage();
		$this->loadFile($lang);
	}
	
	
	
	/********************************************  P U B L I C S  ********************************************/
	
	
	/**
	 * 
	 * Alter desired language Section
	 * 
	 * @param String $section
	 */
	public function setSection($section)
	{
		$this->_section = $this->_language->xpath('/root/page[@id="'.$section.'"]');
	}
	
	
	/**
	 * Magic getter to access language keys in the form
	 * $class->key
	 * 
	 * @param String $key
	 */
	public function __get($key)
	{
		return $this->_section[0]->$key;
	}

	
	
	/********************************************  P R I V A T E S  ********************************************/

	
	/**
	 * Choose language based on Session
	 * or use default
	 */
	private function chooseLanguage()
	{
		// Session already exists, so we use it
		if ( Session::exists('language') )
		{
			$lang	= Session::get('language');
			$short	= $lang['short'];
			$file	= USR.DS.'lang'.DS.$short.'.xml';
			
			if ( !file_exists($file) )
			{
				Log::setWarn('LANGUAGE: '.$short, 'File does not exist: '.$file.'. Trying default');
				$short = $GLOBALS['HTML_DEFAULT_LANG_SHORT'];
			}
		}
		// No Session yet, so create it based on the default language
		else
		{
			Log::setInfo('LANGUAGE', 'Language has not yet been choosen. Setting default');
			$short = $GLOBALS['HTML_DEFAULT_LANG_SHORT'];
			Session::set('language', array('short' => $short));
		}
		return $short;
	}
	
	
	/**
	 * Load Language File
	 * 
	 * @param String $lang_short ('en', 'de', etc)
	 */
	private function loadFile($lang_short)
	{
		$xml_lang = USR.DS.'lang'.DS.$lang_short.'.xml';
		
		if ( file_exists($xml_lang) )
		{
			Log::SetInfo('LANGUAGE: '.$lang_short, 'Loading: '.$xml_lang);
			$this->_language = simplexml_load_file($xml_lang);
		}
		else
		{
			Log::setError('LANGUAGE: '.$lang_short, 'No such language file: '.$xml_lang);
			Log::show();
		}
	}
}