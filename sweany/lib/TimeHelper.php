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

	/**
	 * Returns a formatted date, or if names of past days are specified
	 * it can also return 'yesterday' instead of the date
	 *
	 *
	 * @param datetime	$dateTimeString
	 * @param string	$format
	 * @param mixed		$dayAgoNames
	 * 		array('Today', 'Yesterday', 'the day before yesterday', ...)
	 * 		or noting. Depending on how many days ago you would like to display the daye
	 *
	 */
	public static function getFormattedDate($dateTimeString, $format = 'd.m.Y', $dayAgoNames = array())
	{
		$day		= 86400;	// (60*60*24) seconds
		$agoDays	= count($dayAgoNames);
		$checkDate	= date('Ymd',self::toTimeStamp($dateTimeString));

		for ( $i=0; $i<$agoDays; $i++ ) {
			if ( $checkDate == date('Ymd', time()-($day*$i)) ) {
				return $dayAgoNames[$i];
			}
		}
		return date($format, self::toTimeStamp($dateTimeString));
	}



	/**
	 *
	 * @param timestamp $timestamp
	 */
	public static function date($timestamp)
	{
		// TODO:
		return date('d.m.Y', $timestamp);
	}



	/**
	 * returns the localized name of the week depending
	 * on the language used.
	 *
	 * @param timestamp $timestamp
	 * @return string
	 */
	public static function weekDay($timestamp)
	{
		return strftime('%A', $timestamp);
	}

	/**
	 * returns the localized short name of the week depending
	 * on the language used.
	 *
	 * @param timestamp $timestamp
	 * @return string
	 */
	public static function weekDayShort($timestamp)
	{
		return strftime('%a', $timestamp);
	}

	/**
	 * returns the localized name of the month depending
	 * on the language used.
	 *
	 * @param timestamp $timestamp
	 * @return string
	 */
	public static function month($timestamp)
	{
		return strftime('%B', $timestamp);
	}

	public static function monthShort($timestamp)
	{
		return strftime('%b', $timestamp);
	}




	/**
	 * Returns a UNIX timestamp, given either a UNIX timestamp or a valid strtotime() date string.
	 *
	 * @param integer|string $date UNIX timestamp, strtotime() valid string
	 * @param string|DateTimeZone $timezone Timezone string or DateTimeZone object
	 * @return string Parsed timestamp
	 */
	public static function toTimeStamp($date, $timezone = null)
	{
		if (empty($date)) {
			return false;
		}

		if (is_integer($date) || is_numeric($date)) {
			$date = intval($date);
		} else {
			$date = strtotime($date);
		}

		if ($date === -1 || empty($date)) {
			return false;
		}

		if ($timezone === null) {
			//$timezone = $GLOBALS['$DEFAULT_TIME_ZONE'];
		}

		if ($timezone !== null) {
			return self::convert($date, $timezone);
		}

		return $date;
	}



	/**
	 * Converts given time (in server's time zone) to user's local time, given his/her timezone.
	 *
	 * @param string $serverTime UNIX timestamp
	 * @param string|DateTimeZone $timezone User's timezone string or DateTimeZone object
	 * @return integer UNIX timestamp
	 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/time.html#formatting
	 */
	public static function convert($serverTime, $timezone)
	{
		static $serverTimezone = null;

		if (is_null($serverTimezone) || (date_default_timezone_get() !== $serverTimezone->getName()))
		{
			$serverTimezone = new DateTimeZone(date_default_timezone_get());
		}

		$serverOffset = $serverTimezone->getOffset(new DateTime('@' . $serverTime));
		$gmtTime = $serverTime - $serverOffset;

		if (is_numeric($timezone))
		{
			$userOffset = $timezone * (60 * 60);
		}
		else
		{
			$timezone = self::timezone($timezone);
			$userOffset = $timezone->getOffset(new DateTime('@' . $gmtTime));
		}
		$userTime = $gmtTime + $userOffset;
		return (int)$userTime;
	}

	/**
	 * Returns a timezone object from a string or the user's timezone object
	 *
	 * @param string|DateTimeZone $timezone Timezone string or DateTimeZone object
	 * 	If null it tries to get timezone from 'Config.timezone' config var
	 * @return DateTimeZone Timezone object
	 */
	public static function timezone($timezone = null)
	{
		static $tz = null;

		if (is_object($timezone))
		{
			if ($tz === null || $tz->getName() !== $timezone->getName())
			{
				$tz = $timezone;
			}
		}
		else
		{
			if ($timezone === null)
			{
				$timezone = $GLOBALS['$DEFAULT_TIME_ZONE'];

				if ($timezone === null)
				{
					$timezone = date_default_timezone_get();
				}
			}
			if ($tz === null || $tz->getName() !== $timezone)
			{
				$tz = new DateTimeZone($timezone);
			}
		}
		return $tz;
	}


	/**
	 * Returns true if given datetime string is today.
	 *
	 * @param integer|string|DateTime $dateString UNIX timestamp, strtotime() valid string or DateTime object
	 * @param string|DateTimeZone $timezone Timezone string or DateTimeZone object
	 * @return boolean True if datetime string is today
	 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/time.html#testing-time
	 */
	public static function isToday($dateString, $timezone = null) {
		$date = self::toTimeStamp($dateString, $timezone);
		return date('Y-m-d', $date) == date('Y-m-d', time());
	}

	/**
	 * Returns true if given datetime string is within this week.
	 *
	 * @param integer|string|DateTime $dateString UNIX timestamp, strtotime() valid string or DateTime object
	 * @param string|DateTimeZone $timezone Timezone string or DateTimeZone object
	 * @return boolean True if datetime string is within current week
	 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/time.html#testing-time
	 */
	public static function isThisWeek($dateString, $timezone = null) {
		$date = self::toTimeStamp($dateString, $timezone);
		return date('W o', $date) == date('W o', time());
	}

	/**
	 * Returns true if given datetime string is within this month
	 * @param integer|string|DateTime $dateString UNIX timestamp, strtotime() valid string or DateTime object
	 * @param string|DateTimeZone $timezone Timezone string or DateTimeZone object
	 * @return boolean True if datetime string is within current month
	 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/time.html#testing-time
	 */
	public static function isThisMonth($dateString, $timezone = null) {
		$date = self::toTimeStamp($dateString, $timezone);
		return date('m Y', $date) == date('m Y', time());
	}

	/**
	 * Returns true if given datetime string is within current year.
	 *
	 * @param integer|string|DateTime $dateString UNIX timestamp, strtotime() valid string or DateTime object
	 * @param string|DateTimeZone $timezone Timezone string or DateTimeZone object
	 * @return boolean True if datetime string is within current year
	 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/time.html#testing-time
	 */
	public static function isThisYear($dateString, $timezone = null) {
		$date = self::toTimeStamp($dateString, $timezone);
		return date('Y', $date) == date('Y', time());
	}

	/**
	 * Returns true if given datetime string was yesterday.
	 *
	 * @param integer|string|DateTime $dateString UNIX timestamp, strtotime() valid string or DateTime object
	 * @param string|DateTimeZone $timezone Timezone string or DateTimeZone object
	 * @return boolean True if datetime string was yesterday
	 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/time.html#testing-time
	 *
	 */
	public static function wasYesterday($dateString, $timezone = null) {
		$date = self::toTimeStamp($dateString, $timezone);
		return date('Y-m-d', $date) == date('Y-m-d', strtotime('yesterday'));
	}

	/**
	 * Returns true if given datetime string is tomorrow.
	 *
	 * @param integer|string|DateTime $dateString UNIX timestamp, strtotime() valid string or DateTime object
	 * @param string|DateTimeZone $timezone Timezone string or DateTimeZone object
	 * @return boolean True if datetime string was yesterday
	 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/time.html#testing-time
	 */
	public static function isTomorrow($dateString, $timezone = null) {
		$date = self::toTimeStamp($dateString, $timezone);
		return date('Y-m-d', $date) == date('Y-m-d', strtotime('tomorrow'));
	}


}
