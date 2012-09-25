<?php
namespace Sweany;
class mysql extends aBootTemplate implements iDataBase
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
	public function escape($string)
	{
		return mysql_real_escape_string($string, self::$link);
	}



	/**
	 *
	 *	@param	string		$query
	 *	@param	function	$callback = function ($row, &$data){}
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
	public function selectNumRows($query)
	{
		return 0;
	}
	/* ******************************************** FETCH ******************************************** */
	public function fetchRow($table, $id)
	{
		return array();
	}
	public function fetchRows($table, $ids)
	{
		return array();
	}

	public function fetchField($table, $field_name, $condition)
	{
		return 0;
	}
	public function fetchRowField($table, $field_name, $id)
	{
		return 0;
	}

	public function count($table, $condition)
	{
		return 0;
	}

	public static function idExists($table, $id)
	{
		return false;
	}
	public static function fieldExists($table, $field, $value)
	{
		return false;
	}

	
	
	/* ******************************************** UPDATE ******************************************** */
	public function update($table, $fields, $condition)
	{
		return 0;
	}
	public static function incrementField($table, $field, $condition, $return_ids = false)
	{
		return 0;
	}
	public static function incrementFields($table, $fields, $condition, $return_ids = false)
	{
		return 0;
	}
	
	
	
	/* ******************************************** INSERT ******************************************** */
	public function insert($table, $fields, $return_insert_id = false)
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
	public function delete($table, $condition)
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
		return $row['id'];
	}

}
