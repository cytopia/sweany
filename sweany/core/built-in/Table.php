<?php

define('PRIM_KEY',	'__PRIM_KEY__');	// primary indicator to append at each trable
define('ROW_COUNT',	'__ROW_COUNT__');
define('PREV_ID',	'__PREV_ID__');
define('REL_TYPE',	'__REL_TYPE__');


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




	/**
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
	 *			'dependent'		=> false,
	 *			'recursive'		=> false|true,			# true: also load the depending table with its relations | false: only load this relation
	 *			'recursive'		=> array('hasMany' => array('Alias1', 'Alias', 'belongsTo' => array('One'))
	 *				# only follow specific relations recursively (in this case the Alias1 & Alias2 from 'hasMany' and 'One' from 'belongsTo')
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
	 *			'recursive'		=> false|true,			# true: also load the depending table with its relations | false: only load this relation
	 *			'recursive'		=> array('hasMany' => array('Alias1', 'Alias', 'belongsTo' => array('One'))
	 *				# only follow specific relations recursively (in this case the Alias1 & Alias2 from 'hasMany' and 'One' from 'belongsTo')
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
	 *			'dependent'		=> false,
	 *			'recursive'		=> false|true,			# true: also load the depending table with its relations | false: only load this relation
	 *			'recursive'		=> array('hasMany' => array('Alias1', 'Alias', 'belongsTo' => array('One'))
	 *				# only follow specific relations recursively (in this case the Alias1 & Alias2 from 'hasMany' and 'One' from 'belongsTo')
	 *			'hasCreated'	=> '<SQLDataType>' | array('<field_name>' => '<SQLDataType>'),		# If set, adds date-time value on insert (sql def field: 'created') or specify field name
	 *			'hasModified'	=> '<SQLDataType>' | array('<field_name>' => '<SQLDataType>'),		# If set, adds date-time value on update (sql def field: 'modified') or specify field name
     *   	),
	 *	);
	 */
	public $belongsTo		= array();



	// TODO implement:
	public $hasAndBelongsToMany	= array();




	/* ***************************************  C L A S S E S  *************************************** */

	/**
	 *	@param	class	Database Class
	 */
	protected $db;




	/* ************************************************************************************************************************** *
	 *
	 *	C O N S T R U C T O R   /   D E S T R U C T O R
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




	/* ************************************************************************************************************************** *
	 *
	 *	E N T I T Y   G E T   F U N C T I O N S
	 *
	 * ************************************************************************************************************************** */


	/**
	 *
	 *	Load One entity by id
	 *
	 *	@param	integer			$id			Id of the entity
	 *	@param	string[]|null	$fields		Array of fields or 'null' for default
	 *	@param	string[]|null	$subQueries	Override Array of subqueries or 'null' for default
	 *		Array(
	 *			'alias1' => 'subquery here',
	 *			'alias2' => 'another subquery here',
	 *			...
	 *		)
	 *	@param	integer			$recursive	Level of recursions (0-3)
	 *		0: flat - no relations, only this table
	 *		1: with relations (hasOne, hasMany, belongsTo, hasAndBelongsToMany)
	 *		2: with relations and follow recursion specified by relation
	 *		3: with relations and force recursion on all relations
	 *
	 *	@return	mixed[]			$data		Returns single entity
	 */
	public function load($id, $fields = null, $subQueries = null, $recursive = 1, $return = 'object')
	{
		$data = $this->loadMany(array($id), $fields, $subQueries, null, $recursive, $return);
		return ( isset($data[0]) ) ? $data[0] : array();
	}


	/**
	 *
	 *	Load Many entities by an array of ids
	 *
	 *	@param	integer			$id			Id of the entity
	 *	@param	string[]|null	$fields		Array of fields or 'null' for default
	 *	@param	string[]|null	$subQueries	Override Array of subqueries or 'null' for default
	 *		Array(
	 *			'alias1' => 'subquery here',
	 *			'alias2' => 'another subquery here',
	 *		)
	 *	@param	mixed[]			$order		Override Array of order clauses (only applies to fields in this table)
	 *		Array(
	 *			'field1' => 'ASC',
	 *			'field2' => 'DESC',
	 *			'GREATEST(field1, field2)' => 'ASC',
	 *			 ...
	 *		)
	 *	@param	integer			$recursive	Level of recursions (0-3)
	 *		0: flat - no relations, only this table
	 *		1: with relations (hasOne, hasMany, belongsTo, hasAndBelongsToMany)
	 *		2: with relations and follow recursion (if set to true in respective relations defintion of the current table)
	 *		3: with relations and force recursion on all relations (even if not set or set to false)
	 *
	 *	@return	mixed[]			$data		Returns all found entities
	 */
	public function loadMany($ids, $fields = null, $subQueries = null, $order = null, $recursive = 2, $return = 'object')
	{
		$condition	= $this->primary_key.' IN ('.implode(',', $ids).')';
		$order		= ($order) ? $order : ($this->order ? $this->order : $this->alias.'.'.$this->primary_key);
		$query		= $this->buildQuery($fields, $subQueries, $condition, $order, null, $recursive);
		$data		= $this->retrieveResults($query, $return);
		return $data;
	}



	/**
	 *
	 *	Check if the entity exists
	 *
	 *	@param	integer		$id		Id of the entity (row)
	 *	@return	boolean		exists?
	 */
	public function exist($id)
	{
		return $this->db->rowExists($this->table, $id);
	}


	public function existBy($condition)
	{
		return $this->find('count', array('condition' => $condition));
	}

	/**
	 *
	 *	Get value of a field from the entity (row)
	 *
	 *	@param	name		$name	Name of the field (in row)
	 *	@param	integer		$id		Id of the entity (row)
	 *	@return	mixed		$value	Value of the field
	 *
	 */
	public function Field($name, $id)
	{
		return $this->db->fetchRowField($this->table, $name, $id);
	}


	/**
	 *
	 *	Get value of a field by condition
	 *
	 *	@param	name		$name		Name of the field (in row)
	 *	@param	string		$condition	SQL Condition (make sure to escape the values)
	 *	@return	mixed		$value		Value of the field
	 *
	 */
	public function FieldBy($name, $condition)
	{
		return $this->db->fetchField($this->table, $name, $this->prepare($condition));
	}



	/**
	 *	find($type, $options)
	 *
	 *	The swiss army knife of Sweany's db functionality
	 *
	 *	@param	string	$type		Type of Operation
	 *		all		Return all results
	 *		first	Return first result
	 *		last	Return last result
	 *		count	Count results only
	 *
	 *	@param	mixed[]	$options
	 *
	 *		return			Specify how to retrieve the results
	 *			'return'	=> 'array'	return an array of arrays
	 *			'return'	=> 'object'	return an array of objets
	 *			[DEFAULT]	Returns array of objects
	 *
	 *		fields	 		Overwrite: Array of fields to fetch
	 *			'fields' => array('field_name', 'alias' => 'field_name')
	 *			[DEFAULT]			$this->fields
	 *			[NOTE]				Useless in 'count' operation
	 *
	 *		subQueries	 	Overwrite: Array of fields to fetch
	 *			'subQueries' => array('alias' => 'sub_query_here')
	 *			[DEFAULT]			$this->subQueries
	 *			[NOTE]				Useless in 'count' operation
	 *
	 *		condition		Overwrite: Condition to append
	 *			'condition' => array('field1 LIKE :t AND id IN(5,1)' => array(':t' => '%patu%'))	// escapable statement
	 *			'condition'	=> 'id = 5'	// non-escapable statement
	 *			[DEFAULT]			$this->condition
	 *			[DEFAULT OVERRIDE] 'condition' => null
	 *
	 *		order			Overwrite: Order by
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
	 *		range			Limited range		# (limit and range are mutually exclusive - limit has priority over range)
	 *			'range'	=> array(0,5)			# first 5 results
	 *			'range'	=> array(5,3)			# 6th ,7th and 8th result
	 *			[NOTE]				Damn useless in 'count' operation
	 *
	 *		recursive	=> 0-3	Overwrite: recursion level
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
		// Extract Condition
		$condition	= isset($options['condition'])	? $options['condition'] : $this->condition;
		$condition	= $this->prepare($condition);

		// Return count immediately, if chosen
		if ( $type == 'count' )
		{
			return $this->db->count($condition);
		}


		// Get Options;
		$fields 	= isset($options['fields'])		? $options['fields']	: $this->fields;
		$subQueries	= isset($options['subQueries']) ? $options['subQueries']: $this->subQueries;
		$order		= isset($options['order'])		? $options['order']		: $this->order;
		$recursive	= isset($options['recursive'])	? $options['recursive']	: 1;	// defaults to 1

		// Get mutually exclusive limit/order (limit takes priority over range)
		if ( isset($options['limit']) )
		{
			$limit = $options['limit'];
		}
		else
		{
			$limit	= (isset($options['range']) && is_array($options['range'])) ? implode(',', $options['range']) : null;
		}

		$return		= isset($options['return'])		? $options['return']	: 'object';	// default to object, if no value is set
		$return		= ($return == 'array' || $return == 'object') ? $return : 'object';	// default to object, if wrong value is set


		// Build Query
		$query		= $this->buildQuery($fields, $subQueries, $condition, $order, $limit, $recursive);
		$data		= $this->retrieveResults($query, $return);

		// NOTE:
		// TODO: This is only a temporary solution and will be replaced
		// by propper SQL queries later (just to get the fw back to work
		// FIXME:!!!!
		switch ($type)
		{
			case 'all':			return $data;
			case 'first':		return isset($data[0]) ? $data[0] : array();
			case 'last':		return isset($data[count($data)-1]) ? $data[count($data)-1] : array();
			default: /*'all'*/	return $data;
		}
	}



	/* ************************************************************************************************************************** *
	 *
	 *	E N T I T Y   S A V E   F U N C T I O N S
	 *
	 * ************************************************************************************************************************** */


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
	public function save($fields, $return = 1)
	{
		$fields = $this->_appendCreatedFieldIfExist($fields);
		$ret	= $this->db->insert($this->table, $fields, (($return) ? true : false));

		switch ($return)
		{
			// return non-recursive row
			case 2:
				return $this->load($ret, null, 0);

			// [DEFAULT] return success of operation or last insert id
			default:
				return $ret;
		}
	}




	/* ************************************************************************************************************************** *
	 *
	 *	E N T I T Y   U P D A T E   F U N C T I O N S
	 *
	 * ************************************************************************************************************************** */


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
		$fields = $this->_appendModifiedFieldIfExist($fields);

		// TODO, only extract all fields that actually exist as specified in the table
		$success	= $this->db->updateRow($this->table, $id, $fields);

		switch ($return)
		{
			case 1:		return $id;
			case 2:		return $this->load($id, null, 0);
			default:	return $success;
		}
	}
	public function updateAll($condition, $fields, $return = 0)
	{
		return $this->db->update($this->table, $fields, $this->prepare($condition), $return);
	}


	public function increment($id, $fields, $return = 0)
	{
		$condition = $this->prepare(array('id = :id', array(':id' => $id)));

		return $this->db->incrementFields($this->table, $fields, $condition, $return);
	}

	// TODO: return (array)values on $return = 2
	public function incrementAll($condition, $fields, $return = 0)
	{
		return $this->db->incrementFields($this->table, $fields, $this->prepare($condition), $return);
	}


	/* ************************************************************************************************************************** *
	 *
	 *	E N T I T Y   D E L E T E   F U N C T I O N S
	 *
	 * ************************************************************************************************************************** */


	/**
	 *	Delete entity (by id)
	 *
	 *	@param	integer	$id			Id of the row/entity
	 *	@return	boolean				Success of operation
	 */
	public function delete($id, $related = false)
	{
		// TODO: delete related data in this->[relations]
		return $this->db->deleteRow($this->table, $id);
	}

	public function deleteAll($condition, $related = false, $return = 0)
	{
		// TODO: delete related data in this->[relations]
		return $this->db->delete($this->table, $this->prepare($condition));
	}






	/* ************************************************************************************************************************** *
	*
	*	P R I V A T E   C L A S S   F U N C T I O N S
	*
	* ************************************************************************************************************************** */


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
	private function prepare($statement = null)
	{
		if ( is_string($statement) ) {
			return $statement;
		}
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


	private function buildDerivedTableQueryWithRowCount($table, $alias, $p_alias = '', $fields, $subQueries, $where, $order = null)
	{

		$fields = $this->buildFields($fields, '', '', '', '', false);
		$fields = array_merge($fields, $subQueries);
		$fields	= implode(',', $fields);
		$where	= $where ? ' WHERE '.$this->prepare($where) : '';

		$order	= (is_array($order) && count($order)) ? 'ORDER BY '.implode(',', array_map( create_function('$field, $direction', 'return $field." ".$direction;'), array_keys($order), array_values($order))) : '';
		$query	= sprintf(
			'SELECT '.
				'%s '.		// FIELDS
			'FROM '.
				'%s AS %s, '.		// Table
				'(SELECT @'.$p_alias.$alias.ROW_COUNT.' := 0)	AS x, '.// ROW_COUNT
				'(SELECT @'.$p_alias.$alias.PREV_ID.'   := NULL)	AS y '.	// Previous row id
			'%s '.
			'%s ',
			$fields, $table, $alias, $where, $order
		);
		return $query;
	}

	private function buildSubQueries($subQueries, &$iFields, &$oFields, $i_prefix, $o_prefix, $t_alias)
	{
		$queries		= array();

		foreach ($subQueries as $sAlias => $query)
		{
			$queries[]	= '('.$query.') AS `'.$sAlias.'`';
			$iFields[]	= $i_prefix.$t_alias.'.'.$sAlias.' AS `'.$o_prefix.$t_alias.'_'.$sAlias.'`';
			$oFields[]	= $o_prefix.$t_alias.'_'.$sAlias.' AS `'.$i_prefix.$t_alias.'.'.$sAlias.'`';
		}
		return $queries;
	}
	private function buildMainTableSubQueries($subQueries, &$iFields, &$oFields, $t_alias)
	{
		$queries		= array();

		foreach ($subQueries as $sAlias => $query)
		{
			$queries[]	= '('.$query.') AS `'.$sAlias.'`';
			$iFields[]	= $t_alias.'.'.$sAlias.' AS `'.$t_alias.'_'.$sAlias.'`';
			$oFields[]	= $t_alias.'_'.$sAlias.' AS `'.$sAlias.'`';
		}
		return $queries;
	}

	private function buildFields($tbl_fields, $field_prefix, $alias_prefix, $field_seperator = '.', $alias_seperator = '_', $force_alias = true)
	{
		$fields	= array();

		foreach ($tbl_fields as $alias => $field)
		{
			// numerical array (no alias)		// AS <tbl_alias>.<field_name>
			if ( is_integer($alias) )
			{
				$fields[]	= ($force_alias) ? $field_prefix.$field_seperator.$field.' AS `'.$alias_prefix.$alias_seperator.$field.'`' : $field_prefix.$field_seperator.$field;
			}
			// associative array (has alias)	// AS <tbl_alias>.<field_alias>
			else
			{
				$fields[]	= $field_prefix.$field_seperator.$field.' AS `'.$alias_prefix.$alias_seperator.$alias.'`';
			}
		}
		return $fields;
	}

	private function buildQuery($fields = null, $subQueries = null, $where = null, $order = null, $limit = null, $recursive = 1)
	{
		// CHOOSE DEFAULT FIELDS
		$fields	= ( is_array($fields) && count($fields) ) ? $fields : $this->fields;

		// CHOOSE DEFAULT SUBQUERIES
		$subQueries	= ( is_array($subQueries) && count($subQueries) ) ? $subQueries : $this->subQueries;

		// OUTER FIELDS
		$o_row_name		= $this->alias.ROW_COUNT;
		$o_row			= $this->alias.ROW_COUNT.' AS '.ROW_COUNT;
		$o_pk			= $this->alias.'_'.$this->primary_key.' AS '.PRIM_KEY;
		$o_fields		= $this->buildFields($fields, $this->alias, /*$this->alias*/'', '_', /*'.'*/'');		// <alias>_<field> AS `<alias>.<field>`
		array_unshift($o_fields, $o_row);			// _ROW_COUNT_ to the beginning
		array_unshift($o_fields, $o_pk);			// _PRIM_KEY_ to the beginning

		// INNER FIELDS
		$i_fields		= $this->buildFields($fields, $this->alias, $this->alias, '.', '_');	// <alias>.<field> AS `<alias>_<field>`
		$row_num_cond	= '@'.$this->alias.PREV_ID.' = '.$this->alias.'.'.$this->primary_key;
		$row_num_var	= '@'.$this->alias.ROW_COUNT.' :=  IF('.$row_num_cond.', @'.$this->alias.ROW_COUNT.', @'.$this->alias.ROW_COUNT.'+1) AS '.$o_row_name;
		$prev_id		= '@'.$this->alias.PREV_ID.  ' := '.$this->alias.'.'.$this->primary_key;
		array_unshift($i_fields, $row_num_var);		// row_number to the beginning

		// INNER FIELDS to append at the bottom
		$i_fields_last[]= $prev_id;


		// SUBQUERIES
		$subQueries = $this->buildMainTableSubQueries($subQueries, $i_fields, $o_fields, $this->alias);

		// DERIVED INNER MAIN TABLE
		$main_table = $this->buildDerivedTableQueryWithRowCount($this->table, $this->alias, '', $fields, $subQueries, $where, $order);


		// BUILD LIMIT
		if ($limit) {
			$tmp	= $limit;
			$limit	= array();
			$limit[$o_row_name] = $tmp;
		}

		// JOINS
		$joins	= null;
		if ( $recursive > 0)
		{
			// Add hasOne (one-to-one)
			$this->_buildHasOneQuery(null, $o_fields, $i_fields, $i_fields_last, $joins, $row_num_cond, $recursive);

			// Add hasMany (one-to-many)
			$this->_buildHasManyQuery(null, $o_fields, $i_fields, $i_fields_last, $joins, $order, $limit, $row_num_cond, $recursive);

			// Add belongsTo (many-to-one)
			$this->_buildBelongsToQuery(null, $o_fields, $i_fields, $i_fields_last, $joins, $row_num_cond, $recursive);
		}
		// Append bottom INNER FIELDS after relations have been worked through
		$i_fields = array_merge($i_fields, $i_fields_last);



		// MAIN CONDITION (empty where, it has already been used in derived table)
		$where	= null; //($where) ? 'WHERE '.$where : '';

		// GET LIMIT via WHERE row_count
		$where	= (is_array($limit) && count($limit)) ? 'WHERE '.implode(' AND ', array_map( create_function('$key, $val', 'return $key."<=".$val;'), array_keys($limit), array_values($limit))) : '';
/*
		if ($where && $limit) {
			$where = $where.' AND '.$limit;
		}
		else if ($where && !$limit) {
			$where = $where;
		}
		else if (!$where && $limit) {
			$where = 'WHERE '.$limit;
		}*/

		// We don't need the order at the end, as we do it in derived tables
		//$order	= (is_array($order) && count($order)) ? 'ORDER BY '.implode(',', array_map( create_function('$field, $direction', 'return $field." ".$direction;'), array_keys($order), array_values($order))) : '';

		// We use (simulated for mysql) row_numbers to limit the returned rows
		// so there is no LIMIT clause needed
		$query =
			'SELECT '.
				implode(', ', $o_fields).' '.		// OUTER FIELDS: <alias>_<field> AS <alias>.<field>
			'FROM ('.
				'SELECT '.
					implode(',', $i_fields).' '.	// INNER FIELDS: <alias>.<field> AS <alias>_field> & row_number vars
				'FROM ('.
					$main_table.' '.				// Derived Table
				') AS '.$this->alias.' '.
				implode('', $joins).' '.							// Derived JOIN(s)
			') AS z '.
			$where.' '.		// WHERE
			//'%s '.		// GROUP
			//'%s '.		// HAVING
//			$order.			// ORDER
			//'%s ';		// LIMIT
			'';
		//debug($query);
		return $query;
	}


	/**
	 *
	 *	Retrieve processed database result array
	 *
	 *	@param	string	$query		SQL Query
	 *	@param	string	$return		How to return the data 'object' or 'array'
	 *	@return	mixed[]	Result array
	 *
	 *	Note:
	 *	This function created a lambda callback and parses
	 *	it to the database select function in order to build
	 *	the result array according to the set entity relations
	 *	(hasOne, hasMany, belongsTo...)
	 */
	private function retrieveResults($query, $return = 'object')
	{
		$i1			= 0;			// row_count level 1
		$i2			= 0;			// row_count level 2
		$i3			= 0;			// row_count level 3
		$alias		= $this->alias;


		$lambdaArray = function ($row, &$data) use (&$i1, &$i2, &$i3, $alias)
		{
			foreach ($row as $field => $value)
			{
				$part	= explode('.', $field);
				$size	= count($part);

				if ($size == 1 && $field != PRIM_KEY && $field != ROW_COUNT)	// This table
				{
					$i1 = ($row[ROW_COUNT]-1);	// current row number
					$data[$i1][$alias][$field] = $value;

				}
				else if ($size == 2)	// flat entries
				{
					$l2Alias	= $part[0];
					$i2			= $row[$l2Alias.'.'.ROW_COUNT] - 1;
					$l2Rel		= $row[$l2Alias.'.'.REL_TYPE];

					if ( $part[1] != PRIM_KEY && $part[1] != ROW_COUNT && $part[1] != REL_TYPE )
					{
						// <one|many> to <one>
						if ( $l2Rel == 'one')
						{
							$data[$i1][$l2Alias][$part[1]] = $value;
						}
						// <one|many> to <many>
						else if ( $l2Rel == 'many' )
						{
							$data[$i1][$l2Alias][$i2][$part[1]] = $value;
						}
					}
				}
				else if ( $size == 3 )
				{
					$l3Alias	= $part[1];
					$i3			= $row[$l2Alias.'.'.$l3Alias.'.'.ROW_COUNT] - 1;
					$l3Rel		= $row[$l2Alias.'.'.$l3Alias.'.'.REL_TYPE];

					if ( $part[2] != PRIM_KEY && $part[2] != ROW_COUNT && $part[2] != REL_TYPE )
					{
						// <one|many> to <one>
						if ( $l3Rel == 'one')
						{
							// <one|many> to <one>
							if ( $l2Rel == 'one')
							{
								$data[$i1][$l2Alias][$l3Alias][$part[2]] = $value;
							}
							// <one|many> to <many>
							else if ( $l2Rel == 'many' )
							{
								$data[$i1][$l2Alias][$i2][$l3Alias][$part[2]] = $value;
							}
						}
						// <one|many> to <many>
						else if ( $l3Rel == 'many' )
						{
							// <one|many> to <one>
							if ( $l2Rel == 'one')
							{
								$data[$i1][$l2Alias][$l3Alias][$i3][$part[2]] = $value;
							}
							// <one|many> to <many>
							else if ( $l2Rel == 'many' )
							{
								$data[$i1][$l2Alias][$i2][$l3Alias][$i3][$part[2]] = $value;
							}
						}
					}
				}
			}
		};

		$lambdaObject = function ($row, &$data) use (&$i1, &$i2, &$i3, $alias)
		{
			foreach ($row as $field => $value)
			{
				$part	= explode('.', $field);
				$size	= count($part);

				if ($size == 1 && $field != PRIM_KEY && $field != ROW_COUNT)	// This table
				{
					$i1 = ($row[ROW_COUNT]-1);	// current row number

					if ( !isset($data[$i1]) ) {
						$data[$i1] = new stdClass;
					}
					if ( !isset($data[$i1]->$alias) ) {
						$data[$i1]->$alias = new stdClass;
					}
					$data[$i1]->$alias->$field = $value;

				}
				else if ($size == 2)	// flat entries
				{
					$l2Alias	= $part[0];
					$i2			= $row[$l2Alias.'.'.ROW_COUNT] -1;
					$l2Rel		= $row[$l2Alias.'.'.REL_TYPE];

					if ( $part[1] != PRIM_KEY && $part[1] != ROW_COUNT && $part[1] != REL_TYPE )
					{
						// <one|many> to <one>
						if ( $l2Rel == 'one')
						{
							if ( !isset($data[$i1]) ) {
								$data[$i1] = new stdClass;
							}
							if ( !isset($data[$i1]->$l2Alias) ) {
								$data[$i1]->{$l2Alias} = new stdClass;
							}
							//debug('OK: in Table.php retrieveResults, size==2  X->one');
							$data[$i1]->{$l2Alias}->$part[1] = $value;
						}
						// <one|many> to <many>
						//else if ( $l2Rel == 'many' )
						else
						{
							if ( !isset($data[$i1]->{$l2Alias}[$i2]) ) {
								$data[$i1]->{$l2Alias}[$i2] = new stdClass;
							}
							//debug('FIXME: in Table.php retrieveResults, size==2  X->many');
							(object)$data[$i1]->{$l2Alias}[$i2] = (object)array_merge((array)$data[$i1]->{$l2Alias}[$i2], array($part[1] => $value));
							//(object)$data[$i][$l2Alias]->$l2Index->$part[1] = $value;
						}
					}
				}
				else if ( $size == 3 )
				{
					$l3Alias	= $part[1];
					$i3			= $row[$l2Alias.'.'.$l3Alias.'.'.ROW_COUNT] -1;
					$l3Rel		= $row[$l2Alias.'.'.$l3Alias.'.'.REL_TYPE];

					if ( $part[2] != PRIM_KEY && $part[2] != ROW_COUNT && $part[2] != REL_TYPE )
					{
						// <one|many> to <one|many> to <one>
						if ( $l3Rel == 'one')
						{
							// <one|many> to <one> to <one>
							if ( $l2Rel == 'one')
							{
								//debug('FIXME: in Table.php retrieveResults, size==3  X->one->one');
								(object)$data[$i1]->$l2Alias->$l3Alias->$part[2] = 'aaaa';	// TODO: add this!!!
							}
							// <one|many> to <many> to <one>
							//else if ( $l2Rel == 'many' )
							else
							{
								//debug('FIXME: in Table.php retrieveResults, size==3  X->many->one');
								(object)$data[$i1]->$l2Alias->$i2->$l3Alias->$part[2] = 'vvvv';
							}
						}
						// <one|many> to <one|many> to <many>
						//else if ( $l3Rel == 'many' )
						else
						{
							// <one|many> to <one> to <many>
							if ( $l2Rel == 'one')
							{
								//debug('FIXME: in Table.php retrieveResults, size==3  X->one->many');
								(object)$data[$i1]->$l2Alias->$l3Alias->$i3->$part[2] = 'abbbbb';
							}
							// <one|many> to <many> to <many>
							//else if ( $l2Rel == 'many' )
							else
							{
								if ( !isset($data[$i1]->{$l2Alias}[$i2]) ) {
									$data[$i1]->{$l2Alias}[$i2] = new stdClass;
								}
								if ( !isset($data[$i1]->{$l2Alias}[$i2]->{$l3Alias}[$i3]) ) {
									$data[$i1]->{$l2Alias}[$i2]->{$l3Alias}[$i3] = new stdClass;
								}
								//debug('FIXME: in Table.php retrieveResults, size==3  X->many->many');
								$data[$i1]->{$l2Alias}[$i2]->{$l3Alias}[$i3]->$part[2] = $value;//->{$l2Index}[$part[2]]= $value;//(array)array_merge((array)$data[$i][$l2Alias][$l2Index]->$l3Alias, array($l3Index => array($part[2] => $value)));
								//(object)$data[$i][$l2Alias][$l2Index]->$l3Alias->{$l2Index}[$part[2]]= $value;//(array)array_merge((array)$data[$i][$l2Alias][$l2Index]->$l3Alias, array($l3Index => array($part[2] => $value)));
							}
						}
					}
				}
			}
		};

		// TODO: This is still buggy, as the variables are only available
		// during the second query. But we do not want to have two queries
		if ( $return == 'array' )
		{
			$return = $this->db->select($query, $lambdaArray);
		}
		else
		{
			$return = $this->db->select($query, $lambdaObject);
		}

		return $return;
	}




	/* ************************************************************************************************************************** *
	 *
	 *	P R I V A T E   P R I V A T E   C L A S S   H E L P E R
	 *
	 * ************************************************************************************************************************** */



	private function _buildHasOneQuery($limitAliase = false, &$oFields, &$iFields, &$iFieldsBottom, &$joins, $parent_row_num_cond, $recursive, $prefix = '')
	{
		$joinType	= 'LEFT JOIN';
		$relType	= 'one';

		foreach ( $this->hasOne as $alias => $properties )
		{
			// This is used for the recursive relations, so that we can limit what to follow
			if ( !$limitAliase || in_array($alias, $limitAliase) )
			{
				// Associations
				$mainPK			= $this->alias.'.'.$this->primary_key;
				$thisPK			= isset($properties['primaryKey']) ? $properties['primaryKey'] : 'id';
				$thisAliasedPK	= $alias.'.'.$thisPK;
				$thisFK			= $properties['foreignKey'];
				$thisAliasedFK	= $alias.'.'.$thisFK;
				$thisON			= $mainPK.'='.$thisAliasedFK;

				// Fields
				$thisFields		= $properties['fields'];

				// SubQueries
				$thisSubQ		= isset($properties['subQueries'])	? $properties['subQueries'] : array();

				// Conditions and Order
				$thisCondition	= isset($properties['condition'])	? $properties['condition']	: null;

				// Table Data
				$thisTable		= $properties['table'];


				// FIELD PREFIXE
				$o_prefix		= $prefix ? $prefix.'_' : '';	// outer prefix
				$i_prefix		= $prefix ? $prefix.'.' : '';	// inner prefix
				$a_prefix		= $prefix ? $prefix.'.' : '';	// aliased prefix


				// ---------------------------- OUTER FIELDS (Level 1)
				$o_row_name		= $o_prefix.$alias.'_'.ROW_COUNT;
				$o_pk_name		= $o_prefix.$alias.'_'.$thisPK;
				$o_rel_name		= "'".$relType."'";

				$o_row_alias	= $a_prefix.$alias.'.'.ROW_COUNT;
				$o_pk_alias		= $a_prefix.$alias.'.'.PRIM_KEY;
				$o_rel_alias	= $a_prefix.$alias.'.'.REL_TYPE;

				$o_row			= $o_row_name.' AS `'. $o_row_alias.'`';	// Row Count
				$o_pk			= $o_pk_name. ' AS `'. $o_pk_alias. '`';	// Primary Key
				$o_type			= $o_rel_name.' AS `'.$o_rel_alias. '`';	// Relation Type X->One

				$o_fields		= $this->buildFields($thisFields, $o_prefix.$alias, $i_prefix.$alias, '_', '.');// <alias>_<field> AS `<alias>.<field>`
				array_unshift($o_fields, $o_type);			// relation type to the beginning
				array_unshift($o_fields, $o_row);			// row_count alias to the beginning
				array_unshift($o_fields, $o_pk);			// _PRIM_KEY_ to the very beginning


				// ---------------------------- INNER FIELDS (Level 2)
				$prev_id_name	= '@'.$o_prefix.$alias.PREV_ID;
				$row_count_name	= '@'.$o_prefix.$alias.ROW_COUNT;

				$row_num_cond	= $prev_id_name.' = '.$thisAliasedPK;
				$row_num		= $row_count_name.' :=  IF('.$row_num_cond.', '.$row_count_name.', IF('.$parent_row_num_cond.', '.$row_count_name.' +1, 1)) AS `'.$o_prefix.$alias.'_'.ROW_COUNT.'`';
				$prev_id		= $prev_id_name.' := '.$thisAliasedPK;

				$i_fields		= $this->buildFields($thisFields, $i_prefix.$alias, $o_prefix.$alias, '.', '_');// <alias>.<field> AS `<alias>_<field>`
				array_unshift($i_fields, $row_num);		// row_number to the beginning


				// ---------------------------- SUBQUERIES (Level 1,2,3)
				$thisSubQ		= $this->buildSubQueries($thisSubQ, $i_fields, $o_fields, $i_prefix, $o_prefix, $alias);


				// ---------------------------- JOIN FIELDS (Level 3)
				// make sure to add all fields that are required by order, where and join on(...)
				$thisDFields	= $thisFields;
				if ( !in_array($thisFK, $thisDFields) ) {
					$thisDFields[]	= $thisFK;
				}
				if ( !in_array($thisPK, $thisDFields) ) {
					$thisDFields[]	= $thisPK;
				}
				$thisDTable		= $this->buildDerivedTableQueryWithRowCount($thisTable, $alias, $o_prefix, $thisDFields, $thisSubQ, $thisCondition);
				$thisJoinTable	= $joinType.'('.
						$thisDTable.
						') AS '.$alias.' ON('.$thisON.')';


				// ---------------------------- MERGE ALL FIELDS
				$oFields		= array_merge($oFields, $o_fields);
				$iFields		= array_merge($iFields, $i_fields);
				$iFieldsBottom[]= $prev_id;				// prev_id to the end

				// ---------------------------- ADD JOINS (Level 3)
				$joins[]	= $thisJoinTable;




				// Check for recursive Joining
				if ( ($recursive > 1 &&	isset($properties['recursive']) && $properties['recursive']) ||
						($recursive > 2) // force
				)
				{
					$class	= isset($properties['class']) ? $properties['class'] : Strings::camelCase($properties['table'], true);
					$core	= isset($properties['core'])  ? $properties['core']  : false;

					if ($core)
					{
						$oTable = Loader::loadCoreTable($class);
					}
					else
					{
						$plugin	= isset($properties['plugin'])? $properties['plugin']: null;
						$oTable = $plugin ? Loader::loadPluginTable($class, $plugin) : Loader::loadTable($class);
					}

					// do not limit recursion
					if ( $properties['recursive'] === true || $recursive > 2 )
					{
						// recurse all relations once, and set recursion to false, so that we don't loop any deeper
						$oTable->_buildHasOneQuery(null, $oFields, $iFields, $iFieldsBottom, $joins, $row_num_cond, 0, $alias);
						$oTable->_buildHasManyQuery(null, $oFields, $iFields, $iFieldsBottom, $joins, $order, $limit, $row_num_cond, 0, $alias);
						$oTable->_buildBelongsToQuery(null, $oFields, $iFields, $iFieldsBottom, $joins, $row_num_cond, 0, $alias);
					}
					else
					{
						if ( isset($properties['recursive']['hasOne']) ) {
							$oTable->_buildHasOneQuery($properties['recursive']['hasOne'], $oFields, $iFields, $iFieldsBottom, $joins, $row_num_cond, 0, $alias);
						}
						if ( isset($properties['recursive']['hasMany']) ) {
							$oTable->_buildHasManyQuery($properties['recursive']['hasMany'], $oFields, $iFields, $iFieldsBottom, $joins, $order, $limit, $row_num_cond, 0, $alias);
						}
						if ( isset($properties['recursive']['belongsTo']) ) {
							$oTable->_buildBelongsToQuery($properties['recursive']['belongsTo'], $oFields, $iFields, $iFieldsBottom, $joins, $row_num_cond, 0, $alias);
						}
					}
				}
			}
		}
	}



	private function _buildHasManyQuery($limitAliase = false, &$oFields, &$iFields, &$iFieldsBottom, &$joins, &$order, &$limit, $parent_row_num_cond, $recursive, $prefix = '')
	{
		$joinType	= 'LEFT JOIN';
		$relType	= 'many';

		foreach ( $this->hasMany as $alias => $properties )
		{
			// This is used for the recursive relations, so that we can limit what to follow
			if ( !$limitAliase || in_array($alias, $limitAliase) )
			{
				// Associations
				$mainPK			= $this->alias.'.'.$this->primary_key;
				$thisPK			= isset($properties['primaryKey']) ? $properties['primaryKey'] : 'id';
				$thisAliasedPK	= $alias.'.'.$thisPK;
				$thisFK			= $properties['foreignKey'];
				$thisAliasedFK	= $alias.'.'.$thisFK;
				$thisON			= $mainPK.'='.$thisAliasedFK;

				// Fields
				$thisFields		= $properties['fields'];

				// SubQueries
				$thisSubQ		= isset($properties['subQueries'])	? $properties['subQueries'] : array();

				// Conditions and Order
				$thisCondition	= isset($properties['condition'])	? $properties['condition']	: null;
				$thisOrder		= isset($properties['order'])		? $properties['order']		: array();


				// Table Data
				$thisTable		= $properties['table'];


				// FIELD PREFIXE
				$o_prefix		= $prefix ? $prefix.'_' : '';	// outer prefix
				$i_prefix		= $prefix ? $prefix.'.' : '';	// inner prefix
				$a_prefix		= $prefix ? $prefix.'.' : '';	// aliased prefix


				// ---------------------------- ORDER FIELDS (Level 2 and 3)
				// We have to add the order fields to level 3 and level 2, otherwise it will be unknown and we cannot order by it
				//$orderFields	= array_keys($thisOrder);

				// ---------------------------- OUTER FIELDS (Level 1)
				$o_row_name		= $o_prefix.$alias.'_'.ROW_COUNT;
				$o_pk_name		= $o_prefix.$alias.'_'.$thisPK;
				$o_rel_name		= "'".$relType."'";

				$o_row_alias	= $a_prefix.$alias.'.'.ROW_COUNT;
				$o_pk_alias		= $a_prefix.$alias.'.'.PRIM_KEY;
				$o_rel_alias	= $a_prefix.$alias.'.'.REL_TYPE;

				$o_row			= $o_row_name.' AS `'. $o_row_alias.'`';	// Row Count
				$o_pk			= $o_pk_name. ' AS `'. $o_pk_alias. '`';	// Primary Key
				$o_type			= $o_rel_name.' AS `'.$o_rel_alias. '`';	// Relation Type X->One

				$o_fields		= $this->buildFields($thisFields, $o_prefix.$alias, $i_prefix.$alias, '_', '.');// <alias>_<field> AS `<alias>.<field>`
				array_unshift($o_fields, $o_type);			// relation type to the beginning
				array_unshift($o_fields, $o_row);			// row_count alias to the beginning
				array_unshift($o_fields, $o_pk);			// _PRIM_KEY_ to the very beginning



				// ---------------------------- INNER FIELDS (Level 2)
				$prev_id_name	= '@'.$o_prefix.$alias.PREV_ID;
				$row_count_name	= '@'.$o_prefix.$alias.ROW_COUNT;

				$row_num_cond	= $prev_id_name.' = '.$thisAliasedPK;
				$row_num		= $row_count_name.' :=  IF('.$row_num_cond.', '.$row_count_name.', IF('.$parent_row_num_cond.', '.$row_count_name.' +1, 1)) AS `'.$o_prefix.$alias.'_'.ROW_COUNT.'`';
				$prev_id		= $prev_id_name.' := '.$thisAliasedPK;

				// also add order fields to level 2
				//$i_fields		= array_merge($thisFields, array_keys($thisOrder));
				$i_fields		= array_unique($thisFields);

				$i_fields		= $this->buildFields($i_fields, $i_prefix.$alias, $o_prefix.$alias, '.', '_');// <alias>.<field> AS `<alias>_<field>`
				array_unshift($i_fields, $row_num);		// row_number to the beginning


				// ---------------------------- SUBQUERIES (Level 1,2,3)
				$thisSubQ		= $this->buildSubQueries($thisSubQ, $i_fields, $o_fields, $i_prefix, $o_prefix, $alias);


				// ---------------------------- JOIN FIELDS (Level 3)
				// make sure to add all fields that are required by order, where and join on(...)
				$thisDFields	= array_merge($thisFields, array($thisFK, $thisPK));
				//$thisDFields	= array_merge($thisDFields, $orderFields);
				$thisDFields	= array_unique($thisDFields);

				$thisDTable		= $this->buildDerivedTableQueryWithRowCount($thisTable, $alias, $o_prefix, $thisDFields, $thisSubQ, $thisCondition, $thisOrder);
				$thisJoinTable	= $joinType.'('.
						$thisDTable.
						') AS '.$alias.' ON('.$thisON.')';


				// ---------------------------- MERGE ALL FIELDS
				$oFields		= array_merge($oFields, $o_fields);
				$iFields		= array_merge($iFields, $i_fields);
				$iFieldsBottom[]= $prev_id;				// prev_id to the end

				// ---------------------------- ADD JOINS (Level 3)
				$joins[]	= $thisJoinTable;


				// ---------------------------- MERGE ORDER
				// merge parent order
				if ($thisOrder) {
					foreach ($thisOrder as $key => $dir) {
						$order[$o_prefix.$alias.'_'.$key] = $dir;
					}
				}


				// ---------------------------- ADD LIMIT to outer WHERE (limit by rowcount) (Level 1)
				if ( isset($properties['limit']) ) {
					if (!is_array($limit)) {
						$limit = array();	// initialize
					}
					$limit[$o_row_name] = $properties['limit'];
				}
				else if ( isset($properties['range']) ) {
					if (!is_array($limit)) {
						$limit = array();	// initialize
					}
					$limit[$o_row_name] = $properties['range'];
				}


				// Check for recursive Joining
				if ( ($recursive > 1 &&	isset($properties['recursive']) && $properties['recursive']) ||
					 ($recursive > 2) // force
				)
				{
					$class	= isset($properties['class']) ? $properties['class'] : Strings::camelCase($properties['table'], true);
					$core	= isset($properties['core'])  ? $properties['core']  : false;

					if ($core)
					{
						$oTable = Loader::loadCoreTable($class);
					}
					else
					{
						$plugin	= isset($properties['plugin'])? $properties['plugin']: null;
						$oTable = $plugin ? Loader::loadPluginTable($class, $plugin) : Loader::loadTable($class);
					}

									// do not limit recursion
					if ( $properties['recursive'] === true || $recursive > 2 )
					{
						// recurse all relations once, and set recursion to false, so that we don't loop any deeper
						$oTable->_buildHasOneQuery(null, $oFields, $iFields, $iFieldsBottom, $joins, $row_num_cond, 0, $alias);
						$oTable->_buildHasManyQuery(null, $oFields, $iFields, $iFieldsBottom, $joins, $order, $limit, $row_num_cond, 0, $alias);
						$oTable->_buildBelongsToQuery(null, $oFields, $iFields, $iFieldsBottom, $joins, $row_num_cond, 0, $alias);
					}
					else
					{
						if ( isset($properties['recursive']['hasOne']) ) {
							$oTable->_buildHasOneQuery($properties['recursive']['hasOne'], $oFields, $iFields, $iFieldsBottom, $joins, $row_num_cond, 0, $alias);
						}
						if ( isset($properties['recursive']['hasMany']) ) {
							$oTable->_buildHasManyQuery($properties['recursive']['hasMany'], $oFields, $iFields, $iFieldsBottom, $joins, $order, $limit, $row_num_cond, 0, $alias);
						}
						if ( isset($properties['recursive']['belongsTo']) ) {
							$oTable->_buildBelongsToQuery($properties['recursive']['belongsTo'], $oFields, $iFields, $iFieldsBottom, $joins, $row_num_cond, 0, $alias);
						}
					}
				}
			}
		}
	}

	// prefix is used for inner calls to append prefix to alias: Users.Thread.id
	private function _buildBelongsToQuery($limitAliase = false, &$oFields, &$iFields, &$iFieldsBottom, &$joins, $parent_row_num_cond, $recursive, $prefix = '')
	{
		$joinType	= 'JOIN';
		$relType	= 'one';

		foreach ( $this->belongsTo as $alias => $properties )
		{
			// This is used for the recursive relations, so that we can limit what to follow
			if ( !$limitAliase || in_array($alias, $limitAliase) )
			{
				// Associations
				$mainPK			= $this->alias.'.'.$this->primary_key;
				$mainAliasedFK	= $this->alias.'.'.$properties['foreignKey'];
				$thisPK			= isset($properties['primaryKey']) ? $properties['primaryKey'] : 'id';
				$thisAliasedPK	= $alias.'.'.$thisPK;
				$thisFK			= $properties['foreignKey'];
				$thisAliasedFK	= $alias.'.'.$thisFK;
				$thisON			= $mainAliasedFK.'='.$thisAliasedPK;

				// Fields
				$thisFields		= $properties['fields'];

				// SubQueries
				$thisSubQ		= isset($properties['subQueries'])	? $properties['subQueries'] : array();

				// Conditions and Order
				$thisCondition	= isset($properties['condition'])	? $properties['condition']	: null;
				$thisOrder		= isset($properties['order'])		? $properties['order']		: null;

				// Table Data
				$thisTable		= $properties['table'];


				// FIELD PREFIXE
				$o_prefix		= $prefix ? $prefix.'_' : '';	// outer prefix
				$i_prefix		= $prefix ? $prefix.'.' : '';	// inner prefix
				$a_prefix		= $prefix ? $prefix.'.' : '';	// aliased prefix


				// ---------------------------- OUTER FIELDS (Level 1)
				$o_row_name		= $o_prefix.$alias.'_'.ROW_COUNT;
				$o_pk_name		= $o_prefix.$alias.'_'.$thisPK;
				$o_rel_name		= "'".$relType."'";

				$o_row_alias	= $a_prefix.$alias.'.'.ROW_COUNT;
				$o_pk_alias		= $a_prefix.$alias.'.'.PRIM_KEY;
				$o_rel_alias	= $a_prefix.$alias.'.'.REL_TYPE;

				$o_row			= $o_row_name.' AS `'. $o_row_alias.'`';	// Row Count
				$o_pk			= $o_pk_name. ' AS `'. $o_pk_alias. '`';	// Primary Key
				$o_type			= $o_rel_name.' AS `'.$o_rel_alias. '`';	// Relation Type X->One

				$o_fields		= $this->buildFields($thisFields, $o_prefix.$alias, $i_prefix.$alias, '_', '.');// <alias>_<field> AS `<alias>.<field>`
				array_unshift($o_fields, $o_type);			// relation type to the beginning
				array_unshift($o_fields, $o_row);			// row_count alias to the beginning
				array_unshift($o_fields, $o_pk);			// _PRIM_KEY_ to the very beginning



				// ---------------------------- INNER FIELDS (Level 2)
				$prev_id_name	= '@'.$o_prefix.$alias.PREV_ID;
				$row_count_name	= '@'.$o_prefix.$alias.ROW_COUNT;

				$row_num_cond	= $prev_id_name.' = '.$thisAliasedPK;
				$row_num		= $row_count_name.' :=  IF('.$row_num_cond.', '.$row_count_name.', IF('.$parent_row_num_cond.', '.$row_count_name.' +1, 1)) AS `'.$o_prefix.$alias.'_'.ROW_COUNT.'`';
				$prev_id		= $prev_id_name.' := '.$thisAliasedPK;

				$i_fields		= $this->buildFields($thisFields, $i_prefix.$alias, $o_prefix.$alias, '.', '_');// <alias>.<field> AS `<alias>_<field>`
				array_unshift($i_fields, $row_num);		// row_number to the beginning


				// ---------------------------- SUBQUERIES (Level 1,2,3)
				$thisSubQ		= $this->buildSubQueries($thisSubQ, $i_fields, $o_fields, $i_prefix, $o_prefix, $alias);


				// ---------------------------- JOIN FIELDS (Level 3)
				// make sure to add all fields that are required by order, where and join on(...)
				$thisDFields	= $thisFields;
				// Do not add this in belongsTo, as the FK is
				// the one of the other table
				/* if ( !in_array($thisFK, $thisDFields) ) {
					$thisDFields[]	= $thisFK;
				}*/
				if ( !in_array($thisPK, $thisDFields) ) {
					$thisDFields[]	= $thisPK;
				}
				$thisDTable		= $this->buildDerivedTableQueryWithRowCount($thisTable, $alias, $o_prefix, $thisDFields, $thisSubQ, $thisCondition, $thisOrder);
				$thisJoinTable	= $joinType.'('.
						$thisDTable.
						') AS '.$alias.' ON('.$thisON.')';


				// ---------------------------- MERGE ALL FIELDS
				$oFields		= array_merge($oFields, $o_fields);
				$iFields		= array_merge($iFields, $i_fields);
				$iFieldsBottom[]= $prev_id;				// prev_id to the end

				// ---------------------------- ADD JOINS (Level 3)
				$joins[]	= $thisJoinTable;


				// Check for recursive Joining
				if ( ($recursive > 1 &&	isset($properties['recursive']) && $properties['recursive']) ||
						($recursive > 2) // force
				)
				{
					$class	= isset($properties['class']) ? $properties['class'] : Strings::camelCase($properties['table'], true);
					$core	= isset($properties['core'])  ? $properties['core']  : false;

					if ($core)
					{
						$oTable = Loader::loadCoreTable($class);
					}
					else
					{
						$plugin	= isset($properties['plugin'])? $properties['plugin']: null;
						$oTable = $plugin ? Loader::loadPluginTable($class, $plugin) : Loader::loadTable($class);
					}

					// do not limit recursion
					if ( $properties['recursive'] === true || $recursive > 2 )
					{
						// recurse all relations once, and set recursion to false, so that we don't loop any deeper
						$oTable->_buildHasOneQuery(null, $oFields, $iFields, $iFieldsBottom, $joins, $row_num_cond, 0, $alias);
						$oTable->_buildHasManyQuery(null, $oFields, $iFields, $iFieldsBottom, $joins, $order, $limit, $row_num_cond, 0, $alias);
						$oTable->_buildBelongsToQuery(null, $oFields, $iFields, $iFieldsBottom, $joins, $row_num_cond, 0, $alias);
					}
					else
					{
						if ( isset($properties['recursive']['hasOne']) ) {
							$oTable->_buildHasOneQuery($properties['recursive']['hasOne'], $oFields, $iFields, $iFieldsBottom, $joins, $row_num_cond, 0, $alias);
						}
						if ( isset($properties['recursive']['hasMany']) ) {
							$oTable->_buildHasManyQuery($properties['recursive']['hasMany'], $oFields, $iFields, $iFieldsBottom, $joins, $order, $limit, $row_num_cond, 0, $alias);
						}
						if ( isset($properties['recursive']['belongsTo']) ) {
							$oTable->_buildBelongsToQuery($properties['recursive']['belongsTo'], $oFields, $iFields, $iFieldsBottom, $joins, $row_num_cond, 0, $alias);
						}
					}
				}
			}
		}
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
}
