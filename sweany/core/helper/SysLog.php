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
	private static $baseStore	= array();
	private static $dbStore		= array();

	private static $timeStore	= array(
		'httpd'	=> 0,		// webserver reaction time
		'sql'	=> 0,		// total sql query time
		'core'	=> 0,		// core execution time
		'blocks'=> array(),	// separate block execution time
		'block'	=> 0,		// total block execution time
		'total'	=> 0,		// total script execution time
	);

	private static $queryTime	= 0;




	/******************************************  F U N C T I O N S  ******************************************/
	public static function time($type, $val)
	{
		if ( is_array($type) ) {
			self::$timeStore['blocks'][$type[0]] = $val;
		}else {
			self::$timeStore[$type] += $val;
		}
	}

	/**
	 *
	 * Handle Logging of errors (stdout and file)
	 *
	 *
	 * @param String $title
	 * 		Title for the error
	 *
	 * @param String $message
	 * 		Error message
	 *
	 * @param Array $trace
	 * 		Debug backtrace array (if desired)
	 *
	 * @param Array $start_time
	 * 		If specified, the logger will calculate the execution time
	 *
	 * @param Boolean $log_to_stdout
	 * 		Override the Settings' loglevel for output loggin
	 * 		This can be useful for the very start
	 * 		When the Settings' Loglevel has not been initialized yet
	 *
	 * @param Boolean $log_to_file
	 * 		Override the Settings' loglevel for file logging
	 * 		This can be useful for the very start
	 * 		When the Settings' Loglevel has not been initialized yet
	 */

	public static function e($title, $message, $trace = null, $start_time = null, $log_to_stdout = false, $log_to_file = false)
	{
		// Append information to logfile
		if ( \Sweany\Settings::$logFwErrors || $log_to_file )
		{
			self::_logToFile('ERROR', $title, $message);
		}

		// Only return here if settings allow and also if we do not break on error
		if ( !(\Sweany\Settings::$showFwErrors || $log_to_stdout) && !$GLOBALS['BREAK_ON_ERROR'] )
			return;

		$time = ($start_time) ? sprintf('%.6F',microtime(true)-$start_time).'s' : '';
		self::_store('baseStore', 'ERROR', $title, $message, $trace, $time, $log_to_stdout, $log_to_file);

		// Check for break on error!!!
		if ( $GLOBALS['BREAK_ON_ERROR'] )
		{
			echo '<h1 style="color:red">Break on Framework Error</h1>';
			self::show();
			exit();
		}
	}

	public static function w($title, $message, $trace = null, $start_time = null, $log_to_stdout = false, $log_to_file = false)
	{
		// Append information to logfile
		if ( \Sweany\Settings::$logFwErrors > 1 || $log_to_file )
		{
			self::_logToFile('WARNING', $title, $message);
		}

		if ( !(\Sweany\Settings::$showFwErrors > 1 || $log_to_stdout) )
			return;

		$time = ($start_time) ? sprintf('%.6F',microtime(true)-$start_time).'s' : '';
		self::_store('baseStore', 'WARNING', $title, $message, $trace, $time, $log_to_stdout, $log_to_file);

		if ( error_get_last() &&  $GLOBALS['BREAK_ON_ERROR'] )
		{
			echo '<h1 style="color:red">Break on Framework Error</h1>';
			self::show();
			exit();
		}
	}

	public static function i($title, $message, $trace = null, $start_time = null, $log_to_stdout = false, $log_to_file = false)
	{
		// Append information to logfile
		if ( \Sweany\Settings::$logFwErrors > 2 || $log_to_file )
		{
			self::_logToFile('INFO', $title, $message);
		}

		if ( !(\Sweany\Settings::$showFwErrors > 2 || $log_to_stdout) )
			return;

		$time = ($start_time) ? sprintf('%.6F',microtime(true)-$start_time).'s' : '';
		self::_store('baseStore', 'INFO', $title, $message, $trace, $time, $log_to_stdout, $log_to_file);

		if ( error_get_last() &&  $GLOBALS['BREAK_ON_ERROR'] )
		{
			echo '<h1 style="color:red">Break on Framework Error</h1>';
			self::show();
			exit();
		}
	}


	public static function sqlError($title, $message, $query, $error = array(), $time = null)
	{
		// Append information to logfile
		if ( \Sweany\Settings::$logSqlErrors )
		{
			self::_logToFile('ERROR', $title, self::_formatSQLMessageForFile($message, $query, null, $error));
		}

		if ( !\Sweany\Settings::$showSqlErrors && !$GLOBALS['BREAK_ON_ERROR'])
			return;

		self::_store('dbStore', 'ERROR', $title, self::_formatSQLMessageForHTML($message, $query, null, $error), null, $time);

		// Check for break on error!!!
		if ( $GLOBALS['BREAK_ON_ERROR'] )
		{
			echo '<h1 style="color:red">Break on SQL Error</h1>';
			self::show();
			exit();
		}
	}

	public static function sqlWarn($title, $message, $query, $output, $time = null)
	{
		// Append information to logfile
		if ( \Sweany\Settings::$logSqlErrors > 1 )
		{
			self::_logToFile('WARNING', $title, self::_formatSQLMessageForFile($message, $query, $output, null));
		}

		if ( \Sweany\Settings::$showSqlErrors < 2 )
			return;

		self::_store('dbStore', 'WARNING', $title, self::_formatSQLMessageForHTML($message, $query, $output, null), null, $time);
	}

	public static function sqlInfo($title, $message, $query, $output, $time = null)
	{
		// Append information to logfile
		if ( \Sweany\Settings::$logSqlErrors > 2 )
		{
			self::_logToFile('INFO', $title, self::_formatSQLMessageForFile($message, $query, $output, null));
		}

		if ( \Sweany\Settings::$showSqlErrors < 3 )
			return;

		self::_store('dbStore', 'INFO', $title, self::_formatSQLMessageForHTML($message, $query, $output, null), null, $time);
	}

	public static function sqlAppendTime($time)
	{
		if ( !\Sweany\Settings::$showSqlErrors )
			return;

		self::$queryTime += $time;
	}



	public static function show($return = false)
	{
		if ( !(\Sweany\Settings::$showPhpErrors ||
			 \Sweany\Settings::$showFwErrors ||
			 \Sweany\Settings::$showSqlErrors ) && $return == false)
			return;

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
		$error	.= '<tr>';
		$error	.=		'<td>'.sprintf('%f', round($GLOBALS['SERVER_REACTION_TIME'], 10)).'s</td>';
		$error	.=		'<td><span style="color:#FFFFFF;">[Timing]</span></td>';
		$error	.=		'<td><strong>HTTPD</strong></td>';
		$error	.=		'<td>Webserver reaction time<div style="position:relative;left:50px;">+ The time it takes the webserver to parse the first file to php<br/>+ If this is slow, tune your webserver!</div></td>';
		$error	.= '</tr>';
		$error	.= '<tr>';
		$error	.=		'<td>'.sprintf('%f', round(self::$queryTime, 10)).'s</td>';
		$error	.=		'<td><span style="color:#FFFFFF;">[Timing]</span></td>';
		$error	.=		'<td><strong>SQL</strong></td>';
		$error	.=		'<td>Total SQL execution time</td>';
		$error	.= '</tr>';
		$error	.= '<tr>';
		$error	.=		'<td>'.sprintf('%f', round(self::$timeStore['core'], 10)).'s</td>';
		$error	.=		'<td><span style="color:#FFFFFF;">[Timing]</span></td>';
		$error	.=		'<td><strong>Sweany Core</strong></td>';
		$error	.=		'<td>Sweany core execution time<div style="position:relative;left:50px;">+ The time it takes until the whole sweany core has been fully executed<br/>+ Turn off VALIDATION_MODE to see the actual core speed!</div></td>';
		$error	.= '</tr>';

		$t_blocktime = 0;
		foreach (self::$timeStore['blocks'] as $block => $blocktime)
		{
			$t_blocktime += $blocktime;
			$error	.= '<tr>';
			$error	.=		'<td>'.sprintf('%f', round($blocktime, 10)).'s</td>';
			$error	.=		'<td><span style="color:#FFFFFF;">[Timing]</span></td>';
			$error	.=		'<td><strong>Block</strong></td>';
			$error	.=		'<td>'.$block.'</td>';
			$error	.= '</tr>';
		}
		$error	.= '<tr>';
		$error	.=		'<td>'.sprintf('%f', round($t_blocktime, 10)).'s</td>';
		$error	.=		'<td><span style="color:#FFFFFF;">[Timing]</span></td>';
		$error	.=		'<td><strong>All Blocks</strong></td>';
		$error	.=		'<td>Total Block execution time</td>';
		$error	.= '</tr>';
		$error	.= '<tr>';
		$error	.=		'<td>'.sprintf('%f', round(self::$timeStore['total']-self::$timeStore['core'], 10)).'s</td>';
		$error	.=		'<td><span style="color:#FFFFFF;">[Timing]</span></td>';
		$error	.=		'<td><strong>User</strong></td>';
		$error	.=		'<td>Total User generated code execution time<div style="position:relative;left:50px;">+ The execution time of the code that you have written (including SQL)<br/>+ If this is slow I can\'t help you!</div></td>';
		$error	.= '</tr>';
		$error	.= '<tr>';
		$error	.=		'<td>'.sprintf('%f', round(self::$timeStore['total'], 10)).'s</td>';
		$error	.=		'<td><span style="color:#FFFFFF;">[Timing]</span></td>';
		$error	.=		'<td><strong>Total</strong></td>';
		$error	.=		'<td>Total Script execution time<div style="position:relative;left:50px;">+ The actual execution of everything (PHP and SQL)</div></td>';
		$error	.= '</tr>';
		$error	.= '<tr>';
		$error	.=		'<td>'.sprintf('%f', round($GLOBALS['SERVER_REACTION_TIME']+self::$timeStore['total'], 10)).'s</td>';
		$error	.=		'<td><span style="color:#FFFFFF;">[Timing]</span></td>';
		$error	.=		'<td><strong>HTTPD</strong></td>';
		$error	.=		'<td>Webserver deliver time<div style="position:relative;left:50px;">+ The time, the webserver receives the parsed php code</td>';
		$error	.= '</tr>';
	
		$error	.= '<tr>';
		$error	.=		'<th><div style="width:82px;"></div></th>';
		$error	.=		'<th><div style="width:82px;"></div></th>';
		$error	.=		'<th><div style="width:142px;"></div></th>';
		$error	.=		'<th style="width:100%;"></th>';
		$error	.= '</tr>';
		$error .= '</table>';


		$error .= '<table>';

		// ---------------- Framework Logs
		for ($i=0; $i<sizeof(self::$baseStore); $i++)
		{
			$error .= '<tr>';
			$error .= '<td style="width:80px;">'.self::$baseStore[$i]['time'] .'&nbsp;</td>';

			$error .= '<td style="width:80px;">';
			switch ( self::$baseStore[$i]['type'] )
			{
				case 'ERROR': 		$error .= '<span style="color:#FF0000;">[ERROR]</span>';	break;
				case 'WARNING': 	$error .= '<span style="color:#FF6903;">[Warn]</span>';		break;
				case 'INFO': 		$error .= '<span style="color:#00FF00;">[Info]</span>';		break;
				default: 			$error .= '<span style="color:#FF0000;">[Unknown]</span>';	break;

			}
			$error .= '</td>';

			$color	= (self::$baseStore[$i]['type'] != 'INFO') ? 'color:#28F0BE;' : '';
			$error .= '<td style="width:140px;'.$color.'"><strong>'.self::$baseStore[$i]['title'] .'</strong></td>';

			$error .= '<td>';
			$error .= 	self::$baseStore[$i]['message'];
/*
			if (is_array(self::$baseStore[$i]['error']) )
			{
				$error .= '<font color="red">[PHP ERROR]</font> ';
				$error .= '<strong>'.self::$baseStore[$i]['error']['message'].'</strong>: ';
				$error .= self::$baseStore[$i]['error']['file'] .' on line '.self::$baseStore[$i]['error']['line'].'<br/>';
			}
			*/
			if ( sizeof(self::$baseStore[$i]['trace']) )
			{
//				$error .= '<pre>'.print_r(self::$baseStore[$i]['trace'], true) .'</pre><br/>';
			}

			$error .= '</td>';
			$error .= '</tr>';
		}


		// ---------------- MySQL Query Logs
		for ($i=0; $i<sizeof(self::$dbStore); $i++)
		{
			$query_time = (self::$dbStore[$i]['time']) ? sprintf('%f', round(self::$dbStore[$i]['time'], 10)).'s' : '';
			$error .= '<tr>';
			$error .= '<td>'.$query_time .'&nbsp;</td>';

			$error .= '<td>';
			switch ( self::$dbStore[$i]['type'] )
			{
				case 'ERROR': 		$error .= '<span style="color:#FF0000;">[SQL ERR]</span>';		break;
				case 'WARNING': 	$error .= '<span style="color:#FF6903;">[SQL WARN]</span>';		break;
				case 'INFO': 		$error .= '<span style="color:purple;">[SQL INFO]</span>';		break;
				default: 			$error .= '<span style="color:#FF0000;">[SQL UNKNOWN]</span>';	break;

			}

			$error .= '</td>';
			$color	= (self::$dbStore[$i]['type'] != 'INFO') ? 'color:#28F0BE;' : '';
			$error .= '<td style="'.$color.'"><strong>'.self::$dbStore[$i]['title'] .'</strong></td>';

			$error .= '<td>';
			$error .=	self::$dbStore[$i]['message'];
			if ( isset(self::$dbStore[$i]['trace']) ) {
				$error .= 	'<pre>'.print_r(self::$dbStore[$i]['trace'], true).'</pre>';
			}
			$error .= '</td>';
			$error .= '</tr>';

		}

		if ( $GLOBALS['SQL_ENABLE'] )
		{
		//if ( \Sweany\Settings::$showSqlErrors > 2 )
		//{
			// Append total query time
			$error .= '<tr>';
			$error .= 	'<td>&nbsp;</td>';
			$error .= 	'<td><span style="color:pink;">[SQL SUM]</span></td>';
			$error .= 	'<td style="color:#28F0BE;"><strong>Total Query Time</strong></td>';
			$error .= 	'<td><span style="color:green;">All queries took: '.sprintf('%f', round(self::$queryTime, 10)).' seconds</strong></td>';
			$error .= '</tr>';
		//}
		}

		//if ( \Sweany\Settings::$showFwErrors > 2 )
		//{
			// MISC
			$error .= '<tr>';
			$error .= 	'<td>&nbsp;</td>';
			$error .= 	'<td><span style="color:pink;">[SESS]</span></td>';
			$error .= 	'<td style="color:#28F0BE;"><strong>SESSION</strong></td>';
			$error .= 	'<td>';
			$error .=		self::_traverseSession(isset($_SESSION)?$_SESSION:null, 1);
			$error .=	'</td>';
			$error .= '</tr>';
		//}

		$error .= '</table>';

		if ($return)
			return $pre.$error.$post;
		else
			echo $pre.$error.$post;
	}



	/******************************************  P R I V A T E   F U N C T I O N S  ******************************************/

	private static function _formatSQLMessageForHTML($message, $query, $output = null, $error = null)
	{
		$err_msg	= '';
		$title		= '<div style="font-weight:bold; color:blue;">'.$message.'</div>';
//		$output		= '<pre>'.print_r($output, true).'</pre>';
		$output		= '';

		if ( count($error) )
		{
			$err_msg = '<div style="font-weight:bold;color:red">'.$error[0].' '.$error[1].'</div>';
		}
		return $title.$err_msg.self::_formatSQLQuery($query).$output;
	}
	private static function _formatSQLMessageForFile($message, $query, $output = null, $error = null)
	{
		return $query;
	}

	private static function _formatSQLQuery($query)
	{
		$pre	= '';//'<pre>';
		$post	= '';//'</pre>';
		$query	= preg_replace('/\s+/', ' ', $query);
		$query	= trim($query);
		$query	= self::_loopSQLQuery($query);
		$query	= self::_highlightSQLQuery($query);

		return $pre.$query.$post;
	}
	private static function _loopSQLQuery($query)
	{
		for ($i=0; $i<strlen($query); $i++)
		{
		}
		return $query;
	}

	private static function _highlightSQLQuery($query)
	{
		$options['colors'] = array('#00A9FD' => array(
			'MASTER_SSL_VERIFY_SERVER_CERT', 'SQL_CALC_FOUND_ROWS', 'SECOND_MICROSECOND', 'NO_WRITE_TO_BINLOG', 'MINUTE_MICROSECOND',
			'CURRENT_TIMESTAMP', 'SQL_SMALL_RESULT', 'HOUR_MICROSECOND', 'DAY_MICROSECOND', 'LOCALTIMESTAMP', 'SQL_BIG_RESULT',
			'DETERMINISTIC', 'MINUTE_SECOND', 'STRAIGHT_JOIN', 'UTC_TIMESTAMP', 'HIGH_PRIORITY', 'CURRENT_TIME', 'CURRENT_USER',
			'VARCHARACTER', 'SQLEXCEPTION', 'CURRENT_DATE', 'LOW_PRIORITY', 'INSENSITIVE', 'HOUR_MINUTE', 'DISTINCTROW',
			'HOUR_SECOND', 'CONSTRAINT', 'READ_WRITE', 'DAY_MINUTE', 'DAY_SECOND', 'OPTIONALLY', 'MEDIUMTEXT', 'MEDIUMBLOB',
			'REFERENCES', 'YEAR_MONTH', 'ASENSITIVE', 'TERMINATED', 'ACCESSIBLE', 'SQLWARNING', 'CONDITION', 'PROCEDURE',
			'DATABASES', 'MEDIUMINT', 'PRECISION', 'MIDDLEINT', 'LOCALTIME', 'VARBINARY', 'CHARACTER', 'SEPARATOR', 'SENSITIVE',
			'TRAILING', 'INTERVAL', 'UNSIGNED', 'OPTIMIZE', 'RESTRICT', 'UTC_DATE', 'UTC_TIME', 'ZEROFILL', 'LONGBLOB', 'SMALLINT',
			'MODIFIES', 'SPECIFIC', 'SQLSTATE', 'STARTING', 'TINYBLOB', 'TINYTEXT', 'LONGTEXT', 'FULLTEXT', 'DATABASE', 'DAY_HOUR',
			'ENCLOSED', 'DISTINCT', 'CONTINUE', 'DESCRIBE', 'ITERATE', 'INTEGER', 'CONVERT', 'REQUIRE', 'REPLACE', 'RELEASE',
			'DECLARE', 'PRIMARY', 'NUMERIC', 'DECIMAL', 'NATURAL', 'EXPLAIN', 'DEFAULT', 'DELAYED', 'OUTFILE', 'COLLATE', 'ESCAPED',
			'VARYING', 'VARCHAR', 'FOREIGN', 'ANALYZE', 'TRIGGER', 'LEADING', 'TINYINT', 'BETWEEN', 'SCHEMAS', 'CASCADE', 'SPATIAL',
			'UNIQUE', 'UNLOCK', 'CHANGE', 'CREATE', 'VALUES', 'DELETE', 'SELECT', 'COLUMN', 'OPTION', 'REVOKE', 'LINEAR', 'BIGINT',
			'SCHEMA', 'BINARY', 'REPEAT', 'RENAME', 'RETURN', 'CURSOR', 'UPDATE', 'BEFORE', 'REGEXP', 'INSERT', 'HAVING', 'IGNORE',
			'ELSEIF', 'EXISTS', 'FLOAT8', 'INFILE', 'FLOAT4', 'DOUBLE', 'RIGHT', 'CHECK', 'FETCH', 'PURGE', 'RANGE', 'FALSE', 'READS',
			'LEAVE', 'CROSS', 'INNER', 'INOUT', 'INDEX', 'RLIKE', 'TABLE', 'LIMIT', 'OUTER', 'WHILE', 'ALTER', 'USING', 'USAGE', 'COUNT',
			'WRITE', 'FLOAT', 'GRANT', 'WHERE', 'GROUP', 'LINES', 'UNION', 'FORCE', 'MATCH', 'ORDER', 'CASE', 'WITH', 'CHAR', 'BOTH',
			'WHEN', 'CALL', 'BLOB', 'FROM', 'THEN', 'EXIT', 'TRUE', 'SHOW', 'LOOP', 'KILL', 'REAL', 'READ', 'LEFT', 'LIKE', 'NULL',
			'LOAD', 'ELSE', 'LONG', 'EACH', 'LOCK', 'DUAL', 'DROP', 'KEYS', 'INT4', 'INT3', 'DESC', 'INT2', 'INT8', 'INT1', 'INTO',
			'UNDO', 'JOIN', 'INT', 'NOT', 'SQL', 'ADD', 'FOR', 'ALL', 'XOR', 'MOD', 'SET', 'AND', 'DIV', 'SSL', 'OUT', 'DEC', 'ASC',
			'KEY', 'USE', 'ON', 'IF', 'IS', 'TO', 'BY', 'OR', 'IN', 'AS'
		));
		$options['tags']['old_start']	= '`';
		$options['tags']['old_end']		= '`';
		$options['tags']['new_start']	= '<font color="purple">`</font><font color="navy">';
		$options['tags']['new_end']		= '</font><font color="purple">`</font>';
		return \Highlight::apply($query, $options);
	}


	private static function _logToFile($type, $title, $message)
	{
		// fopen, fwrite, fclose is the faster than file_put_contents (which isjust a wrapper for that)
		// fopen, fputs, fclose is fastest
		$fp	= fopen(LOG_PATH.DS.$GLOBALS['FILE_LOG_CORE'], 'a');
		fputs($fp, date('Y-m-d H:i:s')."\t['.$type.'] [".$title."]\t".strip_tags($message)."\n\r");
		fclose($fp);
	}
	private static function _store($section, $type, $title, $message, $trace, $time)
	{
		$size = count(self::$$section);
		self::${$section}[$size]['time']	= $time;
		self::${$section}[$size]['type']	= $type;
		self::${$section}[$size]['title']	= $title;
		self::${$section}[$size]['message']	= $message;
		self::${$section}[$size]['error']	= error_get_last();
		self::${$section}[$size]['trace']	= $trace;
	}


	private static function _traverseSession($arr, $depth)
	{
		if ( !is_array($arr) )
			return;

		$error = '';
		$tab = self::_getSpace($depth*4);

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
	private static function _getSpace($num)
	{
		$space ='';
		for ($i=0; $i<$num; $i++)
			$space .= '&nbsp;';

		return $space;
	}
}

