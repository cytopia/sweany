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
 * @package		sweany.core.lib
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.7 2012-07-29 13:25
 *
 *
 * Html Template Helper
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
		self::$xmlNs .= isset($subNs[0]) ? ' xmlns:'.$subNs.'="'.$value.'"' : ' xmlns="'.$value.'"';
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
		return isset(self::$title[0]) ? self::$title : $GLOBALS['HTML_DEFAULT_PAGE_TITLE'];
	}


	public static function setKeywords($keywords)
	{
		self::$keywords = htmlentities($keywords);
	}
	public static function getKeywords()
	{
		return isset(self::$keywords[0]) ?  self::$keywords : $GLOBALS['HTML_DEFAULT_PAGE_KEYWORDS'];
	}


	public static function setDescription($description)
	{
		self::$description = htmlentities($description);
	}
	public static function getDescription()
	{
		return isset(self::$description[0]) ? self::$description : $GLOBALS['HTML_DEFAULT_PAGE_DESCRIPTION'];
	}
}
