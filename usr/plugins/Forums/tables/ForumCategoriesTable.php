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
			'plugin'		=> 'Forums',
			'primaryKey'	=> 'id',						# primary key in Category table
			'foreignKey'	=> 'fk_forum_category_id',		# Foreign key in Forum's table
			'conditions'	=> array(),
			'fields'		=> array('id', 'name', 'description', 'icon', 'seo_url'),
			'subQueries'	=> array(
				'thread_count'			=> 'SELECT COUNT(*) FROM forum_threads WHERE forum_threads.fk_forum_forums_id = Forum.id',
				'last_thread_id'		=> 'SELECT id		FROM forum_threads WHERE fk_forum_forums_id = Forum.id ORDER BY created DESC LIMIT 1',
				'last_thread_title'		=> 'SELECT title	FROM forum_threads WHERE fk_forum_forums_id = Forum.id ORDER BY created DESC LIMIT 1',
				'last_thread_seo_url'	=> 'SELECT seo_url	FROM forum_threads WHERE fk_forum_forums_id = Forum.id ORDER BY created DESC LIMIT 1',
				'last_thread_user_id'	=> 'SELECT fk_user_id FROM forum_threads WHERE fk_forum_forums_id = Forum.id ORDER BY created DESC LIMIT 1',
				'last_thread_username'	=> 'SELECT users.username FROM users WHERE users.id = (SELECT fk_user_id FROM forum_threads WHERE fk_forum_forums_id = Forum.id ORDER BY created DESC LIMIT 1)',
				'last_thread_created'	=> 'SELECT created FROM forum_threads WHERE fk_forum_forums_id = Forum.id ORDER BY created DESC LIMIT 1',
				'post_count'			=> 'SELECT COUNT(*) FROM forum_posts WHERE fk_forum_thread_id = (SELECT id		FROM forum_threads WHERE fk_forum_forums_id = Forum.id ORDER BY created DESC LIMIT 1)',
				'last_post_created'		=> 'SELECT last_post_created FROM forum_threads WHERE fk_forum_forums_id = Forum.id ORDER BY created DESC LIMIT 1',
			),
			'order'			=> array('sort' => 'ASC'),
			'limit'			=> array(),
			'dependent'		=> false,
			'recursive'		=> array('hasMany' => array('LastThread')),	// only follow LastThread in $hasMany of ForumForumsTable | instead of TRUE (which follows all relations)
			'hasCreated'	=> 'datetime',
			'hasModified'	=> 'datetime',
		),
	);
}