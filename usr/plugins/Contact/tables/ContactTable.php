<?php
class ContactTable extends Table
{
	protected $table	= 'contact';

	protected $fields	= array(
		'id'			=> 'id',
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
		'fk_user_id'	=> 'fk_user_id',
		'session_id'	=> 'session_id',
		'created'		=> 'created',
	);

	public function add($user_id, $name, $email, $subject, $message)
	{
		$fields	= array(
			'fk_user_id'=> $user_id,
			'name'		=> $name,
			'email'		=> $email,
			'subject'	=> $subject,
			'message'	=> $message,
			'referer'	=> isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
			'useragent'	=> $_SERVER['HTTP_USER_AGENT'],
			'ip'		=> $_SERVER['REMOTE_ADDR'],
			'host'		=> gethostbyaddr($_SERVER['REMOTE_ADDR']),
			'session_id'=> Session::getId(),
		);
		return $this->_add($fields);
	}
	
	public function countNew()
	{
		return $this->_count('`is_read` = 0');
	}
	
	public function markRead($id)
	{
		$this->_updateField($id, 'is_read', 1);
	}
}