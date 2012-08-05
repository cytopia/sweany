<?php
/**
 * Time Module
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
 * @package		sweany.lib
 * @author		Patu
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @version		0.7 2012-07-29 13:25
 *
 */
class MyTime
{
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