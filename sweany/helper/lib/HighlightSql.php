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
 * @version		0.7 2012-07-29 13:25
 *
 *
 * Highlighter (linux's dmidecode)
 */
class HighlightSql
{
	private static $Keywords = array(
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
	);

	private static $Functions = array(
		'GREATEST',
	);


	public static function hl($string)
	{
		$string	= preg_replace('/\s+/', ' ', $string);
		$string	= trim($string);

		$keys	= array_merge(self::$Keywords, self::$Functions);


		$options['colors'] = array('#00A9FD' => $keys);

		$options['tags']['old_start']	= '`';
		$options['tags']['old_end']		= '`';
		$options['tags']['new_start']	= '<font color="purple">`</font><font color="gray">';
		$options['tags']['new_end']		= '</font><font color="purple">`</font>';

		$string = Highlight::custom($string, $options);
		unset($options);

		$options['tags']['old_start']	= '\'';
		$options['tags']['old_end']		= '\'';
		$options['tags']['new_start']	= '<font color="purple">\'</font><font color="lightgray">';
		$options['tags']['new_end']		= '</font><font color="purple">\'</font>';

		$string = Highlight::custom($string, $options);
		return $string;
	}
}