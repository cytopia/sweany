<?php
class ForumCategoriesTable extends Table
{
	public $table	= 'forum_categories';
	public $alias	= 'Category';

	public $fields	= array(
		'id',
		'name',
		'sort',
	);
	public $subQueries = array(
	);

	public $order	= array(
		'Category.sort'	=> 'ASC',
	);

	public $hasMany = array(
		'Forum'	=> array(
			'table'			=> 'forum_forums',
			'plugin'		=> 'Forums',
			'foreignKey'	=> 'fk_forum_category_id',		# Foreign key in Forum's table
			'condition'		=> '',
			'fields'		=> array('id', 'name', 'description', 'icon', 'seo_url'),
			'subQueries'	=> array(
				'thread_count'	=> 'SELECT COUNT(*) FROM forum_threads WHERE forum_threads.fk_forum_forums_id = Forum.id',
				'post_count'	=> 'SELECT COUNT(*) FROM forum_posts WHERE forum_posts.fk_forum_thread_id IN (SELECT id FROM forum_threads WHERE forum_threads.fk_forum_forums_id = Forum.id)',
			),
			'order'			=> array('Forum.sort' => 'ASC'),
//			'limit'			=> 5,
			'dependent'		=> false,
			'recursive'		=> array('hasMany' => array('LastThread')),	// only follow LastThread in $hasMany of ForumForumsTable | instead of TRUE (which follows all relations)
		),
	);
}