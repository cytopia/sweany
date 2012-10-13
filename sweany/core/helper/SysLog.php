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
 *
 * System Logger
 */
namespace Sweany;

class SysLog
{
	/******************************************  V A R I A B L E S  ******************************************/



	/**
	 * Hold errors, warnings and info
	 * that will be displayed after
	 * page has been executed.
	 * (if debug level is set in config.php)
	 *
	 */
	private static $store		= array();

	private static $dec_digits	= 6;

	private static $timeStore	= array(
		'httpd'	=> 0,		// webserver reaction time
		'sql'	=> 0,		// total sql query time
		'core'	=> 0,		// core execution time
		'blocks'=> array(),	// separate block execution time
		'block'	=> 0,		// total block execution time
		'total'	=> 0,		// total script execution time
	);




	/******************************************  F U N C T I O N S  ******************************************/



	/**
	 *
	 * Handle Logging of errors (stdout and file)
	 *
	 *
	 * @param string 			$section		Error Section
	 * @param string 			$title			Error Title
	 * @param string 			$message		Error Message
	 * @param string|string[]	$description	String or array of strings of additional descriptions
	 * @param float				$time			Time of execution in miliseconds
	 * @param integer			$start_time		Unix timestamp of starting time
	 */
	public static function e($section, $title, $message, $description = null, $time = null, $start_time = null)
	{
		// Append information to logfile
		if ( Settings::$logFwErrors || Settings::$logSqlErrors ) {
			// calculate time
			if ($time) {
				$f_time = sprintf('%.'.self::$dec_digits.'F', $time).'s';
			}
			else if (!$time && $start_time) {
				$f_time = sprintf('%.'.self::$dec_digits.'F',microtime(true)-$start_time).'s';
			} else {
				$f_time = null;
			}
			self::_logToFile('error', $section, $title, $message, $description, $f_time);
		}

		// Do not put anything into the store, if we are in production mode or all error showing is turned off
		if ( $GLOBALS['RUNTIME_MODE'] == SWEANY_PRODUCTION ||
			!(Settings::$showFwErrors>0 || Settings::$showPhpErrors>0 || !Settings::$showSqlErrors>0 || $GLOBALS['BREAK_ON_ERROR']) ) {
			return;
		}

		// re-use time if has already been calculated
		if ( !$f_time ) {
			if ($time) {
				$f_time = sprintf('%.'.self::$dec_digits.'F', $time).'s';
			} else {
				$f_time = $start_time ? sprintf('%.'.self::$dec_digits.'F',microtime(true)-$start_time).'s' : '';
			}
		}

		$store = array(
			'time'		=> $time,
			'type'		=> 'error',
			'section'	=> $section,
			'title'		=> $title,
			'message'	=> $message,
			'description'=>$description,
			'error'		=> error_get_last(),
			'trace'		=> debug_backtrace(),
		);
		self::$store[] = $store;

		// Check for break on error!!!
		if ( $GLOBALS['BREAK_ON_ERROR'] ) {
			echo '<h1 style="color:red">Break on Framework Error</h1>';
			self::show();
			exit();
		}
	}



	/**
	 *
	 * Handle Logging of warnings (stdout and file)
	 *
	 *
	 * @param string 			$section		Warning Section
	 * @param string 			$title			Warning Title
	 * @param string 			$message		Warning Message
	 * @param string|string[]	$description	String or array of strings of additional descriptions
	 * @param float				$time			Time of execution in miliseconds
	 * @param integer			$start_time		Unix timestamp of starting time
	 */
	public static function w($section, $title, $message, $description = null, $time = null, $start_time = null)
	{
		$f_time = null;

		// Append information to logfile
		if ( Settings::$logFwErrors > 1 || Settings::$logSqlErrors > 1) {
			// calculate time
			if ($time) {
				$f_time = sprintf('%.'.self::$dec_digits.'F', $time).'s';
			}
			else if (!$time && $start_time) {
				$f_time = sprintf('%.'.self::$dec_digits.'F',microtime(true)-$start_time).'s';
			} else {
				$f_time = null;
			}
			self::_logToFile('warning', $section, $title, $message, $description, $f_time);
		}

		// Do not put anything into the store, if we are in production mode or all error showing is turned off
		if ( $GLOBALS['RUNTIME_MODE'] == SWEANY_PRODUCTION ||
			!(Settings::$showFwErrors>1 || Settings::$showPhpErrors>1 || Settings::$showSqlErrors>1) ) {
			return;
		}


		// re-use time if has already been calculated
		if ( !$f_time ) {
			if ($time) {
				$f_time = sprintf('%.'.self::$dec_digits.'F', $time).'s';
			} else {
				$f_time = $start_time ? sprintf('%.'.self::$dec_digits.'F',microtime(true)-$start_time).'s' : '';
			}
		}

		$store = array(
			'time'		=> $time,
			'type'		=> 'warning',
			'section'	=> $section,
			'title'		=> $title,
			'message'	=> $message,
			'description'=>$description,
			'error'		=> error_get_last(),
			'trace'		=> null,
		);
		self::$store[] = $store;

		if ( $store['error'] && $GLOBALS['BREAK_ON_ERROR'] ) {
			echo '<h1 style="color:red">Break on Framework Error</h1>';
			self::show();
			exit();
		}
	}



	/**
	 *
	 * Handle Logging of info (stdout and file)
	 *
	 *
	 * @param string 			$section		Info Section
	 * @param string 			$title			Info Title
	 * @param string 			$message		Info Message
	 * @param string|string[]	$description	String or array of strings of additional descriptions
	 * @param float				$time			Time of execution in miliseconds
	 * @param integer			$start_time		Unix timestamp of starting time
	 */
	public static function i($section, $title, $message, $description = null, $time = null, $start_time = null)
	{
		$f_time = null;

		// Append information to logfile
		if ( Settings::$logFwErrors > 2  || Settings::$logSqlErrors > 2) {
			// calculate time
			if ($time) {
				$f_time = sprintf('%.'.self::$dec_digits.'F', $time).'s';
			}
			else if (!$time && $start_time) {
				$f_time = sprintf('%.'.self::$dec_digits.'F',microtime(true)-$start_time).'s';
			} else {
				$f_time = null;
			}
			self::_logToFile('info', $section, $title, $message, $description, $f_time);
		}

		// Do not put anything into the store, if we are in production mode or all error showing is turned off
		if ( $GLOBALS['RUNTIME_MODE'] == SWEANY_PRODUCTION ||
			!(Settings::$showFwErrors>2 || Settings::$showPhpErrors>2 || Settings::$showSqlErrors>2) ) {
			return;
		}


		// re-use time if has already been calculated
		if ( !$f_time ) {
			if ($time) {
				$f_time = sprintf('%.'.self::$dec_digits.'F', $time).'s';
			} else {
				$f_time = $start_time ? sprintf('%.'.self::$dec_digits.'F',microtime(true)-$start_time).'s' : '';
			}
		}

		$store = array(
			'time'		=> $f_time,
			'type'		=> 'info',
			'section'	=> $section,
			'title'		=> $title,
			'message'	=> $message,
			'description'=>$description,
			'error'		=> error_get_last(),
			'trace'		=> null,
		);
		self::$store[] = $store;

		if ( $store['error'] && $GLOBALS['BREAK_ON_ERROR'] ) {
			echo '<h1 style="color:red">Break on Framework Error</h1>';
			self::show();
			exit();
		}
	}



	/**
	 * Log Total execution times of different types
	 *
	 * @param string	$type	Type of operation
	 * @param float		$val	Time in miliseconds
	 */
	public static function time($type, $val)
	{
		if ( is_array($type) ) {
			self::$timeStore['blocks'][$type[0]] = $val;
		}else {
			self::$timeStore[$type] += $val;
		}
	}



	/**
	 * Show SysLog Output (or return)
	 *
	 * @param	boolean			$return	Wheter to return the string, instead of displaying
	 * @return	void|string		Error message (as string) or nothing
	 */
	public static function show($return = false)
	{
		// If we are in production mode, dont show anything
		if ( $GLOBALS['RUNTIME_MODE'] == SWEANY_PRODUCTION ) {
			return;
		}
		// If all errors are turned off, dont show anything
		if ( !Settings::$showPhpErrors && !Settings::$showFwErrors && !Settings::$showSqlErrors ) {
			return;
		}


		$pre  	= '<style type="text/css">'.
					'.sweanylog {'.
						'border:1px solid #FFFFFF;'.
						'font-family:courier; font-size:12px;'.
						'color:#666666; background-color: black;'.
						'padding:10px; line-height:130%; text-align:left;'.
					'}'.
					'.sweanylog table {'.
						'border:1px solid #111111;'.
						'font-family:courier; font-size:12px;'.
						'color:#666666; background-color: black;'.
					'}'.
					'.sweanylog tr {'.
						'border:1px solid #111111;'.
						'font-family:courier; font-size:12px;'.
						'color:#666666; background-color: black;'.
					'}'.
					'.sweanylog td {'.
						'border:1px solid #111111;'.
						'font-family:courier; font-size:12px;'.
						'color:#666666; background-color: black;'.
					'}'.
					'.sweanylog h1 {'.
						'border:none;'.
						'font-family:courier; font-size:24px;'.
						'color:#666666; background-color: black;'.
					'}'.
				'</style>';
		$pre	.= '<div class="sweanylog">';

		$post	= '</div>';
		$error	= '<h1>Syslog</h1>';

		$lastErr=error_get_last();

		if ( is_array($lastErr) )
		{
			$error .= '<font color="red">[PHP ERROR]</font> ';
			$error .= '<strong>'.$lastErr['message'].'</strong>: ';
			$error .= $lastErr['file'] .' on line '.$lastErr['line'].'<br/><br/>';
		}


		$error .= '<table>';




		// ---------------- Timings
		$error	.= '<tr><td colspan=6>';
		$error	.= '<span style="font-size:18px; font-weight:bold;font-family:courier;">Timings</span>';
		$error	.= '</td></tr>';


		$error	.= '<tr>';
		$error	.=		'<td>'.sprintf('%f', round($GLOBALS['SERVER_REACTION_TIME'], 10)).'s</td>';
		$error	.=		'<td><span style="color:#FFFFFF;">[Timing]</span></td>';
		$error	.=		'<td><strong>external</strong></td>';
		$error	.=		'<td><strong>HTTPD</strong></td>';
		$error	.=		'<td>Webserver reaction time<div style="position:relative;left:50px;">+ The time it takes the webserver to parse the first file to php<br/>+ If this is slow, tune your webserver!</div></td>';
		$error	.=		'<td></td>';
		$error	.= '</tr>';
		$error	.= '<tr>';
		$error	.=		'<td>'.sprintf('%f', round(self::$timeStore['sql'], 10)).'s</td>';
		$error	.=		'<td><span style="color:#FFFFFF;">[Timing]</span></td>';
		$error	.=		'<td><strong>SQL</strong></td>';
		$error	.=		'<td>Total SQL execution time</td>';
		$error	.=		'<td></td>';
		$error	.= '</tr>';
		$error	.= '<tr>';
		$error	.=		'<td>'.sprintf('%f', round(self::$timeStore['core'], 10)).'s</td>';
		$error	.=		'<td><span style="color:#FFFFFF;">[Timing]</span></td>';
		$error	.=		'<td><strong>core</strong></td>';
		$error	.=		'<td><strong>Sweany Core</strong></td>';
		$error	.=		'<td>Sweany core execution time<div style="position:relative;left:50px;">+ The time it takes until the whole sweany core has been fully executed<br/>+ Turn off VALIDATION_MODE to see the actual core speed!</div></td>';
		$error	.=		'<td></td>';
		$error	.= '</tr>';

		$t_blocktime = 0;
		foreach (self::$timeStore['blocks'] as $block => $blocktime)
		{
			$t_blocktime += $blocktime;
			$error	.= '<tr>';
			$error	.=		'<td>'.sprintf('%f', round($blocktime, 10)).'s</td>';
			$error	.=		'<td><span style="color:#FFFFFF;">[Timing]</span></td>';
			$error	.=		'<td><strong>user</strong></td>';
			$error	.=		'<td><strong>Block</strong></td>';
			$error	.=		'<td>'.$block.'</td>';
			$error	.=		'<td></td>';
			$error	.= '</tr>';
		}
		$error	.= '<tr>';
		$error	.=		'<td>'.sprintf('%f', round($t_blocktime, 10)).'s</td>';
		$error	.=		'<td><span style="color:#FFFFFF;">[Timing]</span></td>';
		$error	.=		'<td><strong>user</strong></td>';
		$error	.=		'<td><strong>All Blocks</strong></td>';
		$error	.=		'<td>Total Block execution time</td>';
		$error	.=		'<td></td>';
		$error	.= '</tr>';
		$error	.= '<tr>';
		$error	.=		'<td>'.sprintf('%f', round(self::$timeStore['total']-self::$timeStore['core'], 10)).'s</td>';
		$error	.=		'<td><span style="color:#FFFFFF;">[Timing]</span></td>';
		$error	.=		'<td><strong>user</strong></td>';
		$error	.=		'<td><strong>User</strong></td>';
		$error	.=		'<td>Total User generated code execution time<div style="position:relative;left:50px;">+ The execution time of the code that you have written (including SQL)<br/>+ If this is slow I can\'t help you!</div></td>';
		$error	.=		'<td></td>';
		$error	.= '</tr>';
		$error	.= '<tr>';
		$error	.=		'<td>'.sprintf('%f', round(self::$timeStore['total'], 10)).'s</td>';
		$error	.=		'<td><span style="color:#FFFFFF;">[Timing]</span></td>';
		$error	.=		'<td><strong>All</strong></td>';
		$error	.=		'<td><strong>Total</strong></td>';
		$error	.=		'<td>Total Script execution time<div style="position:relative;left:50px;">+ The actual execution of everything (PHP and SQL)</div></td>';
		$error	.=		'<td></td>';
		$error	.= '</tr>';
		$error	.= '<tr>';
		$error	.=		'<td>'.sprintf('%f', round($GLOBALS['SERVER_REACTION_TIME']+self::$timeStore['total'], 10)).'s</td>';
		$error	.=		'<td><span style="color:#FFFFFF;">[Timing]</span></td>';
		$error	.=		'<td><strong>external</strong></td>';
		$error	.=		'<td><strong>HTTPD</strong></td>';
		$error	.=		'<td>Webserver deliver time<div style="position:relative;left:50px;">+ The time, the webserver receives the parsed php code</td>';
		$error	.=		'<td></td>';
		$error	.= '</tr>';

		$error	.= '<tr>';
		$error	.=		'<th><div style="width:82px;"></div></th>';
		$error	.=		'<th><div style="width:82px;"></div></th>';
		$error	.=		'<th><div style="width:142px;"></div></th>';
		$error	.=		'<th></th>';
		$error	.=		'<th></th>';
		$error	.=		'<th></th>';
		$error	.= '</tr>';





		// ---------------- Logging
		$error	.= '<tr><td colspan=6>';
		$error	.= '<span style="font-size:18px; font-weight:bold;font-family:courier;">Work Cycle</span>';
		$error	.= '</td></tr>';




		for ($i=0, $size = count(self::$store); $i<$size; ++$i)
		{
			$time		= self::$store[$i]['time'];
			$type		= self::$store[$i]['type'];
			$section	= self::$store[$i]['section'];
			$title		= self::$store[$i]['title'];
			$message	= self::$store[$i]['message'];
			$description= self::$store[$i]['description'];
			$err		= self::$store[$i]['error'];
			$trace		= self::$store[$i]['trace'];


			// Deactivate SQL Output, if desired by config
			if ( $section == 'sql' || $section == 'sql-query' )
			{
				if ( $type == 'info' && Settings::$showSqlErrors < 3 )
				{
					continue;
				}
				if ( $type == 'warning' && Settings::$showSqlErrors < 2 )
				{
					continue;
				}
			}
			// Deactivate Framework Output
			else
			{
				if ( $type == 'info' && Settings::$showFwErrors < 3 )
				{
					continue;
				}
				if ( $type == 'warning' && Settings::$showFwErrors < 2 )
				{
					continue;
				}
			}




			// format sql
			if ( $section == 'sql-query' ) {
				$message = \Highlight::sql($message);
			}
			if ($description) {
				if ( is_array($description) ) {
					$description = '<pre>'.print_r($description, true).'</pre>';
				}
				$description = '<br/><br/>'.$description;
			}
			if ($trace) {
				$trace = '<br/><br/><pre>'.print_r($trace, true).'</pre>';
			}

			switch ($type)
			{
				case 'error': 		$type = '<span style="color:#FF0000;">[ERROR]</span>';		break;
				case 'warning': 	$type = '<span style="color:#FF6903;">[Warn]</span>';		break;
				case 'info': 		$type = '<span style="color:#00FF00;">[Info]</span>';		break;
				default: 			$type = '<span style="color:#FF0000;">[Unknown]</span>';	break;

			}
			switch ($section)
			{
				case 'core':		$section = '<span style="color:#28F0BE;">'.$section.'</span>';	break;
				case 'core-module':	$section = '<span style="color:#20F0CC;">'.$section.'</span>';	break;
				case 'internal':	$section = '<span style="color:#2000CC;">'.$section.'</span>';	break;
				case 'sql':			$section = '<span style="color:purple;">'.$section.'</span>';	break;
				case 'sql-query':	$section = '<span style="color:purple;">'.$section.'</span>';	break;
				case 'user':		$section = '<span style="color:white;">'.$section.'</span>';	break;
				default:			$section = '<span style="color:red;">'.$section.'</span>';		break;
			}

			$error .= '<tr>';
			$error .=	'<td>'.$time.'&nbsp;</td>';
			$error .=	'<td>'.$type.'</td>';
			$error .=	'<td>'.$section.'</td>';
			$error .=	'<td>'.$title.'</td>';
			$error .=	'<td>'.$message/*.$description.$trace*/.'</td>';
			$error .=	'<td>'.$err.'</td>';
			$error .= '</tr>';
		}

		// APPEND SESSION
		$error .= '<tr>';
		$error .= 	'<td>&nbsp;</td>';
		$error .= 	'<td><span style="color:pink;">[SESS]</span></td>';
		$error .= 	'<td style="color:#28F0BE;"><strong>SESSION</strong></td>';
		$error .= 	'<td style="color:#28F0BE;"><strong>SESSION</strong></td>';
		$error .= 	'<td>';
		$error .=		self::_traverseSession(isset($_SESSION)?$_SESSION:null, 1);
		$error .=	'</td>';
		$error .= '</tr>';



		$error .= '</table>';

		if ($return)
			return $pre.$error.$post;
		else
			echo $pre.$error.$post;
	}



	/******************************************  P R I V A T E   F U N C T I O N S  ******************************************/
	private static function _logToFile($type, $section, $title, $message, $description, $time)
	{
		// Deactivate SQL Logging, if desired by config
		if ( $section == 'sql' || $section == 'sql-query' )
		{

			if ( $type == 'info' && Settings::$logSqlErrors < 3 )
			{
				return;
			}
			if ( $type == 'warning' && Settings::$logSqlErrors < 2 )
			{
				return;
			}
		}
		// Deactivate Framework Logging
		else
		{
			if ( $type == 'info' && Settings::$logFwErrors < 3 )
			{
				return;
			}
			if ( $type == 'warning' && Settings::$logFwErrors < 2 )
			{
				return;
			}
		}

		if ($description) {
			if ( is_array($description) ) {
				$description = "\n\r".strip_tags(print_r($description, true));
			} else {
				$description = "\n\r".strip_tags($description);
			}
			$description = html_entity_decode($description);
		}else {
			$description = null;
		}

		// fopen, fwrite, fclose is the faster than file_put_contents (which isjust a wrapper for that)
		// fopen, fputs, fclose is fastest
		$fp	= fopen(LOG_PATH.DS.$GLOBALS['FILE_LOG_CORE'], 'a');
		fputs($fp, date('Y-m-d H:i:s')."\t[$type] [$section] [$title]\t".html_entity_decode(strip_tags($message)).$description."\n\r");
		fclose($fp);
	}


	private static function _traverseSession($arr, $depth)
	{
		if ( !is_array($arr) )
			return;

		$error = '';
		$tab = \Html::nbsp($depth*4);

		foreach ($arr as $key => $val)
		{
			$error .= $tab.'<font color="blue">['.$key.']</font>';

			if ( is_array($val) )
			{
				$error .= '&nbsp;&nbsp;&nbsp;&nbsp; => Array<br/>'.$tab.'(<br/>'.self::_traverseSession($val, $depth+1);
				$error .= $tab.')<br/>';
			}
			else
			{

				$error .= '&nbsp;&nbsp;&nbsp;&nbsp; => '.$val.'<br/>';
			}
		}
		return $error;
	}
}
