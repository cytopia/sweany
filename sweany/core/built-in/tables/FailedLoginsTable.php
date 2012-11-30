<?php
class FailedLoginsTable extends Table
{
	// TABLE
	public $table;
	public $alias	= 'FailedLogin';

	// FIELDS
	public $fields	= array(
		'id',
		'username',
		'password',
		'referer',
		'useragent',
		'session_id',
		'ip',
		'hostname',
		'created',
	);

	// AUTO FIELDS
	public $hasCreated	= array('created' => 'integer');


	/* ******************************************** O V E R R I D E S ******************************************** */

	/**
	 *	Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->table = \Sweany\Settings::tblFailedLogins;
	}

	public function beforeSave(&$data)
	{
		$data['referer']	= isset($_SERVER['HTTP_REFERER'])	? $_SERVER['HTTP_REFERER']		: '';
		$data['useragent']	= isset($_SERVER['HTTP_USER_AGENT'])? $_SERVER['HTTP_USER_AGENT']	: '';
		$data['ip']			= isset($_SERVER['REMOTE_ADDR'])	? $_SERVER['REMOTE_ADDR']		: '';
		$data['hostname']	= isset($_SERVER['REMOTE_ADDR'])	? gethostbyaddr($_SERVER['REMOTE_ADDR']) : '';
		$data['session_id']	= \Sweany\Session::id();
	}
}
