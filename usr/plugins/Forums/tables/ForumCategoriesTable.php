<?php
class ForumCategoriesTable extends Table
{
	protected $table	= 'forum_categories';

	protected $fields	= array(
		'id'	=> 'id',
		'name'	=> 'name',
		'sort'	=> 'sort',
	);
}