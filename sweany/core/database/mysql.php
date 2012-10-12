<?php
namespace Sweany;
class mysql extends aBootTemplate implements iDBO
{

	/* ************************************************************************************************************************** *
	 *
	 *	STATIC VARIABLES
	 *
	 * ************************************************************************************************************************** */

	private	static $link		= null;
	private static $query		= null;
	private static $data		= null;

	public static $srvTime		= null;
	public static $srvTimeOff	= null;



	/* ************************************************************************************************************************** *
	 *
	 *	STATIC FUNCTIONS
	 *
	 * ************************************************************************************************************************** */


	public static function initialize($options = null)
	{
		$host		= $options['host'];
		$db			= $options['database'];
		$user		= $options['user'];
		$pass		= $options['pass'];

		self::$link	= mysql_connect($host, $user, $pass);

		if (!self::$link)
		{
			self::$error = 'Connect, Could not connect to host: '.$host;
			return false;
		}

		if (!mysql_select_db($db, self::$link))
		{
			self::$error = 'Database, '.'Coult not select db: '.$db;
			return false;
		}

		// activate total utf-8
		if (!mysql_set_charset('utf8', self::$link))
		{
			self::$error = 'Charset, Coult not be set to utf8';
			return false;
		}
		if (!mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", self::$link))
		{
			self::$error = 'Encodings and Connections could not be set to utf8';
			return false;
		}
		return true;
	}

	/* ********************************************** OVERRIDE CLEANUP ***********************************************/

	public static function cleanup()
	{
		if (is_object(self::$link))
			mysql_close(self::$link);
	}




	/* ************************************************************************************************************************** *
	 *
	 *	BASIC FUNCTIONS
	 *
	 * ************************************************************************************************************************** */

	public function __construct()
	{
	}

	/**
	 *
	 * @Override
	 */
	public function escape($string, $quote = false)
	{
		$q = ($quote) ? "'" : '';
		return $q.mysql_real_escape_string($string, self::$link).$q;
	}




	/* ************************************************************************************************************************** *
	 *
	 *	SQL FUNCTIONS
	 *
	 * ************************************************************************************************************************** */


	/* **************************************************** GENERIC SELECT **************************************************** */


	/**
	 *	Raw select function (optional with callback)
	 *
	 *	Use with caution and escape the query before you go
	 *
	 *	@param	string		$query
	 *	@param	function	$callback = function ($row, &$data){}
	 *	@return	mixed[]		$data
	 */
	public function select($query,  $callback = null)
	{
		self::$query = $query;

		$start	= microtime(true);
		$result = mysql_query($query, self::$link);
		$time	= microtime(true) - $start;

		$data	= array();

		if (!$result)
		{
			SysLog::sqlError('select', 'mysql_query failed', $query, array(mysql_errno(self::$link),mysql_error(self::$link), $time));
			return (-1);
		}

		if ( $callback )
		{
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			{
				$callback($row, $data);
			}
		}
		else
		{
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			{
				$data[] = $row;
			}
		}

		if (!mysql_free_result($result))
			SysLog::sqlError('Cannot Free Result', 'mysql_free_result failed', $query, array(mysql_errno(self::$link),mysql_error(self::$link), $time));

		SysLog::sqlAppendTime($time);
		SysLog::sqlInfo('select', null, $query, $data, $time);

		return ($data);
	}



	/* **************************************************** FETCH **************************************************** */

	/**
	 *	Fetch a specific field by WHERE condition
	 *
	 *	@param	string		$table		Table to work on
	 *	@param	string		$field		Name of the field
	 *	@param	mixed[]		$condition	Escapable condition
	 *		Array (
	 *			[0]	=>	'`id` = :foo AND `username` LIKE %:bar%',
	 *			[1]	=>	Array (
	 *				':foo' 	=> $id,
	 *				':bar'	=> $name
	 *			),
	 *		);
	 *
	 *	@return	mixed		Value of the field (or null if empty)
	 */
	public function fetchField($table, $field, $condition)
	{
		$where	= $condition ? 'WHERE '.$this->prepare($condition) : '';
		$query	= sprintf('SELECT `%s` FROM `%s` %s;', $field, $table, $where);
		$data	= $this->select($query);

		if ( !isset($data[0][$field]) )
		{
			SysLog::sqlWarn('fetchField', 'returning empty field', self::$query, $data);
			return null;
		}
		if ( count($data) > 1 )
		{
			SysLog::sqlWarn('fetchField', 'result hash more than one row', self::$query, $data);
		}
		return $data[0][$field];
	}


	/**
	 *	Fetch a specific field of a row (by id)
	 *
	 *	@param	string		$table		Table to work on
	 *	@param	string		$field_name	Name of the field
	 *	@param	integer		$id			Id of the row
	 *	@return	mixed		Value of the field (or null if empty)
	 */
	public function fetchRowField($table, $field, $id)
	{
		$condition = sprintf('`id` = %d', (int)$id);
		return $this->fetchField($table, $field, $condition);
	}


	/* **************************************************** COUNT **************************************************** */

	/**
	 *	Count Row(s) by WHERE condition
	 *
	 *	@param	string		$table		Table to work on
	 *	@param	mixed[]		$condition	Escapable condition
	 *		Array (
	 *			[0]	=>	'`id` = :foo AND `username` LIKE %:bar%',
	 *			[1]	=>	Array (
	 *				':foo' 	=> $id,
	 *				':bar'	=> $name
	 *			),
	 *		);
	 *
	 *	@return	integer		Number of rows
	 */
	public function count($table, $condition)
	{
		$where	= $condition ? 'WHERE '.$this->prepare($condition) : '';
		$query	= sprintf('SELECT COUNT(*) AS counter FROM `%s` %s;', $table, $where);
		$data	= $this->select($query);

		return (isset($data[0]['counter'])) ? $data[0]['counter'] : 0;
	}


	/**
	 *	Count Row(s) by Distinct field and WHERE condition
	 *
	 *	@param	string		$table		Table to work on
	 *	@param	string		$field		Field to apply DISTINCT() on
	 *	@param	mixed[]		$condition	Escapable condition
	 *		Array (
	 *			[0]	=>	'`id` = :foo AND `username` LIKE %:bar%',
	 *			[1]	=>	Array (
	 *				':foo' 	=> $id,
	 *				':bar'	=> $name
	 *			),
	 *		);
	 *
	 *	@return	integer		Number of rows
	 */
	public function countDistinct($table, $field, $condition)
	{
		$where	= $condition ? 'WHERE '.$this->prepare($condition) : '';
		$query	= sprintf('SELECT COUNT(DISTINCT `%s`) AS counter FROM `%s` %s;', $field, $table, $where);
		$data	= $this->select($query);

		return (isset($data[0]['counter'])) ? $data[0]['counter'] : 0;
	}


	/**
	 *	Check if Row (by id) exists
	 *
	 *	@param	string		$table		Table to work on
	 *	@param	integer		$id			Id of the row
	 *	@return	boolean		yes|no
	 */
	public function rowExists($table, $id)
	{
		$count	= (is_numeric($id)) ? $this->count($table, sprintf('`id` = %d', (int)$id)) : 0;

		if ($count > 1)
		{
			SysLog::sqlWarn('rowExists', 'More than one row exists', $query);
		}

		return (bool)$count;
	}



	/* **************************************************** ADD **************************************************** */


	/**
	 *	Insert
	 *
	 *	@param	string			$table				Table to work on
	 *	@param	mixed[]			$fields				Array of name-value pairs of fields to update
	 *	@param	boolean			$ret_ins_id			Return last insert id?
	 *	@return	boolean|integer	success|insert id
	 */
	public function insert($table, $fields, $ret_ins_id)
	{
		$names	= implode(',', array_map( create_function('$key', 'return "`".$key."`";'), array_keys($fields)));
		$values = implode(',', array_map( function($val) {return $this->escape($val, true);}, array_values($fields)));

		$query	= sprintf('INSERT INTO `%s` (%s) VALUES(%s);', $table, $names, $values);

		$start	= microtime(true);
		$result	= mysql_query($query, self::$link);
		$time	= microtime(true) - $start;

		if (!$result)
		{
			SysLog::sqlError('insertRow', 'mysql_query failed', $query, array(mysql_errno(self::$link),mysql_error(self::$link), $time));
			return false;
		}
		SysLog::sqlAppendTime($time);
		SysLog::sqlInfo('insertRow', null, $query, null, $time);

		// return last insert id ?
		return ($ret_ins_id) ? $this->_getLastInsertId() : true;
	}




	/* **************************************************** UPDATE **************************************************** */

	/**
	 *	Update Row(s) by WHERE condition
	 *
	 *	@param	string		$table		Table to work on
	 *	@param	mixed[]		$fields		Array of name-value pairs of fields to update
	 *	@param	mixed[]		$condition	Escapable condition
	 *		Array (
	 *			[0]	=>	'`id` = :foo AND `username` LIKE %:bar%',
	 *			[1]	=>	Array (
	 *				':foo' 	=> $id,
	 *				':bar'	=> $name
	 *			),
	 *		);
	 *
	 *	@return	boolean		success
	 */
	public function update($table, $fields, $condition)
	{
		// Prepare where clause
		$where		= $condition ? 'WHERE '.$this->prepare($condition) : '';

		// Prepare fields
		$fields	= implode(',', array_map( create_function('$key, $val', 'return "`".$key."`=\'".mysql_real_escape_string($val)."\'";'), array_keys($fields), array_values($fields)));

		$query	= sprintf('UPDATE `%s` SET %s %s;', $table, $fields, $where);

		$start	= microtime(true);
		$result	= mysql_query($query, self::$link);
		$time	= microtime(true) - $start;

		if (!$result)
		{
			SysLog::sqlError('update', 'mysql_query failed', $query, array(mysql_errno(self::$link),mysql_error(self::$link), $time));
			return false;
		}

		SysLog::sqlAppendTime($time);
		SysLog::sqlInfo('update', null, $query, null, $time);

		return true;
	}


	/**
	 *	Update Row by Id
	 *
	 *	@param	string		$table		Table to work on
	 *	@param	intege		$id			Id of the row
	 *	@param	mixed[]		$fields		Array of name-value pairs of fields to update
	 *	@return	boolean		success
	 */
	public function updateRow($table, $fields, $id)
	{
		return $this->update($table, $fields, sprintf('`id` = %d', (int)$id));
	}


	/**
	 *	Increment fields(s) and update other fields (such as modified)
	 *
	 *	@param	string		$table
	 *	@param	string[]	$incFields	Array of field names to increment
	 *	@param	mixed[]		$updFields	Array of name-value pair of fields to update
	 *	@param	mixed[]		$condition	Escapable condition
	 *		Array (
	 *			[0]	=>	'`id` = :foo AND `username` LIKE %:bar%',
	 *			[1]	=>	Array (
	 *				':foo' 	=> $id,
	 *				':bar'	=> $name
	 *			),
	 *		);
	 *
	 *	@return	boolean	success
	 */
	public function incrementFields($table, $incFields, $updFields, $condition)
	{
		// Prepare where clause
		$where		= $condition ? 'WHERE '.$this->prepare($condition) : '';

		// Anonymous functions
		$fIncFields = function($field){
			return '`'.$field.'` = `'.$field.'` + 1';
		};
		$fUpdFields = function($field, $value){
			return '`'.$field.'` = '.$this->escape($value, true);
		};

		// Apply anonymous functions
		$incFields	= is_array($incFields) ? $incFields : array();
		$updFields	= is_array($updFields) ? $updFields : array();
		$incFields	= implode(',', array_map($fIncFields, array_values($incFields)));
		$updFields	= implode(',', array_map($fUpdFields, array_keys($updFields), array_values($updFields)));
		$fields		= $updFields ? $incFields.', '.$updFields : $incFields;

		// Build query
		$query		= sprintf('UPDATE `%s` SET %s %s;', $table, $fields, $where);

		// Fire!
		$start		= microtime(true);
		$result		= mysql_query($query, self::$link);
		$time		= microtime(true) - $start;

		if (!$result)
		{
			SysLog::sqlError('incrementField', 'mysql_query failed', $query, array(mysql_errno(self::$link),mysql_error(self::$link), $time));
			return false;
		}

		SysLog::sqlAppendTime($time);
		SysLog::sqlInfo('incrementFields', $incFields, $query, null, $time);

		return true;
	}




	/* **************************************************** DELETE **************************************************** */

	/**
	 *	Delete by WHERE condition
	 *
	 *	@param	string	$table
	 *	@param	mixed[]	$condition	escapable condition
	 *		Array (
	 *			[0]	=>	'`id` = :foo AND `username` LIKE %:bar%',
	 *			[1]	=>	Array (
	 *				':foo' 	=> $id,
	 *				':bar'	=> $name
	 *			),
	 *		);
	 *
	 *	@return	boolean	success
	 */
	public function delete($table, $condition)
	{
		$where	= $condition ? 'WHERE '.$this->prepare($condition) : '';
		$query	= sprintf('DELETE FROM `%s` %s;', $table, $where);

		$start	= microtime(true);
		$result	= mysql_query($query, self::$link);
		$time	= microtime(true) - $start;

		if (!$result)
		{
			SysLog::sqlError('delete', 'mysql_query failed', $query, array(mysql_errno(self::$link),mysql_error(self::$link), $time));
			return false;
		}

		SysLog::sqlAppendTime($time);
		SysLog::sqlInfo('delete', null, $query, null, $time);

		return true;
	}


	/**
	 *	Delete row by ID
	 *
	 *	@param	string	$table
	 *	@param	integer	$id
	 *	@return	boolean	success
	 */
	public function deleteRow($table, $id)
	{
		return $this->delete($table, sprintf('`id` = %d', (int)$id));
	}



	/* ************************************************************************************************************************** *
	 *
	 *	HELPER FUNCTIONS
	 *
	 * ************************************************************************************************************************** */
	public function getNowDateTime()
	{
		return date('Y-m-d H:i:s');
	}
	public function getNowTimeStamp()
	{
		return date('Y-m-d H:i:s');
	}
	public function getNowUnixTimeStamp()
	{
		return time();
	}

	public function tableExists($table)
	{
		$query = 'show tables like "'.$table.'"';
		$data	= $this->select($query, function($row, &$data){ $data = $row; });
		return (bool)count($data);
	}


	public function getColumnNames($table)
	{
		$query = 'SELECT
					COLUMN_NAME AS name
				FROM
					information_schema.columns
				WHERE
					TABLE_SCHEMA = DATABASE()
				AND
					TABLE_NAME = \''.$table.'\'';

		return $this->select($query, function($row, &$data){ $data[] = $row['name']; });
	}
	public function getColumnTypes($table)
	{
		$query = 'SELECT
					COLUMN_NAME AS name,
					DATA_TYPE AS `type`
				FROM
					information_schema.columns
				WHERE
					TABLE_SCHEMA = DATABASE()
				AND
					TABLE_NAME = \''.$table.'\'';

		return $this->select($query, function($row, &$data){ $data[$row['name']] = $row['type']; });
	}

	public function getPrimaryKey($table)
	{
		$query = 'SELECT
					k.COLUMN_NAME AS pk
				FROM
					information_schema.table_constraints AS t
				LEFT JOIN
					information_schema.key_column_usage k
				USING
					(constraint_name,table_schema,table_name)
				WHERE
					t.constraint_type=\'PRIMARY KEY\'
				AND
					t.table_schema=DATABASE()
				AND
					t.table_name=\''.$table.'\'';

		return $this->select($query, function($row, &$data){ $data[] = $row['pk']; });
	}






	/**********************************************************************************************************************************
	 *
	 * 	P R I V A T E   F U N C T I O N S
	 *
	 **********************************************************************************************************************************/



	private function _getLastInsertId()
	{
		$result	= mysql_query('SELECT LAST_INSERT_ID() AS id');
		$row	= mysql_fetch_array($result, MYSQL_ASSOC);
		$id		= $row['id'];
		SysLog::sqlInfo('lastInsertId', $id, null, null);
		return $id;
	}



	/**
	 *	Prepares and escapes a statement
	 *
	 *	@param	mixed[]	$statement
	 *
	 *		example:
	 *		Array (
	 *			[0]	=>	'`id` = :id AND `username` LIKE %:name%',
	 *			[1]	=>	Array (
	 *				':id' 	=> $id,
	 *				':name'	=> $name
	 *			),
	 *		);
	 *
	 *	@return	string	escape safe string
	 */
	public function prepare($statement = null)
	{
		// Non escapable string
		if ( is_string($statement) )
		{
			return $statement;
		}

		$stmt	= (isset($statement[0]) && is_string($statement[0]) && strlen($statement[0]))	? $statement[0] : null;
		$vars	= (isset($statement[1])	&& is_array($statement[1])	&& count($statement[1]))	? $statement[1] : null;

		if ( !$stmt || !$vars )
		{
			return $stmt;
		}

		$fPrepare = function(&$value, $key) {
			if ($key[0]==':') {
				$value = $this->escape($value, true);
			}
		};
		array_walk($vars, $fPrepare);
		return str_replace(array_keys($vars), array_values($vars), $stmt);
	}
}
