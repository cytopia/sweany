<?php
class FailedLoginsTable extends Table
{
	// TABLE
	public $table 	= 'failed_logins';
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
	protected $hasCreated	= array('created' => 'integer');
	
	
	/* ******************************************** O V E R R I D E S ******************************************** */

	public function save($fields, $return = 0)
	{
		$hostname	= gethostbyaddr($_SERVER['REMOTE_ADDR']);

		$data = array(
			'referer'	=> isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
			'useragent'	=> $_SERVER['HTTP_USER_AGENT'],
			'ip'		=> $_SERVER['REMOTE_ADDR'],
			'hostname'	=> $hostname,
			'session_id'=> Session::getId(),
		);
		return parent::save(array_merge($fields, $data), $return);
	}
}
