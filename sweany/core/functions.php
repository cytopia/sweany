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
 * @package		sweany.core
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.7 2012-07-29 13:25
 *
 * basic functions
 */


/**
 * print_r improvement for html
 */
function debug($arr)
{
	echo '<pre>';
	print_r($arr);
	echo '</pre>';
}


/**
 * @Deprecated
 * TODO remove wrapper function, after checking that no other file is using it
 * returns microtime in miliseconds
 */
function getmicrotime()
{
	return microtime(true);
}

/**
 * Custom ob error handler
 *
 * When in debugging mode, ob_start will use
 * this function as a callback to be able
 * to display errors during output buffering.
 *
 * (In production mode it will use a compression func)
 */
function ob_error_handler($str)
{
	$error = error_get_last();

	// If error orrocured
	if ($error)
	{
		return ini_get('error_prepend_string').
					"\n".'Fatal error: '.$error['message'].' in '.$error['file'].' on line '.$error['line']."\n".
					ini_get('error_append_string');
	}
	return $str;
}
