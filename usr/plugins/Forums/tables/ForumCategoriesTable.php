<?php
class ForumCategoriesTable extends Table
{
	public $table	= 'forum_categories';
	public $alias	= 'Category';

	public $fields	= array(
		'id'	=> 'id',
		'name'	=> 'name',
		'sort'	=> 'sort',
	);
	
	public $order	= array(
		'Category.sort'	=> 'ASC',
	);
	
	public $hasMany = array(
		'Forum'	=> array(
			'table'			=> 'forum_forums',
			'primaryKey'	=> 'id',						# primary key in Category table
			'foreignKey'	=> 'fk_forum_category_id',		# Foreign key in Forum's table
			'conditions'	=> array(),
			'fields'		=> array('id', 'name', 'description'),
			'subQueries'	=> array(),
			'order'			=> array('sort' => 'ASC'),
			'limit'			=> array(),
			'dependent'		=> false,
			'recursive'		=> false,
			'hasCreated'	=> 'datetime',
			'hasModified'	=> 'datetime',
		),
	);
}