<?php

define('PRIM_KEY',	'__PRIM_KEY__');	// primary indicator to append at each trable


class Table
{
	/* ************************************************************************************************************************** *
	 *
	 *	C L A S S   P R O P E R T I E S
	 *
	 * ************************************************************************************************************************** */



	/* ***************************************  T A B L E   D E F I N E S  *************************************** */

	/**
	 *	@param	string		Name of the sql table (required)
	 */
	public $table			= null;



	/**
	 *	@param	string		Name of the alias to use (required)
	 */
	public $alias			= null;



	/**
	 *	@param	string		Primary key of this table
	 */
	public $primary_key		= 'id';



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
	 public $hasModified	= null;



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
	public $hasCreated		= null;




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
	 *
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
	 *
	 *			'class'			=> '<class_name>',		# string:	Defaults from <sql_table_name> to <SqlTableName>Table (underscore -> camelcase)
	 *			'core'			=> true|false			# boolean:	True if this table file is in sweany/core/built-in/tables
	 *			'plugin'		=> '<plugin_name>'		# string:	Name of the plugin table (does not work with 'core' => true)
	 *
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
	 *			'flatten'		=> true|false,			# boolean:	If you know that only one result comes back (LIMIT 1), then you can flatten the result by one level
	 *			'recursive'		=> true|false,			# boolean:	true: Also load the depending table with all its relations | false: only load this relation
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
	 *
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



	/**
	 *
	 *	$hasAndBelongsToMany	Many-to-Many Relation (fetches many rows)
	 *
	 *	@param	Array
	 *	(
	 *		'<alias>' => array(							# SQL Table alias to use (will also be the name of the array|object after fetching)
	 *			'table'			=> '<table_name>',		# string:	Name of the sql table
	 *
	 *			'class'			=> '<class_name>',		# string:	Defaults from <sql_table_name> to <SqlTableName>Table (underscore -> camelcase)
	 *			'core'			=> true|false			# boolean:	True if this table file is in sweany/core/built-in/tables
	 *			'plugin'		=> '<plugin_name>'		# string:	Name of the plugin table (does not work with 'core' => true)
	 *
	 *			'primaryKey'	=> 'id',				# string:	Primary key of the $hasAndBelongsToMany table (<table_name>) (defaults to: 'id')
	 *
	 *			'joinTable'		=> '<table_name>'		# string:	Name of the sql table that holds the relation between $this->table and 'alias' => 'table'
	 *			'joinThisFK'	=> '<foreign_key>'		# string:	Foreign key of the join table, that links to $this->primary_key
	 *			'joinOtherFK'	=> '<foreign_key>'		# string:	Foreign key of the join table, that links to 'alias' => 'primaryKey'
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
	 *			'list'			=> true|false,			# boolean:	If you specify only a single field, you can get a list (numerical array of values of that field). This is useful for php's in_array() checks
	 *			'flatten'		=> true|false,			# boolean:	If you know that only one result comes back (LIMIT 1), then you can flatten the result by one level
	 *
	 *			'dependent'		=> false,				# boolean:	true: On delete of $this->table record, also delete the according entries in the joinTable (NOTE: Not 'alias' => 'table')
     *   	),
	 *	);
	 */
	public $hasAndBelongsToMany	= array();




	/* ***************************************  C L A S S E S  *************************************** */

	/**
	 * @param	class	Database Class
	 *
	 * Useable by all child Tables.
	 * Useful if you want to overwrite a function,
	 * you can then simply use the database functionality
	 * of the current chosen engine.
	 *
	 * @See: sweany/core/database/iDBO.php (aClass)
	 *       for all available functions to use.
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
		// initialize order to primary key
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
	 * Load One Entity by Id
	 *
	 * @param	integer			$id				# Id of the entity
	 * @param	integer			$recursive		# Level of recursions (0-3)
	 *		0: flat - no relations, only this table
	 *		1: with relations (hasOne, hasMany, belongsTo, hasAndBelongsToMany)
	 *		2: with relations and follow recursion (if set to true in respective relations defintion of the current table)
	 *		3: with relations and force recursion on all relations (even if not set or set to false)
	 *
	 * @param	mixed[]			$options
	 * 		@param	mixed[]			'fields'		# Override Array of fields or Alias-Field pairs
 	 *	 		Array(
	 *				'field1',
	 *				'alias1' => 'field1',
	 *			)
	 *		@param	mixed[]			'subQueries'	# Override Array of subqueries
	 *			Array(
	 *				'alias1' => 'subquery here',
	 *				'alias2' => 'another subquery here',
	 *			)
	 *		@param	mixed[]			'order'			# Override Array of order clauses (only applies to fields in this table)
	 *			Array(
	 *				'Alias.field1' => 'ASC',
	 *				'Alias.field2' => 'DESC',
	 *				'GREATEST(Alias.field1, Alias.field2)' => 'ASC',
	 *			)
	 *		@param	string			'return'		# Override Return Type 'object': Array of Objects (default) | 'array': Array of arrays
	 *
	 * @return	mixed[]			$data			# Returns all found entities
	 */
	public function load($id, $recursive = 1, $options = array())
	{
		$data = $this->loadMany(array($id), $recursive, $options);
		return ( isset($data[0]) ) ? $data[0] : array();
	}



	/**
	 *
	 * Load many entities by an array of ids
	 *
	 * @param	integer[]		$ids			# Array if Ids of the entities
	 * @param	integer			$recursive		# Level of recursions (0-3)
	 *		0: flat - no relations, only this table
	 *		1: with relations (hasOne, hasMany, belongsTo, hasAndBelongsToMany)
	 *		2: with relations and follow recursion (if set to true in respective relations defintion of the current table)
	 *		3: with relations and force recursion on all relations (even if not set or set to false)
	 *
	 * @param	mixed[]			$options		# Array of options (to override default values specified in corresponding Table)
	 * 		@param	mixed[]			'fields'		# Override Array of fields or Alias-Field pairs
 	 *	 		Array(
	 *				'field1',
	 *				'alias1' => 'field1',
	 *			)
	 *		@param	mixed[]			'subQueries'	# Override Array of subqueries
	 *			Array(
	 *				'alias1' => 'subquery here',
	 *				'alias2' => 'another subquery here',
	 *			)
	 *		@param	mixed[]			'order'			# Override Array of order clauses (only applies to fields in this table)
	 *			Array(
	 *				'Alias.field1' => 'ASC',
	 *				'Alias.field2' => 'DESC',
	 *				'GREATEST(Alias.field1, Alias.field2)' => 'ASC',
	 *			)
	 *		@param	string			'return'		# Override Return Type 'object': Array of Objects (default) | 'array': Array of arrays
	 *
	 * @return	mixed[]			$data			# Returns all found entities
	 */
	public function loadMany($ids, $recursive = 1, $options = array())
	{
		// ----------------- Create Condition
		if ( count($ids) == 1 ) {
			// Escape input
			$condition = array($this->alias.'.'.$this->primary_key.' = :id', array(':id' => $ids[0]));
		} else {
			// Escape input
			$ids = implode(', ', array_map(function($id){ return sprintf('%d', (int)$id); }, $ids));
			$condition = $this->alias.'.'.$this->primary_key.' IN ('.$ids.')';
		}


		// ----------------- Unset all information from Optpions
		$tmp = $options;
		unset($options);
		$options = array();


		// ----------------- Rebuild Optpions
		$options['condition'] = $condition;
		$options['recursive'] = $recursive;

		if ( isset($tmp['fields']) ) {
			$options['fields']  = $tmp['fields'];
		}
		if ( isset($tmp['subQueries']) ) {
			$option['subQueries'] = $tmp['subQueries'];
		}
		if ( isset($tmp['order']) ) {
			$options['order'] =  $tmp['order'];
		}
		if ( isset($tmp['return']) ) {
			$options['return'] = $tmp['return'];
		}

		return $this->find('all', $options);
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
	 *	@param	mixed[]	$options	Associative array of options:
	 *
	 *		return			Specify how to retrieve the results
	 *			'return'	=> 'array'	return an array of arrays
	 *			'return'	=> 'object'	return an array of objets
	 *			[DEFAULT]	Returns array of objects
	 *
	 *		fields	 		Overwrite: Array of fields to fetch
	 *			'fields' => array(
	 *				'field_name1',
	 *				'field_name2',
	 *				'alias_name1' => 'field_name1',
	 *				'alias_name2' => 'field_name1'
	 *			)
	 *			[DEFAULT]			$this->fields
	 *			[NOTE]				Useless in 'count' operation
	 *
	 *		subQueries	 	Overwrite: Array of fields to fetch
	 *			'subQueries' => array('alias' => 'sub_query_here')
	 *			[DEFAULT]			$this->subQueries
	 *			[NOTE]				Useless in 'count' operation
	 *
	 *		condition		Overwrite: Escapable condition
	 *	 		@param	mixed[]		$condition
	 *				Array (
	 *					[0]	=>	'`id` = :foo AND `username` LIKE %:bar%',
	 *					[1]	=>	Array (
	 *						':foo' 	=> $id,
	 *						':bar'	=> $name
	 *					),
	 *				);

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
	 *			'recursive'	=> 2				# With all relations and follow recursion (one level) IF recursive is set child relations
	 *			'recursive'	=> 3				# With all relations and force recursion (one level) EVEN IF NOT set by child relations
	 *			[DEFAULT]			1
	 *			[NOTE]				Useless in 'count' operation
	 *
	 *		relation		Limit relations to follow (on this level)
	 *			'relation' => array(
	 *				'hasMany' => array('Edit', 'Post')	# Only follow Edit and Post in hasMany relation (and their respective recursion settings)
	 *			)
	 *
	 *
	 *	@return	mixed[]|integer		Array of objects|arrays or integer (if $type == 'count')
	 *
	 */
	public function find($type = 'all', $options = array())
	{
		// Extract Condition
		$condition	= isset($options['condition'])	? $options['condition'] : $this->condition;

		// Return count immediately, if chosen
		if ( $type == 'count' )
		{
			return $this->db->count($this->table, $condition);
		}

		// prepare condition
		$condition	= $this->db->prepare($condition);

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


	/**
	 * Count entities by condition
	 *
	 * @param string|mixed[]	$condition	Escapeable Condition
	 *		Array (
	 *			[0]	=>	'`id` = :foo AND `username` LIKE %:bar%',
	 *			[1]	=>	Array (
	 *				':foo' 	=> $id,
	 *				':bar'	=> $name
	 *			),
	 *		);
	 *
	 * @return integer	Number of matches
	 */
	public function count($condition = null)
	{
		return $this->db->count($this->table, $condition);
	}

	/**
	 *
	 *	Check if the entity exists
	 *
	 *	@param	integer		$id			Id of the entity (row)
	 *	@return	boolean					exists?
	 */
	public function exist($id)
	{
		return $this->db->rowExists($this->table, $id);
	}



	/**
	 *
	 * Check if an (or many) entity(s) exist by a condition
	 *
	 * @param	mixed[]		$condition	Escapable condition
	 *		Array (
	 *			[0]	=>	'`id` = :foo AND `username` LIKE %:bar%',
	 *			[1]	=>	Array (
	 *				':foo' 	=> $id,
	 *				':bar'	=> $name
	 *			),
	 *		);
	 * @return	integer					number of existing entity(s)
	 */
	public function existBy($condition)
	{
		return $this->find('count', array('condition' => $condition));
	}



	/**
	 *
	 * Get value of a field from the entity (row)
	 *
	 * @param	name		$name		Name (or alias specified by $this->fields) of the field (in row)
	 * @param	integer		$id			Id of the entity (row)
	 * @return	mixed		$value		Value of the field
	 *
	 */
	public function field($id, $name)
	{
		// 1.) converts field aliases to field names
		// 2.) removes all fields not available in this table
		$fields = $this->_prepareFields(array($name));

		if ( !isset($fields[0]) ) {
			\Sweany\SysLog::e('user', '[Table] Alias to field rewrite', 'field(): Failed to rewrite alias: <strong>'.$name.'</strong>');
			return;
		}

		$name	= $fields[0];

		return $this->db->fetchRowField($this->table, $name, $id);
	}



	/**
	 *
	 * Get value of a field by condition
	 *
	 * @param	name		$name		Name (or alias specified by $this->fields) of the field (in row)
	 * @param	mixed[]		$condition	Escapable condition
	 *		Array (
	 *			[0]	=>	'`id` = :foo AND `username` LIKE %:bar%',
	 *			[1]	=>	Array (
	 *				':foo' 	=> $id,
	 *				':bar'	=> $name
	 *			),
	 *		);
	 * @return	mixed		$value		Value of the field
	 */
	public function fieldBy($condition, $name)
	{
		// 1.) converts field aliases to field names
		// 2.) removes all fields not available in this table
		$fields = $this->_prepareFields(array($name));

		if ( !isset($fields[0]) ) {
			\Sweany\SysLog::e('user', '[Table] Alias to field rewrite', 'field(): Failed to rewrite alias: <strong>'.$name.'</strong>');
			return;
		}

		$name	= $fields[0];
		return $this->db->fetchField($this->table, $name, $condition);
	}





	/* ************************************************************************************************************************** *
	 *
	 *	E N T I T Y   S A V E   F U N C T I O N S
	 *
	 * ************************************************************************************************************************** */


	/**
	 *
	 *	Save entity (by id)
	 *
	 *	@param	mixed[]	$data			Array of field=>value / alias=>value pairs
	 *		+ You can use all aliases specified in $this->fields, which will automatically be
	 *		  mapped to the corresponding field.
	 *		+ Non matching fields (no field or alias in $this->fields found) will automatically
	 *		  be removed, to prevent sql errors
	 *		+ Field/Alias values will automatically be escaped, no worry about this.
	 *
	 *		Array = (
	 *			'<field1>	=> '<value1>',
	 *			'<alias1>	=> '<value2>',
	 *		);
	 *
	 *
	 *	@return	integer		Returns last insert id
	 */
	public function save($data)
	{
		// Call user overrideable beforeSave function
		// User can use field-names and/or field-aliases
		$this->beforeSave($data);

		// 1.) converts field aliases to field names
		// 2.) removes all fields not available in this table
		$data	= $this->_prepareDataFields($data);

		$data 	= $this->_appendCreatedFieldIfExist($data);
		$id		= $this->db->insert($this->table, $data, true);

		// Only if a derived table has implemented an afterSave function
		// we have to load the object and pass it on
		if ( method_exists($this, 'afterSave') )
		{
			$object	= $this->load($id, 0);
			$this->afterSave($object);
		}

		return $id;
	}

	/**
	 *	beforeSave()
	 *
	 *	@param	mixed[]	&$data	Data to be saved
	 *
	 *	Override this function to handle data escaping or
	 *	anything else before the data gets saved
	 */
	public function beforeSave(&$data)
	{
	}

	/**
	 *	afterSave()
	 *
	 *	@param	object	$object		Non-recursive saved entity
	 *
	 *	Override this function to handle events after this entity
	 *	has been saved. Such as update/insert other entities
	 */
	 // Note: The method has been outcomment, so we can check via method_exist()
	 // if a derived table class has implemented an afterSave function or not
//	public function afterSave($object)
//	{
//	}


	/* ************************************************************************************************************************** *
	 *
	 *	E N T I T Y   U P D A T E   F U N C T I O N S
	 *
	 * ************************************************************************************************************************** */


	/**
	 *
	 * Update entity (by id)
	 *
	 * @param	integer	$id				Id of the row/entity
	 * @param	mixed[]	$data			Array of field-value (and/or alias-value) pairs
	 *		+ You can use all aliases specified in $this->fields, which will automatically be
	 *		  mapped to the corresponding field.
	 *		+ Non matching fields (no field or alias in $this->fields found) will automatically
	 *		  be removed, to prevent sql errors
	 *		+ Field/Alias values will automatically be escaped, no worry about this.
	 *		+ SysLog will log all involved aliases with INFO and all stripped fields with a warning
	 *
	 *		Array = (
	 *			'<field1>	=> '<value1>',
	 *			'<field2>	=> '<value2>',
	 *			'<alias1>	=> '<value3>',
	 *		);
	 *
	 * @return	boolean		Success
	 */
	public function update($id, $data)
	{
		// Call user overrideable beforeUpdate function
		// User can use field-names and/or field-aliases
		$this->beforeUpdate($data);

		// 1.) converts field aliases to field names
		// 2.) removes all fields not available in this table
		$data	= $this->_prepareDataFields($data);

		$data	= $this->_appendModifiedFieldIfExist($data);

		$success= $this->db->updateRow($this->table, $data, $id);

		// TODO: only load, if the afterUpdate method has been overwritten
		$object	= $this->load($id, 0);
		$this->afterUpdate($object);

		return $success;
	}

	/**
	 *	beforeUpdade()
	 *
	 *	@param	mixed[]	&$data	Data to be updated
	 *
	 *	Override this function to handle data escaping or
	 *	anything else before the data gets updated
	 */
	public function beforeUpdate(&$data)
	{
	}

	/**
	 *	afterUpdate()
	 *
	 *	@param	object	$object		Non-recursive updated entity
	 *
	 *	Override this function to handle events after this entity
	 *	has been updated. Such as update/insert other entities
	 */
	public function afterUpdate($object)
	{
	}


	/**
	 *
	 * Update many entities (rows) by condition
	 *
	 * Note: There is no afterUpdate() Call here!!
	 *
	 *
	 * @param	mixed[]		$condition	Escapable condition
	 *		Array (
	 *			[0]	=>	'`id` = :foo AND `username` LIKE %:bar%',
	 *			[1]	=>	Array (
	 *				':foo' 	=> $id,
	 *				':bar'	=> $name
	 *			),
	 *		);
	 *
	 * @param	mixed[]	$data			Array of field-value (and/or alias-value) pairs
	 *		+ You can use all aliases specified in $this->fields, which will automatically be
	 *		  mapped to the corresponding field.
	 *		+ Non matching fields (no field or alias in $this->fields found) will automatically
	 *		  be removed, to prevent sql errors
	 *		+ Field/Alias values will automatically be escaped, no worry about this.
	 *		+ SysLog will log all involved aliases with INFO and all stripped fields with a warning
	 *
	 *		Array = (
	 *			'<field1>	=> '<value1>',
	 *			'<alias1>	=> '<value3>',
	 *		);
	 *
	 * @return	boolean	success
	 */
	public function updateAll($condition, $data)
	{
		// Call user overrideable beforeUpdate function
		// User can use field-names and/or field-aliases
		$this->beforeUpdate($data);

		// 1.) converts field aliases to field names
		// 2.) removes all fields not available in this table
		$data	= $this->_prepareDataFields($data);

		return $this->db->update($this->table, $data, $condition);
	}



	/**
	 *
	 * Increment an Entity's field(s) (and optionally update other fields simultaneously)
	 *
	 * @param	integer		$id			Id of the entity (row)
	 * @param	string[]	$fields		Array of field names or alias names (if specified ub $this->fields)
	 * @param	mixed[]		$data		[Optional] Array of field-value (and/or alias-value) pairs for additional updating
	 *		+ You can use all aliases specified in $this->fields, which will automatically be
	 *		  mapped to the corresponding field.
	 *		+ Non matching fields (no field or alias in $this->fields found) will automatically
	 *		  be removed, to prevent sql errors
	 *		+ Field/Alias values will automatically be escaped, no worry about this.
	 *		+ SysLog will log all involved aliases with INFO and all stripped fields with a warning
	 *
	 *		Array = (
	 *			'<field1>	=> '<value1>',
	 *			'<alias1>	=> '<value2>',
	 *		);
	 *
	 * @return	boolean	success
	 */
	public function increment($id, $fields, $data = array())
	{
		$condition = array('id = :id', array(':id' => $id));

		// 1.) converts field aliases to field names
		// 2.) removes all fields not available in this table
		$fields = $this->_prepareFields($fields);

		// 1.) converts field aliases to field names
		// 2.) removes all fields not available in this table
		$data	= $this->_prepareDataFields($data);

		return $this->db->incrementFields($this->table, $fields, $data, $condition);
	}



	/**
	 *
	 * Increment an Field(s) of many entities by condition (and optionally update other fields simultaneously)
	 *
	 * @param	mixed[]		$condition	Escapable condition
	 *		Array (
	 *			[0]	=>	'`id` = :foo AND `username` LIKE %:bar%',
	 *			[1]	=>	Array (
	 *				':foo' 	=> $id,
	 *				':bar'	=> $name
	 *			),
	 *		);
	 *
	 * @param	string[]	$fields		Array of field names
	 * @param	mixed[]		$data		[Optional] Array of field-value (and/or alias-value) pairs for additional updating
	 *		+ You can use all aliases specified in $this->fields, which will automatically be
	 *		  mapped to the corresponding field.
	 *		+ Non matching fields (no field or alias in $this->fields found) will automatically
	 *		  be removed, to prevent sql errors
	 *		+ Field/Alias values will automatically be escaped, no worry about this.
	 *		+ SysLog will log all involved aliases with INFO and all stripped fields with a warning
	 *
	 *		Array = (
	 *			'<field1>	=> '<value1>',
	 *			'<field2>	=> '<value2>',
	 *			'<alias1>	=> '<value3>',
	 *		);
	 *
	 * @return	boolean					Success of operation
	 */
	public function incrementAll($condition, $fields, $data = array())
	{
		// 1.) converts field aliases to field names
		// 2.) removes all fields not available in this table
		$fields = $this->_prepareFields($fields);

		// 1.) converts field aliases to field names
		// 2.) removes all fields not available in this table
		$data	= $this->_prepareDataFields($data);

		return $this->db->incrementFields($this->table, $fields, $data, $condition);
	}




	/* ************************************************************************************************************************** *
	 *
	 *	E N T I T Y   D E L E T E   F U N C T I O N S
	 *
	 * ************************************************************************************************************************** */


	/**
	 *
	 * Delete entity (by id)
	 *
	 * @param	integer		$id			Id of the row/entity
	 * @param	boolean		$related	Also delete related data from local relations (hasOne, hasMany, hasAndBelongsToMany)
	 * 									NOTE-1: Only deletes related data, if specified by the relation via 'dependent' => true
	 * 									NOTE-2: Does not delete recursively in the specified relations. If you like to do so, overwrite this function
	 *
	 * @param	boolean		$force		Delete related data from local relation (hasOne, hasMany, hasAndBelongsToMany)
	 * 									NOTE-1: Even deletes related data, if it is not specified. This is a FORCED DELETE
	 * 									NOTE-2: Does not delete recursively in the specified relations. If you like to do so, overwrite this function
	 * 									NOTE-3: This flag is useless if $related is set to false
	 *
	 * @return	boolean					Success of operation
	 */
	public function delete($id, $related = true, $force = false)
	{
		// TODO: implement: delete related data
		return $this->db->deleteRow($this->table, $id);
	}



	/**
	 *
	 * Delete many entities (rows) by condition
	 *
	 * @param	mixed[]		$condition	Escapable condition
	 *		Array (
	 *			[0]	=>	'`id` = :foo AND `username` LIKE %:bar%',
	 *			[1]	=>	Array (
	 *				':foo' 	=> $id,
	 *				':bar'	=> $name
	 *			),
	 *		);
	 *
	 * @param	boolean		$related	Also delete related data from local relations (hasOne, hasMany, hasAndBelongsToMany)
	 * 									NOTE-1: Only deletes related data, if specified by the relation via 'dependent' => true
	 * 									NOTE-2: Does not delete recursively in the specified relations. If you like to do so, overwrite this function
	 *
	 * @param	boolean		$force		Delete related data from local relation (hasOne, hasMany, hasAndBelongsToMany)
	 * 									NOTE-1: Even deletes related data, if it is not specified. This is a FORCED DELETE
	 * 									NOTE-2: Does not delete recursively in the specified relations. If you like to do so, overwrite this function
	 * 									NOTE-3: This flag is useless if $related is set to false
	 *
	 * @return	boolean					Success of operation
	 */
	public function deleteAll($condition, $related = true, $force = false)
	{
		// TODO: implement: delete related data
		return $this->db->delete($this->table, $condition);
	}






	/* ************************************************************************************************************************** *
	*
	*	P R I V A T E   C L A S S   F U N C T I O N S
	*
	* ************************************************************************************************************************** */



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
	 *		'recursive':	 0						# Flat - no relations, only this table
	 *		'recursive':	 1						# With all relations (hasOne, hasMany, belongsTo, hasAndBelongsToMany)
	 *		'recursive':	 2						# With all relations and follow recursion (one level) specified by child relation
	 *		'recursive':	 3						# With all relations and force recursion (one level) on all child relations
	 * @param mixed[]			$relation		# Follow only specific relations (on this level)
	 *	'$relation':	 array(
	 *		'hasMany' => array('Edit', 'Post')		# Only follow Edit and Post in hasMany relation (plus THEIR respective recursion settings)
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
		$prefix = $this->alias.'.';	// Prefix Joins (2nd level) by <alias>.
		$joins	= array();

		if ( $recursive > 0)
		{
			// We only join the X->one relations here
			// All X->many have to be done in a for-loop, otherwise we will have no limit available at them

			// Add hasOne (one-to-one)
			if ($tblClass) {
				$tblClass->_buildHasOneQuery($relation, $allFields, $joins, $recursive, $prefix);
			} else {
				$this->_buildHasOneQuery($relation, $allFields, $joins, $recursive, $prefix);
			}

			// Add belongsTo (many-to-one)
			if ($tblClass) {
				$tblClass->_buildBelongsToQuery($relation, $allFields, $joins, $recursive, $prefix);
			} else {
				$this->_buildBelongsToQuery($relation, $allFields, $joins,  $recursive, $prefix);
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
			$habtm	= array();

			foreach ($row as $field => $value)
			{
				$part	= explode('.', $field);
				$size	= count($part);

				// --------------------------------------- Primary Key ---------------------------------------
				if ($size == 1 && $recursive )
				{
					if ( $recursive>0 || (is_array($relation) && in_array('hasMany', array_keys($relation))) )
					{
						$pk			= $value;

						// If recursive is set to 3 (force all, not only those which are specified)
						// We need to destroy the limitations
						$relation	= ($recursive == 3) ? null : $relation;

						// Get the hasMany Relations
						$many		= $this->_retrieveHasMany($relation, $pk, $recursive, 'array');
					}
					if ( $recursive>0 || (is_array($relation) && in_array('hasAndBelongsToMAny', array_keys($relation))) )
					{
						$pk			= $value;

						// If recursive is set to 3 (force all, not only those which are specified)
						// We need to destroy the limitations
						$relation	= ($recursive == 3) ? null : $relation;

						// Get the hasMany Relations
						$habtm		= $this->_retrieveHasAndBelongsToMany($relation, $pk, $recursive, 'array');
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
			// merge hasMany with hasAndBelongsToMany
			$tmp = array_merge($many, $habtm);

			// merge results with the above merge
			$data[$count] = array_merge($data[$count][$alias1], $tmp);
			$count++;
		};



		$getObject = function($row, &$data) use (&$count, $relation, $recursive) {

			$many	= array();
			$habtm	= array();

			foreach ($row as $field => $value)
			{
				$part	= explode('.', $field);
				$size	= count($part);

				// --------------------------------------- Primary Key ---------------------------------------
				if ($size == 1 && $recursive)
				{

					if ( $recursive>0 || (is_array($relation) && in_array('hasMany', array_keys($relation))) )
					{
						//debug($recursive);
						$pk			= $value;

						// If recursive is set to 3 (force all, not only those which are specified)
						// We need to destroy the limitations
						$relation	= ($recursive == 3) ? null : $relation;

						// Get the hasMany Relations
						$many		= $this->_retrieveHasMany($relation, $pk, $recursive);
					}

					if ( $recursive>0 || (is_array($relation) && in_array('hasAndBelongsToMAny', array_keys($relation))) )
					{
						//debug($recursive);
						$pk			= $value;

						// If recursive is set to 3 (force all, not only those which are specified)
						// We need to destroy the limitations
						$relation	= ($recursive == 3) ? null : $relation;

						// Get the hasMany Relations
						$habtm		= $this->_retrieveHasAndBelongsToMany($relation, $pk, $recursive);
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
			// merge hasMany with hasAndBelongsToMany
			$tmp = (object)array_merge((array)$many, (array)$habtm);

			// merge results with the above merge
			$data[$count] = (object)array_merge((array)$data[$count]->$alias1, (array)$tmp);

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
						$oTable = \Sweany\AutoLoader::loadCoreTable($class);
					}
					else
					{
						$plugin	= isset($properties['plugin'])? $properties['plugin']: null;
						$oTable = $plugin ? \Sweany\AutoLoader::loadPluginTable($class, $plugin) : \Sweany\AutoLoader::loadTable($class);
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
						$oTable = \Sweany\AutoLoader::loadCoreTable($class);
					}
					else
					{
						$plugin	= isset($properties['plugin'])? $properties['plugin']: null;
						$oTable = $plugin ? \Sweany\AutoLoader::loadPluginTable($class, $plugin) : \Sweany\AutoLoader::loadTable($class);
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
	private function _retrieveHasMany($limitAliase = false, $mainPKValue, $recursive, $return = 'object')
	{
		$data = array();

		foreach ( $this->hasMany as $alias => $properties )
		{
			// This is used for the recursive relations, so that we can limit what to follow
			if ( !$limitAliase || (isset($limitAliase['hasMany']) && in_array($alias, $limitAliase['hasMany'])) )
			{
				// Relation info
				$thisFK			= $properties['foreignKey'];

				// Fields (and aliases) and Subqueries
				$thisFields		= $properties['fields'];
				$thisSubQ		= isset($properties['subQueries'])	? $properties['subQueries'] : null;

				// Conditions
				$mainCondition	= $alias.'.'.$thisFK.' = '.$mainPKValue;
				$thisCondition	= isset($properties['condition'])	? $properties['condition']	: null;
				$thisCondition	= ($thisCondition) ? $mainCondition.' AND '.$thisCondition : $mainCondition;

				// Order
				$thisOrder	= isset($properties['order'])	? $properties['order']	: null;

				// Limit
				$thisLimit	= isset($properties['limit'])	? $properties['limit']	: null;

				$options['fields']		= $thisFields;
				$options['subQueries']	= $thisSubQ;
				$options['condition']	= $thisCondition;
				$options['order']		= $thisOrder;
				$options['limit']		= $thisLimit;
				$options['recursive']	= $recursive;
				$options['return']		= $return;


				// Normalize wrong recursive values
				if ($options['recursive'] > 3) {
					$options['recursive'] = 3;
				}
				if ($options['recursive'] < 0) {
					$options['recursive'] = 0;
				}


				// If we do not want to force recursion,
				// check if we want to limit to only specific relations
				if ($options['recursive'] < 3) {
					if ( isset($properties['recursive']) && is_array($properties['recursive']) ) {
						$options['relation'] = $properties['recursive'];
					}
				}

				// decrease
				if ( isset($properties['recursive']) && $properties['recursive'] ) {
					$options['recursive']--;	// decrease recursion level by 1
				} else {
					$options['recursive']--;
					$options['recursive']--;
				}


				$class	= isset($properties['class']) ? $properties['class'] : Strings::camelCase($properties['table'], true);
				$core	= isset($properties['core'])  ? $properties['core']  : false;

				if ($core)
				{
					$oTable = \Sweany\AutoLoader::loadCoreTable($class);
				}
				else
				{
					$plugin	= isset($properties['plugin'])? $properties['plugin']: null;
					$oTable = $plugin ? \Sweany\AutoLoader::loadPluginTable($class, $plugin) : \Sweany\AutoLoader::loadTable($class);
				}

				// need to overwrite the alias, as we could define
				// an acronym e,g. LastThread and the table alias is actually thread
				$tmp_alias		= $oTable->alias;
				$oTable->alias	= $alias;

				$result			= $oTable->find('all', $options);

				// restore alias (VERY IMPORTANT!!!)
				$oTable->alias	= $tmp_alias;

				// Apply FLATTENING if specified
				// This is only useful, if you know that you will receive only one element
				if ( isset($properties['flatten']) && $properties['flatten'] === true )
				{
					$result = isset($result[0]) ? $result[0] : ( $return == 'object' ? new stdClass() : array() );
				}

				$data[$alias] = $result;
			}
		}
		return $data;
	}

	private function _retrieveHasAndBelongsToMany($limitAliase = false, $mainPKValue, $recursive, $return = 'object')
	{
		$data = array();

		foreach ( $this->hasAndBelongsToMany as $alias => $properties )
		{
			// This is used for the recursive relations, so that we can limit what to follow
			if ( !$limitAliase || (isset($limitAliase['hasAndBelongsToMany']) && in_array($alias, $limitAliase['hasAndBelongsToMany'])) )
			{
				// Array holding all fields to be selected
				$allFields	= array();

				// ------------------------------------ MAIN QUERY ------------------------------------
				$PK			= isset($properties['primaryKey']) ? $properties['primaryKey'] : 'id';
				$aliasedPK	= '`'.$alias.'`.`'.$PK.'`';		// Primary key identifier

				// Get main query fields
				$fields		= $properties['fields'];
				$fields		= $this->buildAliasedFields($fields, $alias, '', '');
				$fields 	= array_merge(array($aliasedPK.' AS `'.PRIM_KEY.'`'), $fields);

				// Get main query subqueries
				if ( isset($properties['subQueries']) )
				{
					$subQueries	= $properties['subQueries'];
					$subQueries = $this->buildAliasedSubQueries($subQueries, $alias, '');
					$allFields	= array_merge($fields, $subQueries);
				}
				else
				{
					$allFields = $fields;
				}

				// Get main query condition
				$condition	= 'WHERE `'.$this->alias.'`.`'.$this->primary_key.'` = \''.$mainPKValue.'\'';


				// Additional query information
				$where		= isset($properties['condition'])	? $properties['condition']	: null;
				$order		= isset($properties['order'])		? $properties['order']		: null;
				$limit		= isset($properties['limit'])		? $properties['limit']		: null;

				$where		= ($where) ? 'WHERE '	.$condition.' AND '.$where : $condition;
				$order		= ($order) ? 'ORDER BY '.implode(',', array_map(create_function('$key, $dir', 'return $key." ".$dir;'), array_keys($order), array_values($order))) : '';
				$limit		= ($limit) ? 'LIMIT '	.$limit : '';


				$query =
					'SELECT '.
						implode(', ', $allFields).' '.		// OUTER FIELDS: <alias>_<field> AS <alias>.<field>
					'FROM '.
						'`'.$this->table.'` AS `'.$this->alias.'` '.
					// table holding the relations of both other tables
					'JOIN '.
						'`'.$properties['joinTable'].'` AS `_JOIN_TABLE`'.
					'ON ( `'.$this->alias.'`.`'.$this->primary_key.'` = `_JOIN_TABLE`.`'.$properties['joinThisFK'].'` ) '.
					// Other table which also contains the fields to fetch
					'JOIN '.
						'`'.$properties['table'].'` AS `'.$alias.'`' .
					'ON ( `_JOIN_TABLE`.`'.$properties['joinOtherFK'].'` = '.$aliasedPK.' ) '.
					$where.' '.
					//'%s '.		// GROUP
					//'%s '.		// HAVING
					$order.' '.
					$limit;

				$result = $this->retrieveResults($query, 0, null, $return);

				// Apply LISTING if specified
				// This is only useful, if you know that you will receive only one element
				if ( isset($properties['list']) && $properties['list'] === true ) {
					// Get field-name or field-alias (whichever has been set)
					$field = isset($properties['fields'][0]) ? $properties['fields'][0] : key($properties['fields']);
					$result = array_map(function($row) use ($field){ $row = (array)$row; return $row[$field]; }, (array)$result);
				}
				// Apply FLATTENING if specified
				// This is only useful, if you know that you will receive only one element
				if ( isset($properties['flatten']) && $properties['flatten'] === true )
				{
					$result = isset($result[0]) ? $result[0] : ( $return == 'object' ? new stdClass() : array() );
				}
				$data[$alias] = $result;
			}
		}
		return $data;
	}



	private function _appendModifiedFieldIfExist($data)
	{
		if ( $this->hasModified )
		{
			if ( is_array($this->hasModified) )
			{
				$field_name		= key($this->hasModified);
				$sqlDataType	= $this->hasModified[$field_name];
			}
			else
			{
				$field_name		= 'modified';
				$sqlDataType	= $this->hasModified;
			}

			switch ($sqlDataType)
			{
				case 'datetime':	return array_merge($data, array($field_name	=> $this->db->getNowDateTime()));
				case 'timestamp':	return array_merge($data, array($field_name	=> $this->db->getNowTimeStamp()));
				case 'integer':		return array_merge($data, array($field_name	=> $this->db->getNowUnixTimeStamp()));
				default:			return $data;
			}
		}
		else
		{
			return $data;
		}
	}
	private function _appendCreatedFieldIfExist($data)
	{
		if ( $this->hasCreated )
		{
			if ( is_array($this->hasCreated) )
			{
				$field_name		= key($this->hasCreated);
				$sqlDataType	= $this->hasCreated[$field_name];
			}
			else
			{
				$field_name		= 'created';
				$sqlDataType	= $this->hasCreated;
			}

			switch ($sqlDataType)
			{
				case 'datetime':	return array_merge($data, array($field_name	=> $this->db->getNowDateTime()));
				case 'timestamp':	return array_merge($data, array($field_name	=> $this->db->getNowTimeStamp()));
				case 'integer':		return array_merge($data, array($field_name	=> $this->db->getNowUnixTimeStamp()));
				default:			return $data;
			}
		}
		else
		{
			return $data;
		}
	}



	/**
	 * _prepareDataFields()
	 *
	 * This allows the user to insert/update fields by their alias names.
	 * And also put in all kinds of values. Even the whole $_POST array,
	 * we will strip out all illegal fields
	 *
	 * 1.) Convert aliases to their corresponding fields.
	 * 2.) Remove all illegal fields, that are not present in the table
	 *
	 * @param mixed[]	$data		field=>value/alias=>value pair for insert/update
	 */
	private function _prepareDataFields($data)
	{
		if ( !is_array($data) )
		{
			return array();
		}

		$valid			= array();

		foreach ($data as $field => $value)
		{
			// Field-Value-Pair is valid by default
			if ( in_array($field, $this->fields) )
			{
				$valid[$field] = $value;
			}
			// Field-Value-Pair is using an alias, so we need to rewrite it
			else if ( isset($this->fields[$field]) )
			{
				// get actualy field by alias
				$real_name = $this->fields[$field];
				$valid[$real_name] = $value;
				\Sweany\SysLog::i('user', 'Insert/Update', '['.get_class($this).': Field Rewrite] Used Alias: '.$field.' is changed to Field: '.$real_name);
			}
			// Discard all other value
			else {
				\Sweany\SysLog::w('user', 'Insert/Update', '['.get_class($this).': Wrong Field] Field: '.$field.' does not exist');
			}
		}
		return $valid;
	}

	private function _prepareFields($fields)
	{
		$availFields	= array_values($this->fields);
		$availAliase	= array_keys($this->fields);

		$valid			= array();

		foreach ($fields as $field)
		{
			// Field-Value-Pair is valid by default
			if ( in_array($field, $availFields) ) {
				$valid[] = $field;
			}
			// Field-Value-Pair is using an alias, so we need to rewrite it
			else if ( in_array($field, $availAliase) ) {
				// get actualy field by alias
				$real_name	= $this->fields[$field];
				$valid[]	= $real_name;
				\Sweany\SysLog::i('user', 'Insert/Update', '['.get_class($this).': Field Rewrite] Used Alias: '.$field.' is changed to Field: '.$real_name);
			}
			// Discard all other value
			else {
				\Sweany\SysLog::w('user', 'Insert/Update', '['.get_class($this).': Wrong Field] Field: '.$field.' does not exist');
			}
		}
		return $valid;
	}
}
