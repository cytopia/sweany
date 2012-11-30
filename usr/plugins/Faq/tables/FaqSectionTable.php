<?php
class FaqSectionTable extends Table
{
	public $table	= 'faq_sections';
	public $alias	= 'Section';

	public $fields	= array(
		'id',
		'name',
		'sort',
	);
	public $subQueries = array(
	);

	public $order	= array(
		'Section.sort'	=> 'ASC',
	);

	public $hasMany = array(
		'Faq'	=> array(
			'table'			=> 'faq',
			'plugin'		=> 'Faq',
			'foreignKey'	=> 'fk_section_id',
			'fields'		=> array('id', 'question', 'answer', 'anchor'),
			'order'			=> array('Faq.sort' => 'ASC'),
		),
	);
}