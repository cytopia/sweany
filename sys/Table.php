<?php

abstract Class Table
{
	protected $db			= NULL;
	protected $table		= NULL;
	protected $tableHolders	= array();
	protected $fields		= array();
	protected $aliases		= array();
	protected $subSelects	= array();



	public function __construct()
	{
		$this->db	= new MySQL;

		$this->fields = $this->__replaceTablePlaceholders();
	}

	public function getTable()
	{
		return $this->table;
	}

	/************************************************************************************************************************************
	 *
	 *                                              PUBLIC CALLS
	 *
	 * callable by controller and model
	 *
	/************************************************************************************************************************************/


	/********************************* GET *********************************/


	public function getAll($fields = NULL, $order = array(), $limit = NULL, $limit_start = NULL)
	{
		$fields = $this->__selectFields($fields);

		return $this->db->fetch($this->table, $fields, NULL, NULL, $order, $limit, $limit_start);
	}

	public function getRow($id, $fields = NULL)
	{
		$fields = $this->__selectFields($fields);

		return $this->db->fetchRowById($this->table, $id, $fields);
	}

	public function getRows($ids = array(), $fields = NULL, $order = array(), $limit = NULL, $limit_start = NULL)
	{
		$fields = $this->__selectFields($fields);

		return $this->db->fetchByIds($this->table, $ids, $fields, $order, $limit, $limit_start);
	}

	public function getField($id, $field)
	{
		return $this->db->fetchFieldById($this->table, $field, $id);
	}

	public function getColumnFields($field, $where = NULL, $having = NULL, $order = array(), $limit_num = NULL, $limit_start = NULL)
	{
		return $this->db->fetchColumnFields($this->table, $field, $where, $having, $order, $limit_num, $limit_start);
	}

	/********************************* COUNT ALL *********************************/

	public function countAll()
	{
		return $this->_count(NULL);
	}

	/********************************* EXIST *********************************/

	public function rowExists($id)
	{
		return $this->db->existId($this->table, $id);
	}

	public function fieldExists($field, $value)
	{
		return $this->db->existField($this->table, $field, $value);
	}



	/************************************************************************************************************************************
	 *
	 *                                              PROTECTED CALLS
	 *
	 * only callable by tables
	 *
	 /************************************************************************************************************************************/



	/********************************* GET *********************************/

	// conditional get
	protected function _get($fields = NULL, $where = NULL, $having = NULL, $order = array(), $limit = NULL, $limit_start = NULL)
	{
		$fields = $this->__selectFields($fields);

		return $this->db->fetch($this->table, $fields, $where, $having, $order, $limit, $limit_start);
	}


	/********************************* GET FIELD *********************************/

	protected function _getField($field, $condition)
	{
		return $this->db->fetchField($this->table, $field, $condition);
	}


	/********************************* COUNT *********************************/

	protected function _count($condition)
	{
		return $this->db->count($this->table, $condition);
	}


	/********************************* ADD *********************************/

	protected function _add($fields)
	{
		// if an created field exist, add it
		if ( isset($this->fields['created']) )
		{
			$fields['created'] = date("Y-m-d H:i:s", time());
		}

		return $this->db->insertRow($this->table, $fields);
	}

	/********************************* UPDATE *********************************/

	protected function _updateRow($id, $fields = array())
	{
		// if an modifed field exist, add it
		if ( isset($this->fields['modified']) )
		{
			$fields['modified'] = date("Y-m-d H:i:s", time());
		}

		return $this->db->updateRow($this->table, $fields, $id);
	}

	protected function _updateField($id, $field, $value)
	{
		// if an modifed field exist, add it
		if ( isset($this->fields['modified']) )
		{
			$fields['modified'] = date("Y-m-d H:i:s", time());
		}

		$fields[$field] = $value;

		return $this->db->updateRow($this->table, $fields, $id);
	}

	protected function _incrementField($id, $field, $get_update_id = null)
	{
		$fields = array();

		// if an modifed field exist, add it
		if ( isset($this->fields['modified']) )
		{
			$fields['modified'] = date("Y-m-d H:i:s", time());
		}

		$condition = sprintf("id = %d", $id);
		return $this->db->incrementField($this->table, $field, $condition, $get_update_id, $fields);
	}



	/********************************* DELETE *********************************/

	protected function _deleteRow($id)
	{
		return $this->db->deleteRow($this->table, $id);
	}



	/************************************************************************************************************************************
	 *
	 *                                              PRIVATE CALLS
	 *
	 * only callable by this class
	 *
	 /************************************************************************************************************************************/

	private function __replaceTablePlaceholders()
	{
		$newFields	= array();

		// replace table placeholders
		foreach ($this->fields as $key => $value)
		{
			// replace own table
			$value = str_replace('[[this]]', $this->table, $value);
			// replace other table place holders
			foreach ($this->tableHolders as $hold => $replace)
				$value = str_replace($hold, $replace, $value);

			$newFields[$key] = $value;
		}
		return $newFields;
	}

	private function __selectFields($fields)
	{
		$columns = array();

		// if no selection is specified - get All
		if ( is_null($fields) || !is_array($fields) )
		{
			foreach ($this->fields as $alias => $field)
			{
				$alias	= '`'.$alias.'`';
				$field	= ($field[0] != '(') ? '`'.$field.'`' : $field;
				$columns[$alias] = $field;
			}
		}

		// only get the specified fields
		else
		{
			foreach ($fields as $field)
			{
				$alias				= '`'.$field.'`';
				$columns[$alias]	= ( $this->fields[$field][0] != '(' ) ? '`'.$this->fields[$field].'`' : $this->fields[$field];
			}
		}
		return $columns;
	}
}