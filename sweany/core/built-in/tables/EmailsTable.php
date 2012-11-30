<?php
class EmailsTable extends Table
{
	// TABLE
	public $table;
	public $alias	= 'Email';

	// FIELDS
	public $fields	= array(
		'id',
		'recipient',
		'headers',
		'subject',
		'message',
		'created',
	);

	public $order = array('Email.created' => 'DESC');

	// AUTO FIELDS
	public $hasCreated	= array('created' => 'integer');

	/**
	 *	Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->table = \Sweany\Settings::tblEmails;
	}

}
