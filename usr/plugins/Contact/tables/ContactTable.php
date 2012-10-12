<?php
class ContactTable extends Table
{
	public $table	= 'contact';
	public $alias	= 'Contact';

	public $fields	= array(
		'id',
		'fk_user_id',
		'user_id' => 'fk_user_id',	// use nice alias here
		'username',
		'name',
		'email',
		'subject',
		'message',
		'is_read',
		'is_archived',
		'is_deleted',
		'referer',
		'useragent',
		'ip',
		'host',
		'session_id',
		'created',
	);

	public $hasCreated	= array('created' => 'integer');

	/**
	 *	@Override
	 *	@param	mixed[]			$data
	 *	@return	integer|null	$return
	 */
	public function save($data, $return = 0)
	{
		$data['referer']	= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
		$data['referer']	= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
		$data['useragent']	= $_SERVER['HTTP_USER_AGENT'];
		$data['ip']			= $_SERVER['REMOTE_ADDR'];
		$data['host']		= gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$data['session_id']	= Session::getId();

		return parent::save($data, $return);
	}

	/**
	 *	@override
	 *	@param	integer		$id			Id of entity
	 *	@param	void		$related	No effect here (no relations defined)
	 *	@return	boolean		success
	 */
	public function delete($id, $related = true, $force = false)
	{
		$fields = array(
			'is_deleted'	=> 1,
		);
		return parent::update($id, $fields, 0);
	}
	public function deleteAll($condition, $related = true, $force = false)
	{
		$fields = array(
			'is_deleted'	=> 1,
		);
		return parent::updateAll($condition, $fields);
	}



	public function countNew()
	{
		$condition = array('`is_read` = :read', array(':read' => 0));
		return $this->find('count', array('condition' => $condition));
	}

	public function markRead($id)
	{
		return $this->update($id, array('is_read' => 1));
	}
}
