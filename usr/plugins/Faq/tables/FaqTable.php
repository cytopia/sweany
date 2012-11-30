<?php
class FaqTable extends Table
{
	public $table	= 'faq';
	public $alias	= 'Faq';

	public $fields	= array(
		'id',
		'question',
		'answer',
		'anchor',
		'fk_section_id',
		'sort',
	);
	public $subQueries = array(
	);

	public $order	= array(
		'Faq.sort'	=> 'ASC',
	);
}