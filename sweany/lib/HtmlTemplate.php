<?php
/**
 * Html Template Helper
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
 * @package		sweany.helper
 * @author		Patu
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @version		0.7 2012-07-29 13:25
 *
 */
Class HtmlTemplate
{
	private static $xmlNs		= null;
	private static $htmlAttr	= null;
	private static $headPrefix	= null;
	private static $title		= null;
	private static $metaTags	= null;
	private static $keywords	= null;
	private static $description	= null;
	private static $redirect	= null;


	/******************************************************** HTML ********************************************************/

	public static function addXmlNameSpace($subNs = null, $value)
	{
		self::$xmlNs .= !is_null($subNs) ? ' xmlns:'.$subNs.'="'.$value.'"' : ' xmlns="'.$value.'"';
	}
	public static function getXmlNameSpace()
	{
		return self::$xmlNs;
	}

	// adds an attribute to <html ...here... >
	public static function addHtmlAttribute($attribute = null)
	{
		self::$htmlAttr = $attribute;
	}
	public static function getHtmlAttribute()
	{
		return self::$htmlAttr;
	}


	/******************************************************** HEAD ********************************************************/
	public static function addHeadPrefix($prefix)
	{
		self::$headPrefix .= ' '.$prefix;
	}
	public static function getHeadPrefix()
	{
		return self::$headPrefix;
	}





	/******************************************************** META ********************************************************/

	public static function addMetaTag($tag)
	{
		self::$metaTags .= "\t".$tag."\n";
	}
	public static function getMetaTags()
	{
		return self::$metaTags;
	}


	public static function setRedirect($url, $delay = 5)
	{
		self::addMetaTag('<meta http-equiv="refresh" content="'.$delay.'; url='.$url.'" />');
	}



	public static function setTitle($title)
	{
		self::$title = htmlentities($title);
	}
	public static function getTitle()
	{
		return !is_null(self::$title) ? self::$title : $GLOBALS['HTML_DEFAULT_PAGE_TITLE'];
	}


	public static function setKeywords($keywords)
	{
		self::$keywords = htmlentities($keywords);
	}
	public static function getKeywords()
	{
		return !is_null(self::$keywords) ?  self::$keywords : $GLOBALS['HTML_DEFAULT_PAGE_KEYWORDS'];
	}


	public static function setDescription($description)
	{
		self::$description = htmlentities($description);
	}
	public static function getDescription()
	{
		return !is_null(self::$description) ? self::$description : $GLOBALS['HTML_DEFAULT_PAGE_DESCRIPTION'];
	}
}

?>