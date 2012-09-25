<?php

class Table
{
	/* ************************************************************************************************************************** *
	 *
	 *	C L A S S   P R O P E R T I E S
	 *
	 * ************************************************************************************************************************** */



	/* ***************************************  T A B L E   D E F I N E S  *************************************** */

	/**
	 *	@param	string		Name of the sql table
	 */
	public $table;



	/**
	 *	@param	string		Name of the alias to use
	 */
	public $alias;



	/**
	 *	@param	string		Primary key
	 */
	public $primary_key		= 'id';



	/**
	 *	@param	string[]	Array of foreign keys
	 */
	public $foreign_keys	= array();



	/**
	 *
	 *	All available fields (and additional aliases) in the sql table.
	 *
	 *	@param	mixed[]	Fields
	 *	@format:
 	 *	$fields = Array
	 *	(
	 *      '<field_name>',
	 *		'<field_alias>' => '<field_name>',
	 *	);
	 */
	public $fields			= array();



	/**
	 *
	 *	Does This table have a modified field?
	 *	
	 *	If set, we will automatically add the propper
	 *	Date (depending on sqlDataType) during update method
	 *
	 *	@param mixed[]|string|null
	 *
	 *	@format
	 *	+	Array
	 *		(
	 *			'<field_name>'	=> '<sqlDataType>'
	 *		)
	 *	+	String (assumes field name to be 'modified'):
	 *		'<sqlDataType>'
	 *
	 *	Allowed sqlDataTypes
	 *		'datetime'		store via date('Y-m-d H:i:s', $timestamp);
	 *		'timestamp'		store via date('Y-m-d H:i:s', $timestamp);
	 *		'integer'		store via time();	# unix timestamp
	 */
	 protected $hasModified	= null;



	 /**
	 *
	 *	Does This table have a created field?
	 *	
	 *	If set, we will automatically add the propper
	 *	Date (depending on sqlDataType) during save method
	 *
	 *	@param mixed[]|string|null
	 *
	 *	@format
	 *	+	Array
	 *		(
	 *			'<field_name>'	=> '<sqlDataType>'
	 *		)
	 *	+	String (assumes field name to be 'created'):
	 *		'<sqlDataType>'
	 *
	 *	Allowed sqlDataTypes
	 *		'datetime'		store via date('Y-m-d H:i:s', $timestamp);
	 *		'timestamp'		store via date('Y-m-d H:i:s', $timestamp);
	 *		'integer'		store via time();	# unix timestamp
	 */
	protected $hasCreated	= null;




	/* ***************************************  Q U E R Y   D E F I N E S  *************************************** */

	/**
	 *
	 *	Array of subqueries for $this->table to fetch
	 *
	 *	@param mixed[]	Queries
	 *	@format:
	 *	$subQueries = Array
	 *	(
	 *		'<field_alias>'	=> 'SQL sub query',
	 *	);
	 */
	public $subQueries		= array();



	
	/*
	 *	Default order when selecting (can be overwritten)
	 *
	 *	@param mixed[]	Order
	 *	@format:
	 *	$order = Array
	 *	(
	 *		'<alias>.<field>|FUNCTION(<alias>.<field>)'	=> 'ASC|DESC',
	 *	);
	 *
	 *	Defaults to: array(<alias>.<primary_key> => 'ASC')
	 *
	 *
	 */
	public $order			= null;
	
	

	/* ***************************************  R E L A T I O N   D E F I N E S  *************************************** */

	/**
	 *
	 *	$hasOne		One-to-One Relation
	 *
	 *	@param mixed[]	= Array(
	 *		'<alias>' => array(							# Table alias of <table_name> (must match the alias name in the corresponding php class)
	 *			'table'			=> '<table_name>',		# Name of the sql table
	 *			'class'			=> '<class_name>',		# Defaults from <sql_table_name> to <SqlTableName>Table (underscore -> camelcase)
	 *			'plugin'		=> '<plugin_name>'|null,# Name of the plugin or (null or not set if it is not a plugin)
	 *			'primaryKey'	=> 'id',				# Primary key in other table (<table_name>) (defaults to: 'id')
	 *			'foreignKey'	=> '<foreign_key>',		# Foreign key in other table (<table_name>) (defaults to: 'fk_<$this->table>_id')
	 *			'conditions'	=> array(),				# Array of conditions
	 *			'fields'		=> array(),				# Array of fields to fetch
	 *			'subQueries'	=> array(),				# Array of subqueries to append
	 *			'order'			=> array(), 			# Array of order clauses on the given table
	 *			'dependent'		=> false,
	 *			'recursive'		=> false,				# true: also load the depending table with its relations | false: only load this relation
	 *			'hasCreated'	=> '<SQLDataType>' | array('<field_name>' => '<SQLDataType>'),		# If set, adds date-time value on insert (sql def field: 'created') or specify field name
	 *			'hasModified'	=> '<SQLDataType>' | array('<field_name>' => '<SQLDataType>'),		# If set, adds date-time value on update (sql def field: 'modified') or specify field name
	 *		),
	 *	);
	 */
	public $hasOne			= array();



	/**
	 *
	 *	$hasMany	One-to-Many Relation
	 *
	 *	@param mixed[]	= Array(
	 *		'<alias>'	=> array(						# Table alias of <table_name> (must match the alias name in the corresponding php class)
	 *			'table'			=> '<table_name>',		# Name of the sql table
	 *			'class'			=> '<class_name>',		# Defaults from <sql_table_name> to <SqlTableName>Table (underscore -> camelcase)
	 *			'plugin'		=> '<plugin_name>'|null,# Name of the plugin or (null or not set if it is not a plugin)
	 *			'primaryKey'	=> 'id',				# primary key in other table (<table_name>) (defaults to: 'id')
	 *			'foreignKey'	=> '<foreign_key>',		# Foreign key in other table (<table_name>) (defaults to: 'fk_'<$this->table>_id')
	 *			'conditions'	=> array(),				# Array of conditions
	 *			'fields'		=> array(),				# Array of fields to fetch
	 *			'subQueries'	=> array(),				# Array of subqueries to append
	 *			'order'			=> array(),				# Array of order clauses on the given table
	 *			'limit'			=> array(),				# Array of Limit clause
	 *			'dependent'		=> false,
	 *			'recursive'		=> false,				# true: also load the depending table with its relations | false: only load this relation
	 *			'hasCreated'	=> '<SQLDataType>' | array('<field_name>' => '<SQLDataType>'),		# If set, adds date-time value on insert (sql def field: 'created') or specify field name
	 *			'hasModified'	=> '<SQLDataType>' | array('<field_name>' => '<SQLDataType>'),		# If set, adds date-time value on update (sql def field: 'modified') or specify field name
     *   	),
	 *	);
	 */
	public $hasMany			= array();



	/**
	 *
	 *	$belongsTo	Many-to-One Relation
	 *
	 *	@param	Array
	 *	(
	 *		'<alias>'	=> array(						# Alias (must match the alias name in the corresponding php class)
	 *			'table'			=> '<table_name>',		# Name of the sql table
	 *			'class'			=> '<class_name>',		# Defaults from <sql_table_name> to <SqlTableName>Table (underscore -> camelcase)
	 *			'plugin'		=> '<plugin_name>'|null,# Name of the plugin or (null or not set if it is not a plugin)
	 *			'primaryKey'	=> 'id',				# primary key in other table (<table_name>)		(defaults to: 'id')
	 *			'foreignKey'	=> '<foreign_key>',		# Foreign key in current table (<$this->table>)	(defaults to: 'fk_<table_name>_id')
	 *			'conditions'	=> array(),				# Array of conditions
	 *			'fields'		=> array(),				# Array of fields to fetch
	 *			'subQueries'	=> array(),				# Array of subqueries to append
	 *			'order'			=> array(),				# Array of order clauses on the given table
	 *			'limit'			=> array(),				# Array of Limit clause
	 *			'dependent'		=> false,
	 *			'recursive'		=> false,				# true: also load the depending table with its relations | false: only load this relation
	 *			'hasCreated'	=> '<SQLDataType>' | array('<field_name>' => '<SQLDataType>'),		# If set, adds date-time value on insert (sql def field: 'created') or specify field name
	 *			'hasModified'	=> '<SQLDataType>' | array('<field_name>' => '<SQLDataType>'),		# If set, adds date-time value on update (sql def field: 'modified') or specify field name
     *   	),
	 *	);
	 */
	public $belongsTo		= array();



	// TODO:
	public $hasAndBelongsToMany	= array();




	/* ***************************************  C L A S S E S  *************************************** */

	/**
	 *	@param	class	Database Class
	 */
	protected $db;




	/* ************************************************************************************************************************** *
	 *
	 *	C L A S S   F U N C T I O N S
	 *
	 * ************************************************************************************************************************** */


	/**
	 *
	 *	Constructor
	 */
	public function __construct()
	{
		// TODO: Error check if all values have been set
		// Only activate during validation mode

		// TODO: in validation mode, check if all fields are set from this table!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		// TODO:!!!!
	
	
		// initialize order to primary
		if ( !$this->order )
		{
			$this->order = array($this->alias.'.'.$this->primary_key => 'ASC');
		}
	
		$this->db = \Sweany\Database::getInstance();
	}

	/**
	 *
	 *	Load One entity by id
	 *
	 *	@param	integer			$id			Id of the entity
	 *	@param	string[]|null	$fields		Array of fields or 'null' for all
	 *	@param	integer			$recursive	Level of recursions (0-2)
	 *	@return	mixed[]			$data		Returns single entity
	 */
	public function load($id, $fields = null, $recursive = 1)
	{
		$condition	= 'WHERE '.$this->alias.'.'.$this->primary_key.' = '.$id;
		$query		= $this->_buildQuery($fields, $condition);
		$data		= $this->_retrieveResults($query);

		return ( isset($data[0]) ) ? $data[0] : array();
	}

	/**
	 *
	 *	Load Many entities by array of ids
	 *
	 *	@param	integer			$id			Id of the entity
	 *	@param	string[]|null	$fields		Array of fields or 'null' for all
	 *	@param	mixed[]			$order		Array of order clauses (only applies to fields in this table)
	 *	@param	integer			$recursive	Level of recursions (0-2)
	 *	@return	mixed[]			$data		Returns all found entities
	 */
	public function loadMany($ids, $fields = null, $order = null, $recursive = 1)
	{
		$condition	= 'WHERE '.$this->alias.'.'.$this->primary_key.' IN ('.implode(',', $ids).')';
		$order		= ($order) ? $order : ($this->order ? $this->order : $this->alias.'.'.$this->primary_key);
		$query		= $this->_buildQuery($fields, $condition, $order);
		$data		= $this->_retrieveResults($query);

		return $data;
	}

	/**
	 *
	 *	The swiss army knife of Sweany db functionality
	 *
	 *	@param	string	$type	'all', 'count'
	 *
	 *
	 */
	public function find($type, $options = array())
	{
		$fields 	= isset($options['fields']) ? $options['fields'] : null;
		$condition	= isset($options['condition']) ? $options['condition'] : null;
		$order		= isset($options['order']) ? $options['order'] : $this->order;
		$limit		= isset($options['limit']) ? $options['limit'] : null;
		$query		= $this->_buildQuery($fields, $condition, $order, $limit);
		$data		= $this->_retrieveResults($query);

		return $data;
	}
	


	public function save($fields, $return_insert_id = false)
	{
		return $this->db->insert($this->table, $this->__appendCreatedFieldIfExist($fields), $return_insert_id);
	}

	public function update($id, $data)
	{}

	public function delete($id)
	{}
	




	/* ************************************************************************************************************************** *
	 *
	 *	P R I V A T E   C L A S S   F U N C T I O N S
	 *
	 * ************************************************************************************************************************** */

	/**
	 *
	 *	Build SQL Query according to defined relations.
	 *	(hasOne, hasMany, belongsTo)
	 *
	 *	@param	string[]|null	$fields		Array of fields to fetch | or all
	 *	@param	string			$condition	SQL Where clause
	 *	@param	mixed[]|null	$order
	 *	@param	string			$limi		Limit
	 *	@return	string			$query		SQL Query
	 */
	private function _buildQuery($fields = null, $condition = null, $order = null, $limit = null)
	{
		// Get Fields to be used
		$fields	= ( is_array($fields) && count($fields) ) ? $fields : $this->fields;
		
		$pk[]	= $this->alias.'.'.$this->primary_key.' AS __PRIMARY_KEY__';
		$fields	= array_merge($pk, $this->_getPreparedFields($this->alias, $fields));
		$fields	= array_merge($fields, $this->_getPreparedSubQueries($this->alias, $this->subQueries));
		$joins	= array();
		//$group	= ' GROUP BY '.$this->alias.'.'.$this->primary_key;
		$order	= (is_array($order)) ? $order : array();
		$limit	= ($limit) ? ' LIMIT '.$limit : null;

		// one-to-one
		foreach ( $this->hasOne as $alias => $properties )
		{
			$table	= $properties['table'];
			$fields	= array_merge($fields, $this->_getPreparedFields($alias, $properties['fields']));
			$fields	= array_merge($fields, $this->_getPreparedSubQueries($alias, $properties['subQueries']));
			
			$me		= $this->alias.'.'.$this->primary_key;
			$other	= $alias.'.'.$properties['foreignKey'];

			$joins[]= 'LEFT JOIN '.$table.' AS '.$alias.' ON ('.$me.'='.$other.')';
		}

		// one-to-many
		foreach ( $this->hasMany as $alias => $properties )
		{
			$table	= $properties['table'];
			$fields	= array_merge($fields, $this->_getPreparedFields($alias, $properties['fields']));
			$fields	= array_merge($fields, $this->_getPreparedSubQueries($alias, $properties['subQueries']));
			
			$me		= $this->alias.'.'.$this->primary_key;
			$other	= $alias.'.'.$properties['foreignKey'];

			$joins[]= 'LEFT JOIN '.$table.' AS '.$alias.' ON ('.$me.'='.$other.')';
			$order	= array_merge($order, $this->_buildAliasedOrder($properties['order'], $alias, $properties['primaryKey']));
		}

		// many-to-one
		foreach ( $this->belongsTo as $alias => $properties )
		{
			$table	= $properties['table'];
			$fields	= array_merge($fields, $this->_getPreparedFields($alias, $properties['fields']));
			$fields	= array_merge($fields, $this->_getPreparedSubQueries($alias, $properties['subQueries']));
			
			$me		= $this->alias . '.' . $properties['foreignKey'];
			$other	= $alias . '.' . $properties['primaryKey'];

			$joins[]= 'JOIN '.$table.' AS '.$alias.' ON ('.$me.'='.$other.')';
			$order	= array_merge($order, $this->_buildAliasedOrder($properties['order'], $alias, $properties['primaryKey']));
		}

		$select	= 'SELECT '.implode(',', $fields);
		$from	= 'FROM '.$this->table.' AS '.$this->alias;
		$join	= implode('', $joins);
		$order	= $this->__orderToString($order);
		
		// TODO: group by: use where id IN (select ids from bla CONDITION)
		return $select.' '.$from.' '.$join.' '.$condition./*$group*/$order.$limit.';';
	}


	/**
	 *
	 *	Retrieve processed database result array
	 *
	 *	@param	string	$query		SQL Query
	 *	@return	mixed[]	Result array
	 *
	 *	Note:
	 *	This function created a lambda callback and parses
	 *	it to the database select function in order to build
	 *	the result array according to the set entity relations
	 *	(hasOne, hasMany, belongsTo...)
	 */
	private function _retrieveResults($query)
	{
		$count	= -1;
		$many	= 0;
		$pk_val	= null;
		
		$lambda = function ($row, &$data) use (&$count, &$many, &$pk_val)
		{
			// sub-data belonging to the entity
			if ( $row['__PRIMARY_KEY__'] === $pk_val )
			{
				foreach ($row as $underscoredField => $value)
				{
					foreach ($this->hasMany as $alias => $property)
					{
						if ( strpos($underscoredField, '__'.$alias.'__') !== false )
						{
							$field = str_replace('__'.$alias.'__', '', $underscoredField);
							$data[$count][$alias][$many][$field] = $value;
						}
					}
				}
				$many++;
			}
			// new entity starts here
			else
			{
				$many = 0;
				$count++;
				$pk_val = $row['__PRIMARY_KEY__'];
				
				foreach ($row as $underscoredField => $value)
				{
					// retrieve this table's data
					if ( strpos($underscoredField, '__'.$this->alias.'__') !== false )
					{
						$field = str_replace('__'.$this->alias.'__', '', $underscoredField);
						$data[$count][$this->alias][$field] = $value;
					}
					
					// retrieve hasOne data
					foreach ($this->hasOne as $alias => $property)
					{
						if ( strpos($underscoredField, '__'.$alias.'__') !== false )
						{
							$field = str_replace('__'.$alias.'__', '', $underscoredField);
							$data[$count][$alias][$field] = $value;
						}
					}
					// retrieve belongsTo data
					foreach ($this->belongsTo as $alias => $property)
					{
						if ( strpos($underscoredField, '__'.$alias.'__') !== false )
						{
							$field = str_replace('__'.$alias.'__', '', $underscoredField);
							$data[$count][$alias][$field] = $value;
						}
					}
					foreach ($this->hasMany as $alias => $property)
					{
						if ( strpos($underscoredField, '__'.$alias.'__') !== false )
						{
							$field = str_replace('__'.$alias.'__', '', $underscoredField);
							if ( !is_null($value) )
							{
								$data[$count][$alias][$many][$field] = $value;
							}
							else
							{
								$data[$count][$alias] = array();
							}
						}
					}
				}
				$many++;
			}
		};
		return $this->db->select($query, $lambda);
	}

	

	/**
	 *
	 *	Build order array with table aliases
	 *
	 *	@param	mixed[]		$order
	 *	@param	string		$tbl_alias		Table alias name
	 *	@param	string		$tbl_pl			Table primary key
	 *	@param	string		$def_direction	Default order by direction
	 *	@return	mixed[]		order
	 */
	private function _buildAliasedOrder($order, $tbl_alias, $tbl_pk, $def_direction = 'ASC')
	{
		$newOrder = array();

		if ( is_array($order) && count($order) )
		{
			foreach ($order as $field => $direction)
			{
				$newOrder[$tbl_alias.'.'.$field] = $direction;
			}
		}
		else
		{
			$newOrder[$tbl_alias.'.'.$tbl_pk] = $def_direction;
		}
		return $newOrder;
	}

	
	
	/**
	 *
	 *  Retrieves prepares field-array to be used in a SQL query.
	 *
	 *  @param	string		$tbl_alias		Table alias name
	 *  @param	string[]	$fields			Array of fields
	 *	@return string[]	$preparedFields	Prepared Fields
	 *
	 *
	 *	@format:
	 *
	 *	$fields = Array
	 *	(
	 *      '<field_name>',
	 *		'<field_alias>' => '<field_name>',
	 *	);
	 *
	 *
	 *	$preparedFields = Array
	 *	(
	 *		'<table_alias>.<field_name> AS __<table_alias>__<field_name>',
	 *		'<table_alias>.<field_name> AS __<table_alias>__<field_alias>',
	 *	);
	 */
	private function _getPreparedFields($tbl_alias, $fields = array())
	{
		$preparedFields = array();
		
		// TODO: [Performace]: Change to array_map function
		foreach ($fields as $alias => $field)
		{
			// numerical array (no alias)		// __<tbl_alias>__<field_name>
			if ( is_integer($alias) )
			{
				$preparedFields[] = $tbl_alias.'.'.$field.' AS __'.$tbl_alias.'__'.$field ;
			}
			// associative array (has alias)	// __<tbl_alias>__<field_alias>
			else
			{
				$preparedFields[] = $tbl_alias.'.'.$field.' AS __'.$tbl_alias.'__'.$alias;
			}
		}
		return $preparedFields;
	}

	private function _getPreparedSubQueries($tbl_alias, $subQueries = array())
	{
		$preparedSubQueries = array();
		
		// TODO: [Performace]: Change to array_map function
		foreach ($subQueries as $alias => $query)
		{
			$preparedSubQueries[] = '('.$query.') AS __'.$tbl_alias.'__'.$alias;
		}
		return $preparedSubQueries;
	}



	/* ************************************************************************************************************************** *
	 *
	 *	P R I V A T E   C L A S S   H E L P E R   F U N C T I O N S
	 *
	 * ************************************************************************************************************************** */


	/**
	 *
	 *	Stringify the order clause
	 *
	 *	@param	mixed[]		$order
	 *	@return	string		order clause
	 */
	private function __orderToString($order = array())
	{
		return (is_array($order) && count($order)) ? ' ORDER BY '.implode(', ', array_map( create_function('$key, $val', 'return "$key ".$val;'), array_keys($order), array_values($order))) : '';
	}
	
	
	
	private function __appendModifiedFieldIfExist($fields)
	{
		if ( $this->hasModified )
		{
			$field_name		= key($this->hasModified);
			$sqlDataType	= $this->hasModified[$field_name];
			
			switch ($sqlDataType)
			{
				case 'datetime':	return array_merge($fields, array($field_name	=> $this->db->getNowDateTime()));
				case 'timestamp':	return array_merge($fields, array($field_name	=> $this->db->getNowTimeStamp()));
				case 'integer':		return array_merge($fields, array($field_name	=> $this->db->getNowUnixTimeStamp()));
				default:			return $fields;
			}
		}
		else
		{
			return $fields;
		}
	}
	private function __appendCreatedFieldIfExist($fields)
	{
		if ( $this->hasCreated )
		{
			$field_name		= key($this->hasCreated);
			$sqlDataType	= $this->hasCreated[$field_name];
			
			switch ($sqlDataType)
			{
				case 'datetime':	return array_merge($fields, array($field_name	=> $this->db->getNowDateTime()));
				case 'timestamp':	return array_merge($fields, array($field_name	=> $this->db->getNowTimeStamp()));
				case 'integer':		return array_merge($fields, array($field_name	=> $this->db->getNowUnixTimeStamp()));
				default:			return $fields;
			}
		}
		else
		{
			return $fields;
		}
	}
}
