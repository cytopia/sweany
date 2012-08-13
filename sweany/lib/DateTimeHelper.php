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
 * Sweaby is distributed in the hope that it will be useful,
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
 * Time Module
 *
 * TODO: currently contains only temporay stuff. Do not use as it will change!
 */
class DateTimeHelper
{


	/**
	 *
	 * returns a date string in the specified format
	 * or optionally also the name for 'today'/'yesterday' if
	 * the name is given and in the timespan.
	 *
	 * @param  datetime $dateTimeString
	 * @param  string   $format
	 * @param  string   $txtToday
	 * @param  string   $txtYesterday
	 * @return string   (format: Yesterday, Today or 01.01.2000)
	 */
	public static function getFormattedDate($dateTimeString, $format ='d.m.Y', $txtToday = null, $txtYesterday = null)
	{
		$TODAY		= date('Ymd');
		$YESTERDAY	= date('Ymd', time()-86400);	// (60*60*24)
		$checkDate	= date('Ymd',strtotime($dateTimeString));

		if ( strlen($txtToday) && $checkDate == $TODAY )
			return $txtToday;
		else if ( strlen($txtYesterday) && $checkDate == $YESTERDAY )
			return $txtYesterday;
		else
			return date($format, strtotime($dateTimeString));
	}


	public static function getNowTimestamp()
	{
		return time();
	}

	public static function getWeekDay($timestamp)
	{
		$day  = date("N", $timestamp);

		switch ($day)
		{
			case 1: return 'Montag';		break;
			case 2: return 'Dienstag';		break;
			case 3: return 'Mittwoch';		break;
			case 4: return 'Donnerstag';	break;
			case 5: return 'Freitag';		break;
			case 6: return 'Samstag';		break;
			case 7: return 'Sonntag';		break;
		}
	}
	public static function getWeekDayShort($timestamp)
	{
		$day  = date("N", $timestamp);

		switch ($day)
		{
			case 1: return 'Mo';	break;
			case 2: return 'Di';	break;
			case 3: return 'Mi';	break;
			case 4: return 'Do';	break;
			case 5: return 'Fr';	break;
			case 6: return 'Sa';	break;
			case 7: return 'So';	break;
		}
	}


	public static function getMonthName($timestamp)
	{
		$month = date("m", $timestamp);

		switch ($month)
		{
			case 1: 	return 'Januar';	break;
			case 2: 	return 'Februar';	break;
			case 3: 	return 'M&auml;rz';	break;
			case 4: 	return 'April';		break;
			case 5: 	return 'Mai';		break;
			case 6: 	return 'Juni';		break;
			case 7: 	return 'Juli';		break;
			case 8:		return 'August';	break;
			case 9:		return 'September';	break;
			case 10:	return 'Oktober';	break;
			case 11:	return 'Novermber';	break;
			case 12:	return 'Dezember';	break;
		}
	}
}

?>