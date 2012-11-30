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
 * Project Log Helper
 */

class LogCat
{
	/******************************************  V A R I A B L E S  ******************************************/





	/******************************************  F U N C T I O N S  ******************************************/


	/**
	 *
	 * Error
	 *
	 * @param String $message
	 * 		Error message
	 *
	 */
	public static function e($message)
	{
		self::_logToFile('ERROR', $message);
	}

	/**
	 *
	 * Warning
	 *
	 * @param String $message
	 * 		Error message
	 */
	 public static function w($message)
	 {
	 	self::_logToFile('WARNING', $message);
	 }

	 /**
	  *
	  * Info
	  *
	  * @param String $title
	  * 		Title for the error
	  *
	  */
	 public static function i($message)
	 {
	 	self::_logToFile('INFO', $message);
	 }



	/******************************************  P R I V A T E   F U N C T I O N S  ******************************************/

	private static function _logToFile($type, $message)
	{
		// fopen, fwrite, fclose is the faster than file_put_contents (which isjust a wrapper for that)
		// fopen, fputs, fclose is fastest
		$fp	= fopen(LOG_PATH.DS.$GLOBALS['FILE_LOG_USER'], 'a');

		$type_len	= strlen($type);
		$head		= '['.date('Y-m-d H:i:s').'] ---- ['.$type.'] '.self::_getChars(50-$type_len, '-');

		fputs($fp, $head."\n\r");
		fputs($fp, $message."\n\r\n\r");
		fclose($fp);
	}

	private static function _getChars($num, $char)
	{
		$space ='';
		for ($i=0; $i<$num; $i++) {
			$space .= $char;
		}
		return $space;
	}
}