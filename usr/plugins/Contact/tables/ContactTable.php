<?php
class ContactTable extends Table
{
	public $table	= 'contact';
	public $alias	= 'Contact';

	public $fields	= array(
		'id'			=> 'id',
		'fk_user_id'	=> 'fk_user_id',
		'username'		=> 'username',
		'name'			=> 'name',
		'email'			=> 'email',
		'subject'		=> 'subject',
		'message'		=> 'message',
		'is_read'		=> 'is_read',
		'is_archived'	=> 'is_archived',
		'is_deleted'	=> 'is_deleted',
		'referer'		=> 'referer',
		'useragent'		=> 'useragent',
		'ip'			=> 'ip',
		'host'			=> 'host',
		'session_id'	=> 'session_id',
		'created'		=> 'created',
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
	public function delete($id, $related = null)
	{
		$fields = array(
			'is_deleted'	=> 1,
		);
		return parent::update($id, $fields, 0);
	}
	public function deleteAll($condition, $related = false, $return = 0)
	{
		$fields = array(
			'is_deleted'	=> 1,
		);
		return parent::updateAll($condition, $fields, $return);
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
