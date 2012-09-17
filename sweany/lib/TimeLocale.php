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
 * @version		0.7 2012-07-29 13:25
 *
 *
 * Date Module
 *
 * This module will handle date formats and return the correct
 * names (of each language) defined by the locale (defined by config.php)
 *
 * This Module integrates with the internal language system and by setting
 * different languages this module will react appropriate
 *
 */
class TimeLocale
{

	public static function now($format = null, $timezone = null)
	{

	}

	/**
	 *
	 * @param integer|string|DateTime $date UNIX timestamp, strtotime() valid string or DateTime object
	 * @param string|DateTimeZone $timezone Timezone string or DateTimeZone object
	 * @return string Parsed timestamp

	 */
	public static function date($date, $timezone)
	{
		// TODO:
		return date('d.m.Y', $timestamp);
	}



}
