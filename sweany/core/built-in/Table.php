<?php
define('TREE_SEP', '__.__');		// tree seperator
define('MANY_IND', '__s__');		// many indicator
define('PRIM_KEY', '__PRIM_KEY__');	// primary indicator to append at each trable
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
	 *	@param	string		Primary key of this table
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


	/**
	 *
	 *	Default condition
	 *
	 *	@param	string	$condition
	 *
	 *	For example:
	 *		$condition = '<Alias>.is_deleted = 0';
	 */
	public $condition		= null;

	/* ***************************************  R E L A T I O N   D E F I N E S  *************************************** */

	/**
	 *
	 *	$hasOne		One-to-One Relation
	 *
	 *	@param mixed[]	= Array(
	 *		'<alias>' => array(							# Table alias of <table_name> (must match the alias name in the corresponding php class)
	 *			'table'			=> '<table_name>',		# Name of the sql table
	 *			'class'			=> '<class_name>',		# Defaults from <sql_table_name> to <SqlTableName>Table (underscore -> camelcase)
	 *			'core'			=> false				# True if this table file is in sweany/core/built-in/tables
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
	 *			'core'			=> false				# True if this table file is in sweany/core/built-in/tables
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
	 *			'core'			=> false				# True if this table file is in sweany/core/built-in/tables
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
	 *	E N T I T Y   C L A S S   F U N C T I O N S
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
	 *	@param	integer			$recursive	Level of recursions (0-3)
	 *		0: flat - no relations, only this table
	 *		1: with relations (hasOne, hasMany, belongsTo, hasAndBelongsToMany)
	 *		2: with relations and follow recursion specified by relation
	 *		3: with relations and force recursion on all relations
	 *
	 *	@return	mixed[]			$data		Returns single entity
	 */
	public function load($id, $fields = null, $recursive = 1)
	{
		$data = $this->loadMany(array($id), $fields, null, $recursive);
		return ( isset($data[0]) ) ? $data[0] : array();
	}


	/**
	 *
	 *	Load Many entities by an array of ids
	 *
	 *	@param	integer			$id			Id of the entity
	 *	@param	string[]|null	$fields		Array of fields or 'null' for all
	 *	@param	mixed[]			$order		Array of order clauses (only applies to fields in this table)
	 *	@param	integer			$recursive	Level of recursions (0-3)
	 *		0: flat - no relations, only this table
	 *		1: with relations (hasOne, hasMany, belongsTo, hasAndBelongsToMany)
	 *		2: with relations and follow recursion specified by relation
	 *		3: with relations and force recursion on all relations
	 *
	 *	@return	mixed[]			$data		Returns all found entities
	 */
	public function loadMany($ids, $fields = null, $order = null, $recursive = 1)
	{
		$condition	= $this->alias.'.'.$this->primary_key.' IN ('.implode(',', $ids).')';
		$order		= ($order) ? $order : ($this->order ? $this->order : $this->alias.'.'.$this->primary_key);
		$query		= $this->buildQuery($fields, $condition, $order, null, $recursive);
		$data		= $this->retrieveResults($query);
		return $data;
	}


	/**
	 *
	 *	The swiss army knife of Sweany db functionality
	 *
	 *	@param	string	$type		Type of Operation
	 *		all		Return all results
	 *		first	Return first result
	 *		last	Return last result
	 *		count	Count results only
	 *
	 *	@param	mixed[]	$options
	 *
	 *		fields	 		Array of fields to fetch
	 *			'fields' => array('field_name', 'alias' => 'field_name')
	 *			[DEFAULT]			$this->fields
	 *			[NOTE]				Useless in 'count' operation
	 *
	 *		condition		Condition to append
	 *			'condition' => array('Alias.field > 5 AND Alias.field <10 ')
	 *			[DEFAULT]			$this->condition
	 *			[DEFAULT OVERRIDE] 'condition' => null
	 *
	 *		order			Order by
	 *			'order'	=> array('Alias.field' => 'DESC', 'Alias.field2' => 'ASC')
	 *			'order'	=> array('RAND()')
	 *			'order' => array('MAX(Alias.field1, Alias.field2)', 'Alias.field1' => 'ASC')
	 *			[DEFAULT]:			$this->order
	 *			[DEFAULT OVERRIDE]: 'order' => null
	 *			[NOTE]				Useless in 'count' operation
	 *
	 *		limit			Limit results		# (limit and range are mutually exclusive - limit has priority over range)
	 *			'limit' => 5
	 *			[NOTE]				Damn useless in 'count' operation
	 *
	 *		range			Limited rang		# (limit and range are mutually exclusive - limit has priority over range)
	 *			'range'	=> array(0,5)			# first 5 results
	 *			'range'	=> array(5,3)			# 6th ,7th and 8th result
	 *			[NOTE]				Damn useless in 'count' operation
	 *
	 *		recursive	=> 0-3
	 *			'recursive'	=> 0				# Flat - no relations, only this table
	 *			'recursive'	=> 1				# With relations (hasOne, hasMany, belongsTo, hasAndBelongsToMany)
	 *			'recursive'	=> 2				# With relations and follow recursion specified by relation
	 *			'recursive'	=> 3				# With relations and force recursion on all relations
	 *			[DEFAULT] 1
	 *			[NOTE]				Useless in 'count' operation
	 *
	 *
	 *	@return	mixed[]|integer		Array of results or integer (if $type == 'count')
	 *
	 */
	public function find($type = 'all', $options = array())
	{
		// Get Options;
		$fields 	= isset($options['fields'])				? $options['fields']	: $this->fields;
		$condition	= isset($options['condition'])			? $options['condition'] : $this->condition;
		$order		= isset($options['order'])				? $options['order']		: $this->order;
		$limit		= isset($options['limit'])				? $options['limit']		: null;	// mutually exclusive $limit > $range
		$range		= !$limit && isset($options['range'])	? $options['range']		: null;	// mutually exclusive $limit > $range
		$recursive	= isset($options['recursive'])			? $options['recursive']	: 1;

		switch ($type)
		{
			case 'all':
				break;
			case 'first':
				break;
			case 'last':
				break;
			case 'count':
				break;
			default: /* 'all' */
				break;
		}

		// Build Query
		$query		= $this->buildQuery($fields, $condition, $order, $limit);

		// Retrieve Results
		$data		= $this->retrieveResults($query);

		return $data;
	}


	/**
	 *	Save entity (by id)
	 *
	 *	@param	mixed[]	$fields			Array of field-value pairs
	 *		Array = (
	 *			'<field1>	=> '<value1>',
	 *			'<field2>	=> '<value2>',
	 *		);
	 *
	 *	@param	integer	$return			(0-2) Whether or not to return anything
	 *		0:	return boolean	Success
	 *		1:	return integer	Last insert id
	 *		2:	return mixed[]	Updated row
	 *
	 *	@return	boolean|integer|mixed[]	Depending on $return param
	 */
	public function save($fields, $return = 0)
	{
		$fields = $this->_appendCreatedFieldIfExist($fields);
		$ret	= $this->db->insert($this->table, $fields, (($return) ? true : false));

		switch ($return)
		{
			// return non-recursive row
			case 2:
				return $this->loadOne($ret, null, 0);

			// [DEFAULT] return success of operation or last insert id
			default:
				return $ret;
		}
	}

	/**
	 *	Update entity (by id)
	 *
	 *	@param	integer	$id			Id of the row/entity
	 *	@param	mixed[]	$fields		Array of field-value pairs
	 *		Array = (
	 *			'<field1>	=> '<value1>',
	 *			'<field2>	=> '<value2>',
	 *		);
	 *
	 *	@param	integer	$return		(0-2) Whether or not to return anything
	 *		0:	return boolean	Success
	 *		1:	return integer	Last insert id
	 *		2:	return mixed[]	Updated row
	 *
	 *	@return	boolean|integer|mixed[]
	 */
	public function update($id, $fields, $return = 0)
	{
		$success	= $this->db->updateRow($this->table, $id, $fields);
		
		switch ($return)
		{
			case 1:		return $id;
			case 2:		return $this->loadOne($id, null, 0);
			default:	return $success;
		}
	}


	/**
	 *	Delete entity (by id)
	 *
	 *	@param	integer	$id			Id of the row/entity
	 *	@return	boolean				Success of operation
	 */
	public function delete($id)
	{
		return $this->db->deleteRow($this->table, $id);
	}




	/* ************************************************************************************************************************** *
	*
	*	P R I V A T E   C L A S S   F U N C T I O N S
	*
	* ************************************************************************************************************************** */

	private function buildQuery($fields = null, $condition = null, $order = null, $limit = null, $recursive = 1)
	{
		$pk[]	= $this->alias.'.'.$this->primary_key.' AS '.PRIM_KEY;

		// Get Fields to be used
		$fields	= ( is_array($fields) && count($fields) ) ? $fields : $this->fields;
		$fields	= array_merge($pk, $this->_buildAliasedFields($this->alias, $this->alias, $fields));
		
		// Append local Subqueries
		$fields = array_merge($fields, $this->_buildAliasedSubQueries($this->alias, $this->alias, $this->subQueries));

		// Will be filled on the run (depending on the other relations)
		$joins	= array();

		$condition	= $this->_buildMainQueryLimitCondition($condition, $limit);	// string
		$order		= $this->_buildMainQueryOrder($order);	// array

		if ( $recursive > 0)
		{
			// Add hasOne (one-to-one)
			$this->_buildHasOneQuery($fields, $joins, $recursive);

			// Add hasMany (one-to-many)
			$this->_buildHasManyQuery($fields, $joins, $order, $recursive);

			// Add belongsTo (many-to-one)
			$this->_buildBelongsToQuery($fields, $joins, $order, $recursive);
		}

		// Build Query
		$select	= 'SELECT '.implode(',', $fields);
		$from	= 'FROM '.$this->table.' AS '.$this->alias;
		$join	= implode('', $joins);
		$order	= $this->__orderToString($order);

		$query	= $select.' '.$from.' '.$join.' '.$condition.$order.';';
		return $query;
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
	private function retrieveResults($query)
	{
		$count	= -1;
		$pk_val	= null;
		$stack	= array();

		$lambda = function ($row, &$data) use (&$count, &$pk_val, &$stack)
		{
			if ($pk_val != $row[PRIM_KEY] )
			{
				$pk_val = $row[PRIM_KEY];
				$count++;
			}

			foreach ($row as $field => $value)
			{
				$parts	= explode(TREE_SEP, $field);
				$size	= count($parts);

				if ($size == 2)	// flat entries
				{
					// <one|many> to <many>
					if ( strpos($parts[0], MANY_IND) !== false )
					{
						$alias = str_replace(MANY_IND, '', $parts[0]);
						$field = $parts[1];
						if ( $field == PRIM_KEY){
							$value = is_null($value) ? 0 : $value;
							$stack[$alias][PRIM_KEY][$value] = $value;
							$index = $value;
						}else {
							$index = end($stack[$alias][PRIM_KEY]);
							$data[$count][$alias][$index][$field] = $value;
						}
					}
					// <one|many> to <one>
					else
					{
						$alias = $parts[0];
						$field = $parts[1];
						if ($field != PRIM_KEY) {
							$data[$count][$alias][$field] = $value;
						}
					}
				}
				else if ( $size == 3)	// recursive entries
				{
					// <one|many> to <many>
					if ( strpos($parts[1], MANY_IND) !== false )
					{
						$alias1 = (strpos($parts[0], MANY_IND) !== false) ? str_replace(MANY_IND, '', $parts[0]) : $parts[0];
						$alias2	= str_replace(MANY_IND, '', $parts[1]);
						$field	= $parts[2];
						if ( $field == PRIM_KEY ) {
							$value = is_null($value) ? 0 : $value;
							$stack[$alias1][$alias2][PRIM_KEY][$value] = $value;
							$index2 = $value;
						}else {
							$index1	= isset($stack[$alias1][PRIM_KEY]) ? end($stack[$alias1][PRIM_KEY]) : 0;
							$index2 = end($stack[$alias1][$alias2][PRIM_KEY]);

							if (strpos($parts[0], MANY_IND) !== false) {
								$data[$count][$alias1][$index1][$alias2][$index2][$field] = $value;
							}else {
								$data[$count][$alias1][$alias2][$index2][$field] = $value;
							}
						}
					}
					// <one|many> to <one>
					else
					{
						$alias1 = (strpos($parts[0], MANY_IND) !== false) ? str_replace(MANY_IND, '', $parts[0]) : $parts[0];
						$alias2	= $parts[1];
						$field	= $parts[2];
						$data[$count][$alias1][$alias2][$field] = $value;
					}
				}
			}
		};
		$return = $this->db->select($query, $lambda);
		return $return;
	}




	/* ************************************************************************************************************************** *
	 *
	 *	P R I V A T E   P R I V A T E   C L A S S   H E L P E R
	 *
	 * ************************************************************************************************************************** */

	private function _buildMainQueryLimitCondition($condition, $limit)
	{
		// CONDITION | LIMIT:
		// --------------------------------------
		// @param: string
		//
		// We cannot limit by lines directly.
		// LIMIT 1 on the main query would result in fetching one row only,
		// but if the entity has a few hasMany relations it would need more
		// than one row. So we have to put the LIMIT into a seperate
		// sub query WHERE-CONDITION:
		//
		// _MAIN_QUERY WHERE Alias.id IN (SELECT * FROM (SELECT id FROM $this->table WHERE (CONDITION) LIMIT) AS tmpCondtition)
		//
		$limit		= ($limit) ? ' LIMIT '.$limit : null;
		if ( $condition )
		{
			$subCont	= 'SELECT * FROM (SELECT '.$this->primary_key.' FROM '.$this->table.' AS '.$this->alias.' WHERE '.$condition.' '.$limit.') AS tmpLimitConditionTbl';
			$condition	= 'WHERE '.$this->alias.'.'.$this->primary_key.' IN('.$subCont.')';
		}
		else
		{
			$condition = '';
		}
		return $condition;
	}


	private function _buildMainQueryOrder($order)
	{
		// ORDER:
		// --------------------------------------
		// @param: mixed[]
		//
		//	1.) Function parameter has order?
		//	2.) Class $this->order has order?
		//	3.) Order by primary key as last instance (if the above fail)
		//		Fallback is needed in case the relations also specify an order
		//		so that our table always has priority on ordering.
		//		This is needed to extract the array values afterwards
		$order	= (is_array($order) && count($order))
					? $order
					:
					( (is_array($this->order) && count($this->order))
						? $this->order
						: array($this->alias.'.'.$this->primary_key => 'ASC')
					);

		return $order;
	}
	private function _buildHasOneQuery(&$fields, &$joins, $recursive, $prefix = '')
	{
		$joinType = 'JOIN';	// LEFT JOIN?	TODO: double check if this works!!! (also in recursive relations), otherwise LEFT JOIN

		foreach ( $this->hasOne as $alias => $properties )
		{
			// Associations
			$mainPK		= $this->alias.'.'.$this->primary_key;
			$orderPK	= isset($properties['primaryKey']) ? $properties['primaryKey'] : 'id';
			$thisPK		= $alias.'.'. (isset($properties['primaryKey']) ? $properties['primaryKey'] : 'id');
			$thisFK		= $alias.'.'.$properties['foreignKey'];

			// Table Data
			$thisTable	= $properties['table'];

			// Field Data
			$thisFields = Arrays::array_unshift_assoc($properties['fields'], PRIM_KEY, $orderPK);
			$thisFields	= $this->_buildAliasedFields($alias, $prefix.$alias, $thisFields);

			$thisSubQ	= isset($properties['subQueries'])	? $this->_buildAliasedSubQueries($alias, $prefix.$alias, $properties['subQueries']) : null;

			// ADD Fields
			$fields		= array_merge($fields, $thisFields);

			// ADD Sub Queries
			$fields		= $thisSubQ ? array_merge($fields, $thisSubQ) : $fields;

			// ADD JOINS
			$joins[]	= $joinType.' '.$thisTable.' AS '.$alias.' ON ('.$mainPK.'='.$thisFK.')';

			// Check for recursive Joining
			if ( isset($properties['recursive']) && $properties['recursive'] )
			{
				$class	= isset($properties['class']) ? $properties['class'] : Strings::camelCase($properties['table'], true);
				$plugin	= isset($properties['plugin'])? $properties['plugin']: null;
				$oTable = $plugin ? Loader::loadPluginTable($class, $plugin) : Loader::loadTable($class);

				// recurse all relations once, by setting recursion to false
				// recurse all relations once, by setting recursion to false
				$oTable->_buildHasOneQuery($fields, $joins, 0, $alias.TREE_SEP);
				$oTable->_buildHasManyQuery($fields, $joins, $order, 0, $alias.TREE_SEP);
				$oTable->_buildBelongsToQuery($fields, $joins, $order, 0, $alias.TREE_SEP);
			}
		}
	}


	private function _buildHasManyQuery(&$fields, &$joins, &$order, $recursive, $prefix = '')
	{
		$joinType = 'LEFT JOIN';

		foreach ( $this->hasMany as $alias => $properties )
		{
			// Associations
			$mainPK		= $this->alias.'.'.$this->primary_key;
			$orderPK	= isset($properties['primaryKey']) ? $properties['primaryKey'] : 'id';
			$thisPK		= $alias.'.'.$orderPK;
			$thisFK		= $alias.'.'.$properties['foreignKey'];

			// Table Data
			$thisTable	= $properties['table'];

			// Field Data
			$thisFields = Arrays::array_unshift_assoc($properties['fields'], PRIM_KEY, $orderPK);
			$thisFields	= $this->_buildAliasedFields($alias, $prefix.$alias, $thisFields, 'many');

			$thisSubQ	= isset($properties['subQueries'])	? $this->_buildAliasedSubQueries($alias, $prefix.$alias, $properties['subQueries'], 'many') : null;
			$thisOrder	= isset($properties['order'])		? $this->_buildAliasedOrder($properties['order'], $alias, $orderPK)  : $this->_buildAliasedOrder(null, $alias, $orderPK);

			// ADD Fields
			$fields		= array_merge($fields, $thisFields);

			// ADD Sub Queries
			$fields		= $thisSubQ ? array_merge($fields, $thisSubQ) : $fields;

			// ADD JOINS
			$joins[]	= $joinType.' '.$thisTable.' AS '.$alias.' ON ('.$mainPK.'='.$thisFK.')';

			// ADD ORDER
			$order		= $thisOrder ? array_merge($order, $thisOrder) : $order;

			// Check for recursive Joining
			if ( ($recursive > 1 &&	isset($properties['recursive']) && $properties['recursive']) ||
				 ($recursive > 2) // force
			)
			{
				$class	= isset($properties['class']) ? $properties['class'] : Strings::camelCase($properties['table'], true);
				$plugin	= isset($properties['plugin'])? $properties['plugin']: null;
				$oTable = $plugin ? Loader::loadPluginTable($class, $plugin) : Loader::loadTable($class);

				// recurse all relations once, by setting recursion to false
				// We are already in a X -> many relation, so we have to append the ManyIndicator to all local aliases
				$oTable->_buildHasOneQuery($fields, $joins, 0, $alias.MANY_IND.TREE_SEP);
				$oTable->_buildHasManyQuery($fields, $joins, $order, 0, $alias.MANY_IND.TREE_SEP);
				$oTable->_buildBelongsToQuery($fields, $joins, $order, 0, $alias.MANY_IND.TREE_SEP);
			}
		}
	}

	// prefix is used for inner calls to append prefix to alias: Users.Thread.id
	private function _buildBelongsToQuery(&$fields, &$joins, &$order, $recursive, $prefix = '')
	{
		$joinType = 'JOIN';

		foreach ( $this->belongsTo as $alias => $properties )
		{
			// Associations
			$mainPK		= $this->alias.'.'.$this->primary_key;
			$mainFK		= $this->alias.'.'.$properties['foreignKey'];
			$orderPK	= isset($properties['primaryKey']) ? $properties['primaryKey'] : 'id';
			$thisPK		= $alias.'.'.$orderPK;

			// Table Data
			$thisTable	= $properties['table'];

			// Field Data
			$thisFields = Arrays::array_unshift_assoc($properties['fields'], PRIM_KEY, $orderPK);
			$thisFields	= $this->_buildAliasedFields($alias, $prefix.$alias, $thisFields);

			$thisSubQ	= isset($properties['subQueries'])	? $this->_buildAliasedSubQueries($alias, $prefix.$alias, $properties['subQueries']) : null;
			$thisOrder	= isset($properties['order'])		? $this->_buildAliasedOrder($properties['order'], $alias, $orderPK)  : $this->_buildAliasedOrder(null, $alias, $orderPK);

			// ADD Fields
			$fields		= array_merge($fields, $thisFields);

			// ADD Sub Queries
			$fields		= $thisSubQ ? array_merge($fields, $thisSubQ) : $fields;

			// ADD JOINS
			$joins[]	= $joinType.' '.$thisTable.' AS '.$alias.' ON ('.$mainFK.'='.$thisPK.')';

			// ADD ORDER
			$order		= $thisOrder ? array_merge($order, $thisOrder) : $order;

			// Check for recursive Joining
			if ( ($recursive > 1 &&	isset($properties['recursive']) && $properties['recursive']) ||
				 ($recursive > 2) // force
			)
			{
				$class	= isset($properties['class']) ? $properties['class'] : Strings::camelCase($properties['table'], true);
				$plugin	= isset($properties['plugin'])? $properties['plugin']: null;
				$oTable = $plugin ? Loader::loadPluginTable($class, $plugin) : Loader::loadTable($class);

				// recurse all relations once, by setting recursion to false
				// recurse all relations once, by setting recursion to false
				$oTable->_buildHasOneQuery($fields, $joins, 0, $alias.TREE_SEP);
				$oTable->_buildHasManyQuery($fields, $joins, $order, 0, $alias.TREE_SEP);
				$oTable->_buildBelongsToQuery($fields, $joins, $order, 0, $alias.TREE_SEP);
			}
		}
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
	private function _buildAliasedOrder($order, $tbl_alias, $tbl_pk = null, $def_direction = 'ASC')
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
			if ( $tbl_pk )
			{
				$newOrder[$tbl_alias.'.'.$tbl_pk] = $def_direction;
			}
			else
			{
				return array();
			}
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

	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $alias
	 * @param unknown_type $fields
	 * @param string $relation	'one' | 'many'
	 * 		In 'many' we will append an 's' to the query, so that we know it is a X->many relation
	 */
	private function _buildAliasedFields($tbl_alias, $prefix, $fields, $relation = 'one')
	{
		$aFields	= array();
		$many		= ($relation == 'many') ? MANY_IND : '';

		foreach ($fields as $alias => $field)
		{
			// numerical array (no alias)		// <prefix>(s).<field_name>
			if ( is_integer($alias) )
			{
				$aFields[] = $tbl_alias.'.'.$field.' AS `'.$prefix.$many.TREE_SEP.$field.'`';
			}
			// associative array (has alias)	// <prefix>(s).<field_alias>
			else
			{
				$aFields[] = $tbl_alias.'.'.$field.' AS `'.$prefix.$many.TREE_SEP.$alias.'`';
			}
		}
		return $aFields;
	}

	private function _buildAliasedSubQueries($tbl_alias, $prefix, $subQueries, $relation = 'one')
	{
		$aSubQueries= array();
		$many		= ($relation == 'many') ? MANY_IND : '';

		// TODO: [Performace]: Change to array_map function
		foreach ($subQueries as $alias => $query)
		{
			$aSubQueries[] = '('.$query.') AS `'.$prefix.$many.TREE_SEP.$alias.'`';
		}
		return $aSubQueries;
	}


	private function _appendModifiedFieldIfExist($fields)
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
	private function _appendCreatedFieldIfExist($fields)
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

}
