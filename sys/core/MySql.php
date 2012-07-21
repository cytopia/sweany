<?PHP
/*
 * Class MySQL
 */

class MySql extends CoreTemplate
{

	/* ******************************************** VARIABLES ********************************************/

	private	static $link		= null;
	private static $query		= null;

	public static $srvTime		= null;
	public static $srvTimeOff	= null;



	/*********************************************** OVERRIDE INITIALIZE ***********************************************/

	public static function initialize()
	{
		$host		= $GLOBALS['SQL_HOST'];
		$db			= $GLOBALS['SQL_DB'];
		$user		= $GLOBALS['SQL_USER'];
		$pass		= $GLOBALS['SQL_PASS'];

		self::$link	= @mysql_connect($host, $user, $pass);

		if (!self::$link)
		{
			self::$error = 'Connect, Could not connect to '.$host;
			return false;
		}

		if (!@mysql_select_db($db, self::$link))
		{
			self::$error = 'Database, '.'Coult not select '.$db;
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





	/**********************************************************************************************************************************
	*
	* 	GENERIC UNSAFE SELECT FUNCTIONS (use carefully and always escape)
	*
	**********************************************************************************************************************************/

	/*
	 * Generic Select
	 */
	public static function select($query)
	{
		self::$query = $query;

		$start	= getmicrotime();
		$result = mysql_query($query, self::$link);
		$time	= self::_getQueryTime($start, getmicrotime());

		$data	= array();

		if (!$result)
		{
			Log::setQueryError('select', self::_formatError($query, $time));
			return (0);
		}

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			$data[] = $row;

		if (!mysql_free_result($result))
			Log::setQueryError('Cannot Free Result', self::_formatError($query, $time), $data);

		Log::setQueryTime($time);
		Log::setQueryInfo('select', self::_formatError($query, $time), $data);

		return ($data);
	}

	/**
	 * Counts number of rows in a select query
	 * DOES NOT fetch the data, only does a quick count
	 */
	public static function selectNumRows($query)
	{
		self::$query = $query;

		$start	= getmicrotime();
		$result = mysql_query($query, self::$link);
		$time	= self::_getQueryTime($start, getmicrotime());

		if (!$result)
		{
			Log::setQueryError('numRows', self::_formatError($query, $time));
			return (0);
		}
		$num	= mysql_numrows($result);
		$time	= self::_getQueryTime($start, getmicrotime());	// measure again for 2nd query

		if (!mysql_free_result($result))
			Log::setQueryError('Cannot Free Result', self::_formatError($query, $time));

		Log::setQueryTime($time);
		Log::setQueryInfo('numRows', self::_formatError($query, $time));

		return ($num);
	}




	/**********************************************************************************************************************************
	*
	* 	SAFE MYSQL FUNCTIONS
	*
	**********************************************************************************************************************************/

	/*
	 * function fetch()
	 *
	 * assoc array fields:	array('*') || array('field1', 'field2')
	 * num array   aliases:	array('id' => 'myalias', 'field2' => 'alias2')
	 */
	public static function fetch($table, $fields = array(), $where = NULL, $having = NULL, $order = array(), $limit_num = NULL, $limit_start = NULL)
	{
		$fields	= self::_getFields($fields);	// if NULL return *
		$where	= self::_getWhere($where);
		$having	= self::_getHaving($having);
		$order	= self::_getOrderBy($order);
		$limit	= self::_getLimit($limit_num, $limit_start);

		$query	= sprintf('SELECT %s FROM `%s` %s %s %s %s', $fields, $table, $where, $having, $order, $limit);
		return self::select($query);
	}

	public static function fetchField($table, $field, $where)
	{
		$where	= self::_getWhere($where);
		$query	= sprintf('SELECT `%s` FROM `%s` %s', $field, $table, $where);
		$data	= self::select($query);

		if ( !isset($data[0][$field]) )
		{
			Log::setQueryWarning('fetchField', 'returning empty field: '.self::$query);
			return NULL;
		}
		if ( sizeof($data) > 1 )
		{
			Log::setQueryWarning('fetchField', 'result hash more than one row: '.self::$query, $data);
		}
		return $data[0][$field];
	}

	public static function fetchColumnFields($table, $field, $where = NULL, $having = NULL, $order = array(), $limit_num = NULL, $limit_start = NULL)
	{
		$idArr	= self::fetch($table, array($field => $field), $where, $having, $order, $limit_num, $limit_start);

		return array_map( create_function('$arr', 'return (current($arr));'), array_values($idArr));
	}

	public static function fetchFieldById($table, $field, $id)
	{
		return self::fetchField($table, $field, sprintf("id = %d", (int)$id));
	}

	public static function fetchByIds($table, $ids = array(), $fields = array(), $order = array(), $limit_num = NULL, $limit_start = NULL)
	{
		$ids	= implode(',', $ids);
		$fields	= self::_getFields($fields);	// if NULL return *
		$order	= self::_getOrderBy($order);
		$limit	= self::_getLimit($limit_num, $limit_start);

		$query	= sprintf('SELECT %s FROM `%s` WHERE id IN (%s) %s %s', $fields, $table, $ids, $order, $limit);
		return self::select($query);
	}

	public static function fetchRowById($table, $id, $fields = array())
	{
		$fields	= self::_getFields($fields);

		$query	= sprintf('SELECT %s FROM `%s` WHERE `id` = %d', $fields, $table, (int)$id);
		$data	= self::select($query);

		if ( !isset($data[0]) )
		{
			Log::setQueryWarning('fetchRowById', 'returning empty array: '.self::$query);
			return array();
		}
		if ( sizeof($data) > 1 )
		{
			Log::setQueryWarning('fetchRowById', 'result hash more than one row: '.self::$query, $data);
		}

		return $data[0];
	}

	public static function fetchByField($table, $field, $value, $fields = array())
	{
		$fields	= self::_getFields($fields);

		$query = sprintf('SELECT %s FROM `%s` WHERE `%s` = "%s"', $fields, $table, $field, mysql_real_escape_string($value));
		return self::select($query);
	}


	/************************************************** INSERT Functions **************************************************/

	public static function insertRow($table, $field_array = array())
	{
		$fields = implode(',', array_map( create_function('$key', 'return "`".$key."`";'), array_keys($field_array)));
		$values = implode(',', array_map( create_function('$val', 'return "\'".mysql_real_escape_string($val)."\'";'), array_values($field_array)));

		$query	= sprintf('INSERT INTO `%s` (%s) VALUES(%s)', $table, $fields, $values);

		$start	= getmicrotime();
		$result	= mysql_query($query, self::$link);
		$time	= self::_getQueryTime($start, getmicrotime());

		if (!$result)
		{
			Log::setQueryError('insertRow', self::_formatError($query, $time));
			return (0);
		}
		Log::setQueryTime($time);
		Log::setQueryInfo('insertRow', self::_formatError($query, $time), NULL);

		// return last insert id
		return (self::_getLastInsertId());
	}



	/************************************************** UPDATE Functions **************************************************/

	public static function update($table, $field_array = array(), $where)
	{
		$fields	= implode(',', array_map( create_function('$key, $val', 'return "`".$key."`=\'".mysql_real_escape_string($val)."\'";'), array_keys($field_array), array_values($field_array)));
		$where	= self::_getWhere($where);

		$query	= sprintf('UPDATE `%s` SET %s %s', $table, $fields, $where);

		$start	= getmicrotime();
		$result	= mysql_query($query, self::$link);
		$time	= self::_getQueryTime($start, getmicrotime());

		if (!$result)
		{
			Log::setQueryError('updateRow', self::_formatError($query, $time));
			return (0);
		}

		Log::setQueryTime($time);
		Log::setQueryInfo('updateRow', self::_formatError($query, $time));

		return (1);
	}

	public static function updateRow($table, $field_array = array(), $id)
	{
		return self::update($table, $field_array, sprintf('`id` = %d', (int)$id));
	}

	public static function incrementField($table, $field, $where, $get_update_id = null, $other_fields = array())
	{
		$condition	= self::_getWhere($where);
		// inject other fields to update (such as modified timestamp)
		$fields		= implode(',', array_map( create_function('$key, $val', 'return "`".$key."`=\'".mysql_real_escape_string($val)."\'";'), array_keys($other_fields), array_values($other_fields)));
		$fields		= strlen($fields) ? ', '.$fields : '';
		$query		= sprintf("UPDATE `%s` SET `%s` = `%s` + 1 %s %s", $table, $field, $field, $fields, $condition);

		$start	= getmicrotime();
		$result	= mysql_query($query, self::$link);
		$time	= self::_getQueryTime($start, getmicrotime());

		if (!$result)
		{
			Log::setQueryError('updateRow', self::_formatError($query, $time));
			return (0);
		}

		Log::setQueryTime($time);
		Log::setQueryInfo('updateRow', self::_formatError($query, $time));

		return ($get_update_id) ? self::fetchField($table, 'id', $where) : 1;
	}



	/************************************************** DELETE Functions **************************************************/

	public static function delete($table, $where)
	{
		$condition	= self::_getWhere($where);
		$query		= sprintf("DELETE FROM `%s` %s", $table, $condition);

		$start	= getmicrotime();
		$result	= mysql_query($query, self::$link);
		$time	= self::_getQueryTime($start, getmicrotime());

		if (!$result)
		{
			Log::setQueryError('delete', self::_formatError($query, $time));
			return (0);
		}

		Log::setQueryTime($time);
		Log::setQueryInfo('delete', self::_formatError($query, $time), null);

		return (1);
	}

	public static function deleteRow($table, $id)
	{
		return self::delete($table, sprintf('`id` = %d', (int)$id));
	}


	/************************************************** CHECK/EXIST Functions **************************************************/

	public static function count($table, $where = NULL)
	{
		$where	= self::_getWhere($where);
		$query	= sprintf('SELECT COUNT(*) AS counter FROM `%s` %s', $table, $where);
		$data	= self::select($query);

		return (isset($data[0]['counter'])) ? $data[0]['counter'] : 0;
	}

	public static function existField($table, $field, $value)
	{
		return self::count($table, sprintf("`%s` = '%s'", $field, mysql_real_escape_string($value)));
	}

	public static function existId($table, $id)
	{
		return (is_numeric($id)) ? self::count($table, sprintf('`id` = %d', (int)$id)) : FALSE;
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
	public static function _getDate()
	{
		$date = self::select("SELECT CURDATE() AS d;");
		return $date[0]['d'];
	}
	public static function _getTime()
	{
		$date = self::select("SELECT CURTIME() AS t;");
		return $date[0]['t'];
	}
	public static function _getTimeHour()
	{
		$hour = self::select("SELECT HOUR(NOW()) AS h");
		return $hour[0]['h'];
	}
	public static function _getTimeMin()
	{
		$min = self::select("SELECT MINUTE(NOW()) AS m");
		return $min[0]['m'];
	}

	public static function _getNow()
	{
		$date = self::select("SELECT NOW() AS n;");
		return $date[0]['n'];
	}
	public static function _getGlobalTimeZone()
	{
		$zone = self::select("SELECT @@global.time_zone AS g");
		return $zone[0]['g'];
	}
	public static function _getSessionTimeZone()
	{
		$zone = self::select("SELECT @@session.time_zone AS s");
		return $zone[0]['s'];
	}



	/**************************************************  M Y S Q L   S E R V E R   F U N C T I O N S  **************************************************/

	// we assume that php has the correct time
	private static function _setServerTimeZone()
	{
		$mysql	= self::_getTime();
		$php	= date('H:i',time());
		$offset	= get_timezone_difference($php,$mysql);

		self::$srvTime		= self::_getNow();
		self::$srvTimeOff	= $offset;

		return self::_setServerFlags(array('time_zone' => $offset));
		//return self::_setServerFlags(array('time_zone' => date("P", time())));
	}

	private static function _setServerFlags($flags = array())
	{
		$flags	= implode(',', array_map( create_function('$key, $val', 'return "`".$key."`=\'".mysql_real_escape_string($val)."\'";'), array_keys($flags), array_values($flags)));

		$query	= sprintf('SET %s', $flags);

		$start	= getmicrotime();
		$result	= mysql_query($query, self::$link);
		$time	= self::_getQueryTime($start, getmicrotime());

		if (!$result)
		{
			Log::setQueryError('set Server flags', self::_formatError($query, $time));
			return (0);
		}

		Log::setQueryTime($time);
		Log::setQueryInfo('SET flags', self::_formatError($query, $time));

		return true;
	}



	/**************************************************  P R I V A T E   B U I L D   F U N C T I O N S  **************************************************/

	// BUILD Fields to include in SELECT query
	private static function _getFields($fields = array())
	{
		return (sizeof($fields)) ? implode(',', array_map( create_function('$key, $val', 'return " ".$key." AS ".$val;'), array_values($fields), array_keys($fields))) : '*';
	}
	// BUILD WHERE Statement
		private static function _getWhere($where = NULL)
	{
		return ($where) ? sprintf('WHERE %s', $where) : '';
	}
	// BUILD HAVING Statement
	private static function _getHaving($having = NULL)
	{
		return ($having) ? sprintf('HAVING %s', $having) : '';
	}
	// BUILD ORDER BY Statement
	private static function _getOrderBy($order = NULL)
	{
		return (sizeof($order)>0) ? 'ORDER BY '.implode(', ', array_map( create_function('$key, $val', 'return "$key ".$val;'), array_keys($order), array_values($order))) : '';
	}
	// BUILD LIMIT Statement
	private static function _getLimit($limit_num = NULL, $limit_start =NULL)
	{
		if ( is_numeric($limit_num) && $limit_num > 0 )
		{
			if ( is_numeric($limit_start) && $limit_start > 0 )
				return 'LIMIT '.$limit_start.','.$limit_num;
			else
				return 'LIMIT '.$limit_num;
		}
		return '';
	}

	/************************************************** Helper Log Functions **************************************************/

	private static function _formatError($query, $time=NULL)
	{
		if (mysql_errno(self::$link))
			$str = '<font color="red"><b>'.mysql_errno(self::$link).'</b>: '.mysql_error(self::$link).'</font><br><font color="blue">'.$query.'</font><br/>';
		else
			$str = "<font face='courier' color='blue'>".$query."</font><br/>";

		if ($time)
			$str .= '<font color="green">Query took <b>'.$time.'</b> seconds</font>';


		return $str;
	}
	private static function _getQueryTime($start, $end)
	{
		return sprintf('%f', round($end-$start, 10));
	}
}

?>