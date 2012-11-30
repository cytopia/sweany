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
 * @version		0.8 2012-08-13 13:25
 *
 *
 * Captcha Helper
 */

Class Captcha
{

	private static $img_path	= '/sweany/captcha/captcha.php';
	private static $font_path	= '/sweany/captcha/captcha.ttf';


	/**
	 * Return a renderable captcha image
	 *
	 * @param	string	$alt		Img Alternative Text
	 * @param	mixed[]	$options	Options for the img tag
	 * @return	string				Renderable image tag
	 */
	public static function img($alt = '', $options = array())
	{
		return Html::img(self::$img_path, $alt, $options);
	}


	/**
	 * Get the characters displayed on the captcha
	 *
	 * @return string The characters from the captcha
	 */
	public static function read($destroy = true)
	{
		// Get the current captcha text from the session
		$text = \Sweany\Session::get('captcha_spam');

		// Destroy the session
		if ($destroy) {
			\Sweany\Session::del('captcha_spam');
		}

		return $text;
	}
}
