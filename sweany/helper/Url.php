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
 * URL Helper
 */

/**
 *  TODO: coreUrl and this class still need to be seperated
 *        and rewritten!
 *
 */
class Url
{
	public static function getController()
	{
		return \Sweany\Url::getController();
	}

	public static function getMethod()
	{
		return \Sweany\Url::getMethod();
	}

	public static function getParams()
	{
		return \Sweany\Url::getParams();
	}
	public static function getRequest()
	{
		return \Sweany\Url::getRequest();
	}

	/**
	 *
	 * Get http|https and domain
	 */
	public static function getSiteUrl()
	{
		$protocol	= ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
		$domainName	= $_SERVER['HTTP_HOST'];
		return $protocol.$domainName;
	}

	public static function getFullUrl()
	{
		return self::getSiteUrl().$_SERVER['REQUEST_URI'];
	}

	public static function cleanUrlParams($urlParamString)
	{
		$clean = preg_replace("/[^a-zA-Z0-9.\/_|+ -]/", '', $urlParamString);
		$clean = preg_replace("/[\/|+ -]+/", '-', $clean);
		return ($clean);
	}
}
