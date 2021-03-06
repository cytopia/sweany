<?php
class VisitorsTable extends Table
{
	// TABLE
	public $table;
	public $alias	= 'Visitor';

	// FIELDS
	public $fields = array(
		'id',
		'url',
		'referer',
		'useragent',
		'ip',
		'host',
		'session_id',
		'created',
		'fk_user_id'
	);

	// AUTO FIELDS
	public $hasCreated	= array(
		'created'		=> 'integer',		// store current unix timestamp on save()
	);

	public $order = array('Visitor.created' => 'DESC');


	/* ******************************************** O V E R R I D E S ******************************************** */

	/**
	 *	Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->table = \Sweany\Settings::tblVisitors;
	}


	public function beforeSave(&$data)
	{
		$data['url']		= \Sweany\Url::getRequest();
		$data['referer']	= isset($_SERVER['HTTP_REFERER'])	? $_SERVER['HTTP_REFERER']		: '';
		$data['useragent']	= isset($_SERVER['HTTP_USER_AGENT'])? $_SERVER['HTTP_USER_AGENT']	: '';
		$data['ip']			= isset($_SERVER['REMOTE_ADDR'])	? $_SERVER['REMOTE_ADDR']		: '';
		$data['host']		= isset($_SERVER['REMOTE_ADDR'])	? gethostbyaddr($_SERVER['REMOTE_ADDR']) : '';
		$data['session_id']	= \Sweany\Session::id();
	}

}
