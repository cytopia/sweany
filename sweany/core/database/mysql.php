<?php
namespace Sweany;
class mysql extends aBootTemplate implements iDBO
{

	/* ******************************************** VARIABLES ********************************************/

	private	static $link		= null;
	private static $query		= null;
	private static $data		= null;

	public static $srvTime		= null;
	public static $srvTimeOff	= null;



	/*********************************************** OVERRIDE INITIALIZE ***********************************************/

	public static function initialize($options = null)
	{
		$host		= $options['host'];
		$db			= $options['database'];
		$user		= $options['user'];
		$pass		= $options['pass'];

		self::$link	= @mysql_connect($host, $user, $pass);

		if (!self::$link)
		{
			self::$error = 'Connect, Could not connect to host: '.$host;
			return false;
		}

		if (!@mysql_select_db($db, self::$link))
		{
			self::$error = 'Database, '.'Coult not select db: '.$db;
			return false;
		}

		// activate total utf-8
		if (!@mysql_set_charset('utf8', self::$link))
		{
			self::$error = 'Charset, Coult not be set to utf8';
			return false;
		}
		if (!@mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", self::$link))
		{
			self::$error = 'Encodings and Connections could not be set to utf8';
			return false;
		}


		/**
		 *	If MySQL Server and Apache Server are on a different host,
		 *  they could likely have a different time set, if the admin
		 *  is a fucking stupid dickhead (as those from 0fees.net)
		 *  So we have to manually adjust the MySQL time every time
		 *  we do a page load
		 *
		 *  TODO: Only activate if this is the case
		 */
		/*
		if (!self::_setServerTimeZone())
		{
			self::$error = 'Timezone, Coult not set timezone offset '.date("P", time());
			return FALSE;
		}*/

		return true;
	}

	/*********************************************** OVERRIDE CLEANUP ***********************************************/

	public static function cleanup()
	{
		if (is_object(self::$link))
			mysql_close(self::$link);
	}

	/*********************************************** CLASS FUNCTIONS ***********************************************/

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



	/**
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
			// TODO: use: mysql_fetch_assoc | might be faster
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			{
				$callback($row, $data);
			}
		}
		else
		{
			// TODO: use: mysql_fetch_assoc | might be faster
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



	/* ******************************************** FETCH ******************************************** */

	public function fetchField($table, $field_name, $condition)
	{
	
	}
	

	public function fetchRowField($table, $field_name, $id)
	{
		return 0;
	}

	public function count($table, $condition)
	{
		$where	= $condition ? 'WHERE '.$condition : '';
		$query	= sprintf('SELECT COUNT(*) AS counter FROM `%s` %s', $table, $where);
		$data	= $this->select($query);

		return (isset($data[0]['counter'])) ? $data[0]['counter'] : 0;
	}
	
	public function countDistinct($table, $field, $condition)
	{
		$where	= $condition ? 'WHERE '.$condition : '';
		$query	= sprintf('SELECT COUNT(DISTINCT `%s`) AS counter FROM `%s` %s', $field, $table, $where);
		$data	= $this->select($query);

		return (isset($data[0]['counter'])) ? $data[0]['counter'] : 0;
	
	}
	
	
	public function rowExists($table, $id)
	{
		$count	= (is_numeric($id)) ? self::count($table, sprintf('`id` = %d', (int)$id)) : 0;
		
		if ($count > 1)
		{
			SysLog::sqlWarn('rowExists', 'More than one row exists', $query);
		}
		
		return (bool)$count;
	}


	
	/* ******************************************** UPDATE ******************************************** */


	/**
	 *
	 *	Update by condition
	 *
	 *	@param	string		$query				SQL Query
	 *	@param	mixed[]		$fields				Field value pair of fields and values
	 *	@param	string		$condition			SQL Condition
	 *	@param	boolean		$affected_row_ids	Return Ids of affected rows?
	 *
	 *	@return	integer[]|boolean				Array of Ids of affected rows or success of operation
	 */
	public function update($table, $fields, $condition, $affected_row_ids = false)
	{
	}
	public function updateRow($table, $id, $fields)
	{
		return 0;
	}
	

	public function incrementFields($table, $incFields, $updFields, $condition, $affected_row_ids = false)
	{
		// Prepare where clause
		$where		= $condition ? 'WHERE '.$this->_prepare($condition) : '';
		
		// Anonymous functions
		$fIncFields = function($field){
			return '`'.$field.'` = `'.$field.'` + 1';
		};
		$fUpdFields = function($field, $value){
			return '`'.$field.'` = '.$this->escape($value, true);
		};
		
		// Apply anonymous functions
		$incFields	= implode(',', array_map($fIncFields, array_values($incFields)));
		$updFields	= implode(',', array_map($fUpdFieldsm array_keys($updFields), array_values($updFields)));
		$fields		= $incFields ? $incFields.', '.$updFields : $updFields;
		
		// Build query
		$query		= sprintf("UPDATE `%s` SET %s %s", $table, $fields, $condition);


		$start	= microtime(true);
		$result	= mysql_query($query, self::$link);
		$time	= microtime(true) - $start;

		if (!$result)
		{
			SysLog::sqlError('incrementField', 'mysql_query failed', $query, array(mysql_errno(self::$link),mysql_error(self::$link), $time));
			return false;
		}

		SysLog::sqlAppendTime($time);
		SysLog::sqlInfo('incrementFields', implode(',', $incFields), $query, null, $time);
	
		// TODO: getColumnFields()
		return ($get_update_id) ? $this->getColumnFields($table, 'id', $where) : true;
	}
	
	
	
	/* ******************************************** INSERT ******************************************** */
	public function insert($table, $fields, $return_insert_id = true)
	{
		$names	= implode(',', array_map( create_function('$key', 'return "`".$key."`";'), array_keys($fields)));
		$values = implode(',', array_map( create_function('$val', 'return "\'".mysql_real_escape_string($val)."\'";'), array_values($fields)));

		$query	= sprintf('INSERT INTO `%s` (%s) VALUES(%s)', $table, $names, $values);

		$start	= microtime(true);
		$result	= mysql_query($query, self::$link);
		$time	= microtime(true) - $start;

		if (!$result)
		{
			\Sweany\SysLog::sqlError('insertRow', 'mysql_query failed', $query, array(mysql_errno(self::$link),mysql_error(self::$link), $time));
			return (-1);
		}
		\Sweany\SysLog::sqlAppendTime($time);
		\Sweany\SysLog::sqlInfo('insertRow', null, $query, null, $time);

		// return last insert id ?
		return ($return_insert_id) ? self::_getLastInsertId() : null;
	}

	
	
	/* ******************************************** DELETE ******************************************** */
	public function delete($table, $condition, $affected_row_ids = false)
	{
	}
	public function deleteRow($table, $id)
	{
	}

	
	
	/* ******************************************** HELPER ******************************************** */
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

		return $this->select($query, function($row, &$data){ $data = $row['pk']; });
	}



	/**********************************************************************************************************************************
	 *
	 * 	P R I V A T E   F U N C T I O N S
	 *
	 **********************************************************************************************************************************/

	/************************************************ PRIVATE SPECIAL MYSQL FUNCTIONS ****************************************************/
	private static function _getLastInsertId()
	{
		$result	= mysql_query('SELECT LAST_INSERT_ID() AS id');
		$row	= mysql_fetch_array($result, MYSQL_ASSOC);
		$id		= $row['id'];
		\Sweany\SysLog::sqlInfo('lastInsertId', $id, null, null);
		return $id;
	}

	
	
	/**
	 *	Prepares and escapes a statement
	 *
	 *	@param	mixed[]	$statement
	 *
	 *		example:
	 *		Array
	 *		(
	 *			[0]	=>	'`id` = :id AND `username` LIKE %:name%',
	 *			[1]	=>	Array
	 *				(
	 *					':id' 	=> $id,
	 *					':name'	=> $name
	 *				),
	 *		);
	 *
	 *	@return	string	escape safe string
	 */
	private function _prepare($statement = null)
	{
		$stmt	= (isset($statement[0]) && is_string($statement[0]) && strlen($statement[0]))	? $statement[0] : '';
		$vars	= (isset($statement[1])	&& is_array($statement[1])	&& count($statement[1]))	? $statement[1] : null;
		
		if ( !$stmt || !$vars )
		{
			return $stmt;
		}

		$fPrepare = function(&$value, $key) /*use ($this)*/ {
			if ($key[0]==':') {
				$value = $this->db->escape($value, true);
			}
		};
		array_walk($vars, $fPrepare);
		return str_replace(array_keys($vars), array_values($vars), $stmt);
	}

}
