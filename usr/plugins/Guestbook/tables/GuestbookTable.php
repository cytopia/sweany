<?php
class GuestbookTable extends Table
{
	public $table	= 'guestbook';
	public $alias	= 'Guestbook';

	public $fields	= array(
		'id',
		'fk_user_id',
		'username',
		'author',
		'email',
		'avatar',
		'title',
		'message',
		'is_approved',
		'is_deleted',
		'referer',
		'useragent',
		'ip',
		'host',
		'session_id',
		'created',
	);

	public $hasCreated	= array('created' => 'integer');

	public $condition	= array('Guestbook.is_deleted = 0 AND Guestbook.is_approved = 1');
	public $order		= array('Guestbook.created' => 'DESC');


	public function beforeSave(&$data)
	{
		$data['fk_user_id']	= \Sweany\Users::id();
		$data['username']	= \Sweany\Users::name();
		$data['author']		= isset($data['author']) ? htmlentities($data['author']) : '';

		$data['referer']	= Client::referer();
		$data['useragent']	= Client::useragent();
		$data['ip']			= Client::ip();
		$data['host']		= Client::host();
		$data['session_id']	= Session::id();

		if ( $data['fk_user_id'] )
		{
			// auto-approve entries of registered users
			$data['is_approved'] = 1;
		}
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
}
