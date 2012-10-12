<?php
class UsersTable extends Table
{
	// TABLE
	public $table 	= 'users';
	public $alias	= 'User';

	// FIELDS
	public $fields	= array(
		'id',
		'username',
		'password',
		'password_salt',
		'email',
		'theme',
		'signature',
		'timezone',
		'language',
		'has_accepted_terms',
		'is_admin',
		'is_enabled',
		'is_locked',
		'is_deleted',
		'is_fake',
		'validation_key',
		'reset_password_key',
		'session_id',
		'last_ip',
		'last_host',
		'last_login',
		'last_failed_login_count',
		'created',
		'modified',
	);

	public $hasCreated	= 'integer';
	public $hasModified	= 'integer';



	/************************************************** OVERRIDES **************************************************/

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
