<?php
class EmailsTable extends Table
{
	// TABLE
	public $table 	= 'emails';
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
	
	// AUTO FIELDS
	public $hasCreated	= array('created' => 'integer');
}
