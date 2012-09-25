<?php
class Table3
{
	protected $db			= NULL;
	protected $table		= NULL;
	protected $tableHolders	= array();
	protected $fields		= array();
	protected $aliases		= array();
	protected $subSelects	= array();

	private $order = null;
	private $where = null;
	
	/**
	 * one-to-one
	 *
	 * className: the classname of the model being associated to the current model. If you’re defining a ‘User hasOne Profile’ relationship, the className key should equal ‘Profile.’
	 * foreignKey: the name of the foreign key found in the other model. This is especially handy if you need to define multiple hasOne relationships. The default value for this key is the underscored, singular name of the current model, suffixed with ‘_id’. In the example above it would default to ‘user_id’.
	 * conditions: an array of find() compatible conditions or SQL strings such as array(‘Profile.approved’ => true)
	 * fields: A list of fields to be retrieved when the associated model data is fetched. Returns all fields by default.
	 * order: an array of find() compatible order clauses or SQL strings such as array(‘Profile.last_name’ => ‘ASC’)
	 * dependent: When the dependent key is set to true, and the model’s delete() method is called with the cascade parameter set to true, associated model records are also deleted. In this case we set it true so that deleting a User will also delete her associated Profile.
	 */
	
	public $primary_key		= array();
	public $foreign_keys	= array();
	
	// one to one	hasOne	A user has one profile.
	public $hasOne = array(
		'User' => array(
			'table'			=> 'User',
			'foreignKey'	=> 'fk_user_id',	// foreign key in other model
			'conditions'	=> array('User.is_enabled' => '1'),
			'fields'		=> array(),
			'subQueries'	=> array(),
			'order'			=> array(), // array()|string
			'dependent'		=> false,
			'recursive'		=> 1,		// level of recursion
			'hasCreated'	=> true,
			'hasModified'	=> true,
        ),
    );
	
	public function _loadHasOne()
	{
		foreach ( $hasOne as $name => $properties)
		{
			$table = Loader::loadTable($properties['table']);
			
			$data[$name] = $this->db->fetchRowById($properties['table'], $row[$properties['foreignKey']], $properties['fields']);
		}
		// merge this row with one-to-one rows
		$data[$this->table] = $row;
		return $data;
	
	}
	
	// one to many	hasMany	A user can have multiple recipes.
	public $hasMany = array(
		'User' => array(
			'table'			=> 'User',
			'foreignKey'	=> 'fk_user_id',	// foreign key in other model
			'conditions'	=> array('User.is_enabled' => '1'),
			'fields'		=> array(),
			'subQueries'	=> array(),
			'order'			=> array(),
			'limit'			=> array(),
			'dependent'		=> false,
			'hasCreated'	=> true,
			'hasModified'	=> true,
        ),
    );
	// many to one	belongsTo	Many recipes belong to a user.
	public $belongsTo = array(
		'User' => array(
			'table'			=> 'User',
			'foreignKey'	=> 'fk_user_id',	// foreign key in the current model
			'conditions'	=> array('User.is_enabled' => '1'),
			'fields'		=> array(),
			'subQueries'	=> array(),
			'order'			=> array(),
			'limit'			=> array(),
			'dependent'		=> false,
			'hasCreated'	=> true,
			'hasModified'	=> true,
        ),
    );
	
	public function load($id, $recursive = true)
	{
		$fields = $this->__selectFields($fields);
		$row	= $this->db->fetchRowById($this->table, $id, $fields);
		$data	= array();
		
		// one-to-one
		foreach ( $hasOne as $name => $properties)
		{
			$data[$name] = $this->db->fetchRowById($properties['table'], $row[$properties['foreignKey']], $properties['fields']);
		}
		// merge this row with one-to-one rows
		$data[$this->table] = $row;
		return $data;
	}
// table conventions:  forum_forums
// naming conventions: ForumForums
	
//
// many to many	hasAndBelongsToMany	Recipes have, and belong to many ingredients.
//

/* TODO:
 * // object orientated
load()
loadMany()
save()
delete()
update()


// statics
raw()
get($fields, $)
getRow()
getRows()
getField()
getFields()
getColumn()
getColumns()
getField

// Dynamics
query()
distinct()
where()
having()
groupBy
orderBy()
orderRandom()
limit()
join()
range(from, offset)
execute()
 */

	public function __construct()
	{
		$this->db	= new \Core\Init\CoreMySQL;
	}



	/* *************************************** ENTITY FUNCTIONS ******************************* */
	public function load($id, $recursive = true)
	{

	}
	public function loadMany($ids, $recursive = true)
	{

	}
	public function save($data)
	{
	}
	public function delete($id)
	{
	}
	public function update($data, $id)
	{
	}


	public function select($table, $alias, $fields = null)
	{
	}

	/**
	 *
	 *  @param	mixed[]	$order
	 *  array('title' => 'ASC')
	 */
	public function order($order)
	{
		$this->order = $order;
		return $this;
	}

	public function range($start, $end){}
	public function limit($end){}


	public function where($where, $placeholders = array())
	{
		$this->where = $this->_evalPlaceholders($where, $placeholders);
		return $this;
	}
	/**
	 * example:
	 * join('users', 'User', 'n.uid = u.uid AND u.uid = :uid', array(':uid' => 5));
	 */
	public function join($table, $alias, $condition, $placeholders = array())
	{
		return $this;
	}
	public function execute()
	{
		debug($this);
	}


	private function _evalPlaceholders($string, $placeholders = array())
	{
		foreach ($placeholders as $key => $val)
		{
			$string = str_replace($key, mysql_real_escape_string($val), $string);
		}
		return $string;
	}
}