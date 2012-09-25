<?php
class Table2
{
	protected $db			= NULL;
	protected $table		= NULL;
	protected $tableHolders	= array();
	protected $fields		= array();
	protected $aliases		= array();
	protected $subSelects	= array();

	private $order = null;
	private $where = null;


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