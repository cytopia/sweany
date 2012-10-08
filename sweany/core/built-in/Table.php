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
	 *	$hasOne		One-to-One Relation (fetches one row)
	 *
	 *	@param mixed[]	= Array(
	 *		'<alias>' => array(							# SQL Table alias to use (will also be the name of the array|object after fetching)
	 *			'table'			=> '<table_name>',		# string:	Name of the sql table
	 *			'class'			=> '<class_name>',		# string:	Defaults from <sql_table_name> to <SqlTableName>Table (underscore -> camelcase)
	 *			'core'			=> true|false			# boolean:	True if this table file is in sweany/core/built-in/tables
	 *			'plugin'		=> '<plugin_name>'		# string:	Name of the plugin table (does not work with 'core' => true)
	 *
	 *			'primaryKey'	=> 'id',				# string:	Primary key of the hasOne table (<table_name>) (defaults to: 'id')
	 *			'foreignKey'	=> '<foreign_key>',		# string:	Foreign key of the hasOne table (<table_name>) (defaults to: 'fk_<$this->table>_id')
	 *
	 *			'fields'		=> array(),				# mixed[]:	Array of fields and/or alias-field pairs to fetch
	 *														'fields' => array(
	 *															'id', 'name', '<alias1>' => '<field1>'
	 *														)
	 *			'subQueries'	=> array(),				# mixed[]:	Associative Array of subqueries to append
	 *														'subQueries' => array(
	 *															'id' => 'SELECT id FROM <table> WHERE <condition> LIMIT 1'
	 *														)
	 *			'condition'		=> '<condition>',		# string:	Conditions
	 *
	 *			'recursive'		=> false|true,			# boolean:	true: Also load the depending table with all its relations | false: only load this relation
	 *			'recursive'		=> array()				# mixed[]:	Only follow the relations specified here
	 *														'recursive' => array(
	 *															'hasMany'	=> array('Alias1', 'Alias2'),
	 *															'belongsTo'	=> array('Alias1')
	 *
	 *			'dependent'		=> false,				# boolean:	true: Also call the delete function of this hasOne relation on deleting
	 *															Note: the delete function can be overwritten in each corresponding table
	 *																  setting a row to is_deleted=1 for example
	 *		),
	 *	);
	 */
	public $hasOne			= array();



	/**
	 *	$hasMany	One-to-Many Relation (fetches many rows)
	 *
	 *	@param mixed[]	= Array(
	 *		'<alias>' => array(							# SQL Table alias to use (will also be the name of the array|object after fetching)
	 *			'table'			=> '<table_name>',		# string:	Name of the sql table
	 *			'class'			=> '<class_name>',		# string:	Defaults from <sql_table_name> to <SqlTableName>Table (underscore -> camelcase)
	 *			'core'			=> true|false			# boolean:	True if this table file is in sweany/core/built-in/tables
	 *			'plugin'		=> '<plugin_name>'		# string:	Name of the plugin table (does not work with 'core' => true)
	 *
	 *			'primaryKey'	=> 'id',				# string:	Primary key of the hasMany table (<table_name>) (defaults to: 'id')
	 *			'foreignKey'	=> '<foreign_key>',		# string:	Foreign key of the hasMany table (<table_name>) (defaults to: 'fk_<$this->table>_id')
	 *
	 *			'fields'		=> array(),				# mixed[]:	Array of fields and/or alias-field pairs to fetch
	 *														'fields' => array(
	 *															'id', 'name', '<alias1>' => '<field1>'
	 *														)
	 *			'subQueries'	=> array(),				# mixed[]:	Associative Array of subqueries to append
	 *														'subQueries' => array(
	 *															'id' => 'SELECT id FROM <table> WHERE <condition> LIMIT 1'
	 *														)
	 *			'condition'		=> '<condition>',		# string:	Conditions
	 *			'order'			=> array()				# mixed[]:	Associative array of order clauses
	 *			'limit'			=> int					# integer:	Limit by X rows
	 *
	 *			'recursive'		=> false|true,			# boolean:	true: Also load the depending table with all its relations | false: only load this relation
	 *			'recursive'		=> array()				# mixed[]:	Only follow the relations specified here
	 *														'recursive' => array(
	 *															'hasMany'	=> array('Alias1', 'Alias2'),
	 *															'belongsTo'	=> array('Alias1')
	 *														),
	 *
	 *			'dependent'		=> false,				# boolean:	true: Also call the delete function of this hasMany relation on deleting
	 *															Note: the delete function can be overwritten in each corresponding table
	 *																  setting a row to is_deleted=1 for example
	 *		),
	 *	);
	 */
	public $hasMany			= array();



	/**
	 *
	 *	$belongsTo	Many-to-One Relation (fetches one row)
	 *
	 *	@param	Array
	 *	(
	 *		'<alias>' => array(							# SQL Table alias to use (will also be the name of the array|object after fetching)
	 *			'table'			=> '<table_name>',		# string:	Name of the sql table
	 *			'class'			=> '<class_name>',		# string:	Defaults from <sql_table_name> to <SqlTableName>Table (underscore -> camelcase)
	 *			'core'			=> true|false			# boolean:	True if this table file is in sweany/core/built-in/tables
	 *			'plugin'		=> '<plugin_name>'		# string:	Name of the plugin table (does not work with 'core' => true)
	 *
	 *			'primaryKey'	=> 'id',				# string:	Primary key of the belongsTi table (<table_name>) (defaults to: 'id')
	 *			'foreignKey'	=> '<foreign_key>',		# string:	Foreign key in $this->table (defaults to: 'fk_<table_name>_id')
	 *
	 *			'fields'		=> array(),				# mixed[]:	Array of fields and/or alias-field pairs to fetch
	 *														'fields' => array(
	 *															'id', 'name', '<alias1>' => '<field1>'
	 *														)
	 *			'subQueries'	=> array(),				# mixed[]:	Associative Array of subqueries to append
	 *														'subQueries' => array(
	 *															'id' => 'SELECT id FROM <table> WHERE <condition> LIMIT 1'
	 *														)
	 *			'condition'		=> '<condition>',		# string:	Conditions
	 *
	 *			'recursive'		=> false|true,			# boolean:	true: Also load the depending table with all its relations | false: only load this relation
	 *			'recursive'		=> array()				# mixed[]:	Only follow the relations specified here
	 *														'recursive' => array(
	 *															'hasMany'	=> array('Alias1', 'Alias2'),
	 *															'belongsTo'	=> array('Alias1')
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
	public function loadMany($ids, $fields = null, $subQueries = null, $order = null, $recursive = 1, $return = 'object')
	{
		$fields		= ( is_array($fields) && count($fields) ) ? $fields : $this->fields;
		$subQueries	= ( is_array($subQueries) && count($subQueries) ) ? $subQueries : $this->subQueries;

		if ( count($ids) == 1 ) {
			// Escape input
			$condition = $this->prepare(array($this->alias.'.'.$this->primary_key.' = :id', array(':id' => $ids[0])));
		} else {
			// Escape input
			$ids = implode(', ', array_map(function($id){ return $this->db->escape($id); }, $ids));
			$condition = $this->alias.'.'.$this->primary_key.' IN ('.$ids.')';
		}
		$order		= ($order) ? $order : ($this->order ? $this->order : $this->alias.'.'.$this->primary_key);

		$query		= $this->buildQuery($fields, $subQueries, $condition, $order, null, $recursive, null, null);
		$data		= $this->retrieveResults($query, $recursive, null, $return);
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
	 *		recursive	=> 0-3	Overwrite: recursion level for relations
	 *			'recursive'	=> 0				# Flat - no relations, only this table
	 *			'recursive'	=> 1				# With all relations (hasOne, hasMany, belongsTo, hasAndBelongsToMany)
	 *			'recursive'	=> 2				# With all relations and follow recursion (one level) specified by child relation
	 *			'recursive'	=> 3				# With all relations and force recursion (one level) on all child relations
	 *		relation		Limit relations to follow (on this level)
	 *			'relation' => array(
	 *				'hasMany' => array('Edit', 'Post')	# Only follow Edit and Post in hasMany relation (and their respective recursion settings)
	 *			)
	 *
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
		$relation	= isset($options['relation'])	? $options['relation']	: null;	// only follow specific relations?

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
		$query		= $this->buildQuery($fields, $subQueries, $condition, $order, $limit, $recursive, $relation, null);
		$data		= $this->retrieveResults($query, $recursive, $relation, $return);

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
		// TODO: remove fields, that do not belong here
		// via $this->fields
		$fields = $this->_appendCreatedFieldIfExist($fields);
		$ret	= $this->db->insert($this->table, $fields, (($return) ? true : false));

		switch ($return)
		{
			// return non-recursive row
			case 2:
				return $this->load($ret, null, null, 0);

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
			case 2:		return $this->load($id, null, null, 0);
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
		return $this->db->incrementFields($this->table, $fields, null, $condition, $return);
	}

	// TODO: return (array)values on $return = 2
	public function incrementAll($condition, $fields, $return = 0)
	{
		return $this->db->incrementFields($this->table, $fields, null, $this->prepare($condition), $return);
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








	private function buildAliasedSubQueries($subQueries, $tbl_alias, $alias_prefix)
	{
		$build = function($alias, $query) use ($tbl_alias, $alias_prefix) {
			return '('.$query.') AS `'.$alias_prefix.$tbl_alias.'.'.$alias.'`';
		};
		return array_map($build, array_keys($subQueries), array_values($subQueries));
	}


	private function buildAliasedFields($tbl_fields, $tbl_alias, $field_prefix, $alias_prefix)
	{
		$build = function($alias, $field) use($tbl_alias, $field_prefix, $alias_prefix) {
			// numerical array (no alias)		// AS [<parent_tbl_alias>.]<tbl_alias>.<field_name>
			if ( is_integer($alias) ) {
				return $field_prefix.$tbl_alias.'.'.$field.' AS `'.$alias_prefix.$tbl_alias.'.'.$field.'`';
			}
			// associative array (has alias)	// AS [<parent_tbl_alias>.]<tbl_alias>.<field_alias>
			else {
				return $field_prefix.$tbl_alias.'.'.$field.' AS `'.$alias_prefix.$tbl_alias.'.'.$alias.'`';
			}
		};
		return array_map($build, array_keys($tbl_fields), array_values($tbl_fields));
	}



	/**
	 * buildQuery
	 *
	 * @param mixed[]			$fields
	 * @param mixed[]			$subQueries
	 * @param string			$where
	 * @param mixed[]			$order
	 * @param integer			$limit
	 * @param integer			$recursive
	 *	'recursive':	 0						# Flat - no relations, only this table
	 *	'recursive':	 1						# With all relations (hasOne, hasMany, belongsTo, hasAndBelongsToMany)
	 *	'recursive':	 2						# With all relations and follow recursion (one level) specified by child relation
	 *	'recursive':	 3						# With all relations and force recursion (one level) on all child relations
	 * @param mixed[]			$relation		# Follow only specific relations (on this level)
	 *	'$relation':	 array(
	 *		'hasMany' => array('Edit', 'Post')	# Only follow Edit and Post in hasMany relation (plus THEIR respective recursion settings)
	 *	)
	 * @param object			$tblClass
	 * @return string
	 */
	private function buildQuery($fields, $subQueries, $where = null, $order = null, $limit = null, $recursive = 1, $relation = null, $tblClass = null)
	{
		// Array holding all fields to be selected
		$allFields	= array();

		// ------------------------------------ MAIN QUERY ------------------------------------

		$aliasedPK	= $this->alias.'.'.$this->primary_key;		// Primary key identifier
		// Get main query fields
		$fields		= $this->buildAliasedFields($fields, $this->alias, '', '');
		$fields 	= array_merge(array($aliasedPK.' AS `'.PRIM_KEY.'`'), $fields);


		// Get main query subqueries
		$subQueries = $this->buildAliasedSubQueries($subQueries, $this->alias, '');

		// Add fields and subqueries to the fields to be selected
		$allFields	= array_merge($fields, $subQueries);

		$where		= ($where) ? 'WHERE '	.$where : '';
		$order		= ($order) ? 'ORDER BY '.implode(',', array_map(create_function('$key, $dir', 'return $key." ".$dir;'), array_keys($order), array_values($order))) : '';
		$limit		= ($limit) ? 'LIMIT '	.$limit : '';


		// ------------------------------------ JOINS(s) ------------------------------------

		$joins	= array();
		if ( $recursive > 0)
		{
			// We only join the X->one relations here
			// All X->many have to be done in a for-loop, otherwise we will have no limit available at them

			// Add hasOne (one-to-one)
			if ($tblClass) {
				$tblClass->_buildHasOneQuery($relation, $allFields, $joins, $recursive, '');
			} else {
				$this->_buildHasOneQuery($relation, $allFields, $joins, $recursive, '');
			}

			// Add belongsTo (many-to-one)
			if ($tblClass) {
				$tblClass->_buildBelongsToQuery($relation, $allFields, $joins, $recursive, '');
			} else {
				$this->_buildBelongsToQuery($relation, $allFields, $joins,  $recursive, '');
			}
		}


		$query =
			'SELECT '.
				implode(', ', $allFields).' '.		// OUTER FIELDS: <alias>_<field> AS <alias>.<field>
			'FROM '.
				$this->table.' AS '.$this->alias.' '.
			implode('', $joins).' '.				// JOIN(s)
			$where.' '.
			//'%s '.		// GROUP
			//'%s '.		// HAVING
			$order.' '.
			$limit;

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
	private function retrieveResults($query, $recursive = 1, $relation = null, $return = 'object')
	{
		$count		= 0;

		$getArray = function($row, &$data) use (&$count, $relation, $recursive) {

			$many	= array();

			foreach ($row as $field => $value)
			{
				$part	= explode('.', $field);
				$size	= count($part);

				// --------------------------------------- Primary Key ---------------------------------------
				if ($size == 1 && $recursive )
				{
					if ( $recursive>0  || (is_array($relation) && in_array('hasMany', array_keys($relation))) )
					{
						$pk		= $value;
						$many	= $this->_retrieveHasMany($relation, $pk, $recursive);
					}
				}
				// --------------------------------------- Flat Entries ---------------------------------------
				else if ( $size == 2 )
				{
					$alias1 = $part[0];
					$field	= $part[1];
					if ( $field != PRIM_KEY ) {
						$data[$count][$alias1][$field] = $value;
					}
					else {
						//if ( in_array($alias1, $hasMany) ){}	// We have found a hasMany relation here
						// TODO:!! do we need to load something here?
					}
				}
				// --------------------------------------- Recursive Entries ---------------------------------------
				if ( $size == 3 )
				{
					$alias1 = $part[0];
					$alias2 = $part[1];
					$field	= $part[2];
					if ( $field != PRIM_KEY ) {
						$data[$count][$alias1][$alias2][$field] = $value;
					}
					else {
						// check for recursion here
						//if ( in_array($alias1, $hasMany) ){}	// We have found a hasMany relation here
						// TODO:!! do we need to load something here?
					}
				}
			}
			$data[$count] = array_merge($data[$count], $many);
			$count++;
		};



		$getObject = function($row, &$data) use (&$count, $relation, $recursive) {

			$many	= array();

			foreach ($row as $field => $value)
			{
				$part	= explode('.', $field);
				$size	= count($part);

				// --------------------------------------- Primary Key ---------------------------------------
				if ($size == 1 && $recursive)
				{

					if ( $recursive>0 || (is_array($relation) && in_array('hasMany', array_keys($relation))) )
					{
						$pk		= $value;
						$many	= $this->_retrieveHasMany($relation, $pk, $recursive);
					}
				}
				// --------------------------------------- Flat Entries ---------------------------------------
				else if ( $size == 2 )
				{
					$alias1 = $part[0];
					$field	= $part[1];
					if ( $field == PRIM_KEY ) {
						//if ( in_array($alias1, $hasMany) ){}	// We have found a hasMany relation here
						// TODO:!! do we need to load something here?

					} else {
						if ( !isset($data[$count]) ) {
							$data[$count] = new stdClass();
						}
						if ( !isset($data[$count]->$alias1 ) ) {
							$data[$count]->$alias1 = new stdClass();
						}
						$data[$count]->$alias1->$field = $value;
					}
				}
				// --------------------------------------- Recursive Entries ---------------------------------------
				else if ( $size == 3 )
				{
					$alias1 = $part[0];
					$alias2 = $part[1];
					$field	= $part[2];
					if ( $field == PRIM_KEY ) {
						// check for recursion here
						//if ( in_array($alias1, $hasMany) ){}	// We have found a hasMany relation here
						// TODO:!! do we need to load something here?
						//$data[$count]->$alias1 = new stdClass();
					}
					else {
						if ( !isset($data[$count]->$alias1->$alias2) ) {
							$data[$count]->$alias1->$alias2 = new stdClass();
						}
						$data[$count]->$alias1->$alias2->$field = $value;
					}
				}
			}
			$data[$count] = (object)array_merge((array)$data[$count], (array)$many);
			$count++;
		};

		if ( $return == 'array' )
		{
			$return = $this->db->select($query, $getArray);
		}
		else
		{
			$return = $this->db->select($query, $getObject);
		}
		return $return;
	}




	/* ************************************************************************************************************************** *
	 *
	 *	P R I V A T E   P R I V A T E   C L A S S   H E L P E R
	 *
	 * ************************************************************************************************************************** */


	private function _buildHasOneQuery($limitAliase = false, &$allFields, &$joins, $recursive, $prefix)
	{
		$joinType	= 'LEFT JOIN';

		foreach ( $this->hasOne as $alias => $properties )
		{
			// This is used for the recursive relations, so that we can limit what to follow
			if ( !$limitAliase || ( isset($limitAliase['hasOne']) && in_array($alias, $limitAliase['hasOne']) ) )
			{
				// Associations
				$mainAliasedPK	= $this->alias.'.'.$this->primary_key;
				$thisPK			= isset($properties['primaryKey']) ? $properties['primaryKey'] : 'id';
				$thisAliasedPK	= $alias.'.'.$thisPK;
				$thisFK			= $properties['foreignKey'];
				$thisAliasedFK	= $alias.'.'.$thisFK;
				$thisON			= $mainAliasedPK.'='.$thisAliasedFK;

				// Table Data
				$thisTable		= $properties['table'];

				// Fields
				$thisPKField	= array(PRIM_KEY => $thisPK);
				$thisFields		= $properties['fields'];
				$thisFields		= array_merge($thisPKField, $thisFields);
				$thisFields		= $this->buildAliasedFields($thisFields, $alias, '', $prefix);

				// SubQueries
				$thisSubQ		= isset($properties['subQueries'])	? $properties['subQueries'] : array();
				$thisSubQ		= $this->buildAliasedSubQueries($thisSubQ, $alias, $prefix);

				// Conditions
				$thisCondition	= isset($properties['condition'])	? $properties['condition']	: null;

				$joinCondition	= ($thisCondition) ? array($thisON, $thisCondition) : array($thisON);
				$joinCondition	= implode(' AND ', $joinCondition);

				// JOIN
				$thisJoin		= $joinType.' '.$thisTable.' AS '.$alias.' ON('.$joinCondition.')';


				// Fill referenced function variables
				$allFields		= array_merge($allFields, $thisFields, $thisSubQ);
				$joins[]		= $thisJoin;


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
						$oTable->_buildHasOneQuery(null, $allFields, $joins, 0, $alias.'.');
						$oTable->_buildBelongsToQuery(null, $allFields, $joins, 0, $alias.'.');
					}
					else
					{
						if ( isset($properties['recursive']['hasOne']) ) {
							$oTable->_buildHasOneQuery($properties['recursive']['hasOne'], $allFields, $joins, 0, $alias.'.');
						}
						if ( isset($properties['recursive']['belongsTo']) ) {
							$oTable->_buildBelongsToQuery($properties['recursive']['belongsTo'], $allFields, $joins, 0, $alias.'.');
						}
					}
				}
			}
		}
	}


	// prefix is used for inner calls to append prefix to alias: Users.Thread.id
	private function _buildBelongsToQuery($limitAliase = false, &$allFields, &$joins, $recursive, $prefix)
	{
		$joinType	= 'JOIN';	// maybe LEFT JOIN if the belongint entity does not exist...

		foreach ( $this->belongsTo as $alias => $properties )
		{
			// This is used for the recursive relations, so that we can limit what to follow
			if ( !$limitAliase || ( isset($limitAliase['belongsTo']) && in_array($alias, $limitAliase['belongsTo']) ) )
			{
				// Associations
				$mainAliasedPK	= $this->alias.'.'.$this->primary_key;
				$mainAliasedFK	= $this->alias.'.'.$properties['foreignKey'];
				$thisPK			= isset($properties['primaryKey']) ? $properties['primaryKey'] : 'id';
				$thisAliasedPK	= $alias.'.'.$thisPK;
				$thisFK			= $properties['foreignKey'];
				$thisAliasedFK	= $alias.'.'.$thisFK;
				$thisON			= $mainAliasedFK.'='.$thisAliasedPK;

							// Table Data
				$thisTable		= $properties['table'];

				// Fields
				$thisPKField	= array(PRIM_KEY => $thisPK);
				$thisFields		= $properties['fields'];
				$thisFields		= array_merge($thisPKField, $thisFields);
				$thisFields		= $this->buildAliasedFields($thisFields, $alias, '', $prefix);

				// SubQueries
				$thisSubQ		= isset($properties['subQueries'])	? $properties['subQueries'] : array();
				$thisSubQ		= $this->buildAliasedSubQueries($thisSubQ, $alias, $prefix);

				// Conditions
				$thisCondition	= isset($properties['condition'])	? $properties['condition']	: null;

				$joinCondition	= ($thisCondition) ? array($thisON, $thisCondition) : array($thisON);
				$joinCondition	= implode(' AND ', $joinCondition);

				// JOIN
				$thisJoin		= $joinType.' '.$thisTable.' AS '.$alias.' ON('.$joinCondition.')';


				// Fill referenced function variables
				$allFields		= array_merge($allFields, $thisFields, $thisSubQ);
				$joins[]		= $thisJoin;

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
					if ( (isset($properties['recursive']) && $properties['recursive'] === true) || $recursive > 2 )
					{
						// recurse all relations once, and set recursion to false, so that we don't loop any deeper
						$oTable->_buildHasOneQuery(null, $allFields, $joins, 0, $alias.'.');
						$oTable->_buildBelongsToQuery(null, $allFields, $joins, 0, $alias.'.');
					}
					else
					{

						if ( isset($properties['recursive']['hasOne']) ) {
							$oTable->_buildHasOneQuery($properties['recursive']['hasOne'], $allFields, $joins, 0, $alias.'.');
						}
						if ( isset($properties['recursive']['belongsTo']) ) {
							$oTable->_buildBelongsToQuery($properties['recursive']['belongsTo'], $allFields, $joins, 0, $alias.'.');
						}
					}
				}
			}
		}
	}
	private function _retrieveHasMany($limitAliase = false, $mainPKValue, $recursive)
	{
		$data = array();

		foreach ( $this->hasMany as $alias => $properties )
		{
			// This is used for the recursive relations, so that we can limit what to follow
			if ( !$limitAliase || (isset($limitAliase['hasMany']) && in_array($alias, $limitAliase['hasMany'])) )
			{

				// Table Data
				$thisTable		= $properties['table'];

				$thisPK			= isset($properties['primaryKey']) ? $properties['primaryKey'] : 'id';
				$thisFK			= $properties['foreignKey'];

				// Fields
				$thisFields		= $properties['fields'];

				// SubQueries
				$thisSubQ		= isset($properties['subQueries'])	? $properties['subQueries'] : null;


				// Conditions
				$mainCondition	= $alias.'.'.$thisFK.' = '.$mainPKValue;
				$thisCondition	= isset($properties['condition'])	? $properties['condition']	: null;
				$thisCondition	= ($thisCondition) ? $mainCondition.' AND '.$thisCondition : $mainCondition;

				// Order
				$thisOrder	= isset($properties['order'])	? $properties['order']	: null;

				// Order
				$thisLimit	= isset($properties['limit'])	? $properties['limit']	: null;


				$options['fields']		= $thisFields;
				$options['subQueries']	= $thisSubQ;
				$options['condition']	= $thisCondition;
				$options['order']		= $thisOrder;
				$options['limit']		= $thisLimit;
				$options['recursive']	= $recursive;


				if ($options['recursive'] > 3) {
					$options['recursive'] = 2;
				}
				// decrease
				if ( isset($properties['recursive']) && $properties['recursive'] ) {
					$options['recursive']--;	// decrease recursion level by 1
				} else {
					$options['recursive']--;
					$options['recursive']--;
				}
				// Normalize to zero
				if ($options['recursive'] < 0) {
					$options['recursive'] = 0;
				}

				// Check if we want to limit the relations
				if ( isset($properties['recursive']) && is_array($properties['recursive']) ) {
					$options['relation'] = $properties['recursive'];
				}


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
				// need to overwrite the alias, as we could define
				// an acronym e,g. LastThread and the table alias is actually thread
				$tmp_alias		= $oTable->alias;
				$oTable->alias	= $alias;

				$result	= $oTable->find('all', $options);
				$flatten = function($el) use ($alias) {
					$el		= (array)$el;
					$main	= $el[$alias];
					unset($el[$alias]);
					$result = (object)array_merge((array)$main, (array)$el);
					return $result;
				};

				// restore alias (VERY IMPORTANT!!!)
				$oTable->alias	= $tmp_alias;

				$data[$alias] = array_map($flatten, $result);
			}
		}
		return $data;
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
