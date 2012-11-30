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


	public function beforeSave(&$data)
	{
		$data['name']		= isset($data['name'])		? Strings::removeTags($data['name'])	: '';
		$data['email']		= isset($data['email'])		? Strings::removeTags($data['email'])	: '';
		$data['message']	= isset($data['message'])	? Strings::removeTags($data['message'])	: '';

		$data['fk_user_id']	= \Sweany\Users::id();
		$data['username']	= \Sweany\Users::name();

		$data['referer']	= Client::referer();
		$data['useragent']	= Client::useragent();
		$data['ip']			= Client::ip();
		$data['host']		= Client::host();
		$data['session_id']	= Session::id();
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
		return $this->count($condition);
	}

	public function markRead($id)
	{
		return $this->update($id, array('is_read' => 1));
	}
}
