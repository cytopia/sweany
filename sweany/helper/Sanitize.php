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
 * @package		sweany.lib
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.8 2012-08-17 13:25
 *
 *
 * Sanitize
 */
class Sanitize
{

	/**
	 * Sanitize a string to use in seo url's
	 *
	 * @param string $url String to sanitize
	 */
	public static function seoUrl($url)
	{
		$url = preg_replace("/[^a-zA-Z0-9.\/_|+ -]/", '', $url);
		$url = preg_replace("/[\/|+ -]+/", '-', $url);
		return $url;
	}


	public static function escape($string)
	{
		// TODO!!
		return '"'.$string.'"';
	}

	public static function html($string, $strip_tags = false)
	{
		$charset	= 'UTF-8';
		$quotes		= ENT_QUOTES;	// will convert both double and single quotes.
		$double		= true;			// encode existing html entities.

		if ($strip_tags)
		{
			$string = strip_tags($string);
		}

		return htmlentities($string, $quotes, $charset, $double);
	}

}