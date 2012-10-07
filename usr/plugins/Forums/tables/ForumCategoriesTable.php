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
	public $subQueries = array(
	);

	public $order	= array(
		'sort'	=> 'ASC',
	);

	public $hasMany = array(
		'Forum'	=> array(
			'table'			=> 'forum_forums',
			'plugin'		=> 'Forums',
			'primaryKey'	=> 'id',						# primary key in Category table
			'foreignKey'	=> 'fk_forum_category_id',		# Foreign key in Forum's table
			'conditions'	=> array(),
			'fields'		=> array('id', 'name', 'description', 'icon', 'seo_url'),
			'subQueries'	=> array(
				'thread_count'	=> 'SELECT COUNT(*) FROM forum_threads WHERE forum_threads.fk_forum_forums_id = Forum.id',
			),
			'order'			=> array('sort' => 'ASC'),
			'limit'			=> 5,
			'dependent'		=> false,
			'recursive'		=> array('hasMany' => array('LastThread')),	// only follow LastThread in $hasMany of ForumForumsTable | instead of TRUE (which follows all relations)
			'hasCreated'	=> 'datetime',
			'hasModified'	=> 'datetime',
		),
	);

}