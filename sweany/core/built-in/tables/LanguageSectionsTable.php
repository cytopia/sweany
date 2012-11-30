<?php
class LanguageSectionsTable extends Table
{
	// TABLE
	public $table;
	public $alias	= 'Section';

	// FIELDS
	public $fields	= array(
		'id',
		'group',
		'url',
	);

	/**
	 *	Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->table = \Sweany\Settings::tblLangSections;
	}


	/************************************************** OVERRIDES **************************************************/
}
