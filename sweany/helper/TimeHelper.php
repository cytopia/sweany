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
class TimeHelper
{

	/* ********************************************  VARIABLES  ******************************************** */

	/**
	 * Cache to store all month names in a year,
	 * so we do not have to fetch them multiple times
	 * during a single page call
	 *
	 * @var unknown
	 */
	private static $allMonths	= null;

	private static $allHours	= null;



	/* ********************************************  G E T   B Y   T I M E S T A M P   F U N C T I O N S  ******************************************** */

	/**
	 * Returns a formatted date, or if names of past days are specified
	 * it can also return 'yesterday' instead of the date
	 *
	 * @param	string	$format
	 * @param	float	$timestamp
	 * @param	mixed	$dayAgoNames
	 * 		array('Today', 'Yesterday', 'the day before yesterday', ...)
	 * 		or noting. Depending on how many days ago you would like to display the daye
	 * @param	string	$timezone
	 * @return
	 *
	 */
	public static function getFormattedDate($format, $timestamp, $dayAgoNames = array(), $timezone = null)
	{
		$day		= 86400;	// (60*60*24) seconds
		$agoDays	= count($dayAgoNames);
		$checkDate	= self::date('Ymd', $timestamp, $timezone);

		for ( $i=0; $i<$agoDays; $i++ )
		{
			if ( $checkDate == self::date('Ymd', time()-($day*$i), $timezone) )
			{
				return $dayAgoNames[$i];
			}
		}
		return self::date($format, $timestamp, $timezone);
	}

	/**
	 * Returns formatted date from unix timestamp.
	 * The date is also adjusted to the choosen timezone.
	 *
	 * @param	string		$format
	 * @param	float		$timestamp
	 * @param	string		$timezone
	 * @return	string
	 */
	public static function date($format, $timestamp, $timezone = null)
	{
		$timezone	= ($timezone) ? $timezone : self::getUserTimezone($timezone);
		$date		= new DateTime('@'.$timestamp);

		$date->setTimezone(new DateTimeZone($timezone));
		return $date->format($format);
	}

	/**
	 * Returns the localized long name of the week depending
	 * on the language used.
	 *
	 * @param	float	$timestamp
	 * @return	string
	 */
	public static function weekDay($timestamp, $timezone = null)
	{
		$timezone	= self::getUserTimezone($timezone);
		$timestamp	= $timestamp + self::getTimestampOffset($timezone);

		return strftime('%A', $timestamp);
	}

	/**
	 * Returns the localized short name of the week depending
	 * on the language used.
	 *
	 * @param	float	$timestamp
	 * @return	string
	 */
	public static function weekDayShort($timestamp, $timezone = null)
	{
		$timezone	= self::getUserTimezone($timezone);
		$timestamp	= $timestamp + self::getTimestampOffset($timezone);

		return strftime('%a', $timestamp);
	}

	/**
	 * Returns the localized long name of the month depending
	 * on the language used.
	 *
	 * @param	float	$timestamp
	 * @return	string
	 */
	public static function month($timestamp, $timezone = null)
	{
		$timezone	= self::getUserTimezone($timezone);
		$timestamp	= $timestamp + self::getTimestampOffset($timezone);

		return strftime('%B', $timestamp);
	}

	/**
	 * Returns localized short month name depending
	 * on the language used.
	 *
	 * @param	float		$timestamp
	 * @param	string		$timezone
	 * @return	string
	 */
	public static function monthShort($timestamp, $timezone = null)
	{
		$timezone	= self::getUserTimezone($timezone);
		$timestamp	= $timestamp + self::getTimestampOffset($timezone);

		return strftime('%b', $timestamp);
	}


	/* ********************************************  G E T    A L L   F U N C T I O N S  ******************************************** */

	public static function getAllMonthNames()
	{
		// Cache already filled?
		if (self::$allMonths) {
			return self::$allMonths;
		}

		self::$allMonths = array();

		for ($i=1; $i<=12; $i++) {
			$timestamp	= mktime(0, 0, 0, $i, 1, 2000);
			$name		= strftime('%B', $timestamp);

			self::$allMonths[] = $name;
		}

		return self::$allMonths;
	}

	public static function getHours()
	{
		// Cache already filled?
		if (self::$allHours) {
			return self::$allHours;
		}

		self::$allHours = array();

		for ($i=0; $i<24; $i++) {
			self::$allHours[] = sprintf('%02d:00', $i);
		}

		return self::$allHours;
	}




	/* ********************************************  M I S C   F U N C T I O N S  ******************************************** */

	public static function getHourSpan($hour_from, $hour_to)
	{
		return sprintf('%02d:00 - %02d:00', $hour_from, $hour_to);
	}




	/* ********************************************  C H E C K   D A T E   F U N C T I O N S  ******************************************** */


	/**
	 *	Is the timestamp from today?
	 */
	public static function isToday($timestamp, $timezone = null)
	{
		return ( self::date('Y-m-d', $timestamp, $timezone) == self::date('Y-m-d', time(), $timezone) );
	}

	/**
	 *	Is the timestamp from this week?
	 */
	public static function isThisWeek($timestamp, $timezone = null)
	{
		return ( self::date('W o', $timestamp, $timezone) == self::date('W o', time(), $timezone) );
	}

	/**
	 *	Is the timestamp from this month?
	 */
	public static function isThisMonth($timestamp, $timezone = null)
	{
		return ( self::date('m Y', $timestamp, $timezone) == self::date('m Y', time(), $timezone) );
	}

	/**
	 *	Is the timestamp from this year?
	 */
	public static function isThisYear($timestamp, $timezone = null)
	{
		return ( self::date('Y', $timestamp, $timezone) == self::date('Y', time(), $timezone) );
	}

	/**
	 *	Is the timestamp from yesterday?
	 */
	public static function wasYesterday($timestamp, $timezone = null)
	{
		$oneDay		= 86400;	// (60*60*24) seconds
		return ( self::date('Y', $timestamp, $timezone) == self::date('Y', time()-$oneDay, $timezone) );
	}

	/**
	 *	Is the timestamp from tomorrow?
	 */
	public static function isTomorrow($timestamp, $timezone = null)
	{
		$oneDay		= 86400;	// (60*60*24) seconds
		return ( self::date('Y', $timestamp, $timezone) == self::date('Y', time()+$oneDay, $timezone) );
	}







	/* ********************************************  P R I V A T E S  ******************************************** */

	private static function getServerTimezone()
	{
		return $GLOBALS['DEFAULT_TIME_ZONE'];
	}

	private static function getUserTimezone($timezone)
	{
		if ( $timezone && !is_numeric($timezone) && in_array($timezone, timezone_identifiers_list()) ) {
			return $timezone;
		}
		$timezone = \Sweany\Users::timezone();

		if ( $timezone && !is_numeric($timezone) && in_array($timezone, timezone_identifiers_list()) ) {
			return $timezone;
		}

		return self::getServerTimezone();
	}

	/**
	 * Get Unix Timestamp offset between
	 * the server timezone and the user's timezone.

	 * @param	string	$userTimezone		Timezone
	 * @return	long	Unix Timestamp Offset
	 */
	private static function getTimestampOffset($userTimezone)
	{
		$serverTz	= new DateTimeZone(self::getServerTimezone());
		$serverDt	= new DateTime("now", $serverTz);
		$serverOff	= $serverTz->getOffset($serverDt); // offset from GMT

		$userTz		= new DateTimeZone($userTimezone);
		$userDt		= new DateTime("now", $userTz);
		$userOff	= $userTz->getOffset($userDt);

		// both numbers either positive or both negative
		if ( ($serverOff >= 0 && $userOff >= 0) || $serverOff < 0 && $userOff < 0 ) {
			return abs($serverOff - $userOff);
		} else {
			return abs($serverOff) + abs($userOff);
		}
	}
}
