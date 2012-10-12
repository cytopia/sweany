<?php
class ForumForumsTable extends Table
{
	public $table	= 'forum_forums';
	public $alias	= 'Forum';

	public $fields	= array(
		'id',
		'fk_forum_category_id',
		'category_id'	=> 'fk_forum_category_id',	// Alias for better readability
		'sort',
		'display',
		'can_create',
		'can_reply',
		'name',
		'description',
		'icon',
		'seo_url',
		'created',
		'modified',
	);

	public $hasMany = array(
		'LastThread'	=> array(
			'table'			=> 'forum_threads',
			'plugin'		=> 'Forums',
			'foreignKey'	=> 'fk_forum_forums_id',
			'condition'		=> '',
			'fields'		=> array('id', 'title', 'fk_user_id', 'seo_url', 'created', 'last_post_id', 'last_post_created'),
			'subQueries'	=> array(
				'username'		=> 'SELECT username		FROM users		 WHERE users.id = LastThread.fk_user_id',
				'post_count'	=> 'SELECT COUNT(*) 	FROM forum_posts WHERE forum_posts.id = LastThread.last_post_id',
				'post_title'	=> 'SELECT title		FROM forum_posts WHERE forum_posts.id = LastThread.last_post_id',
				'post_user_id'	=> 'SELECT fk_user_id	FROM forum_posts WHERE forum_posts.id = LastThread.last_post_id',
				'post_username'	=> 'SELECT username		FROM users		 WHERE users.id = (SELECT fk_user_id FROM forum_posts WHERE forum_posts.id = LastThread.last_post_id)',
			),
			'order'			=> array('GREATEST(LastThread.created, LastThread.last_post_created)'=>'DESC'),
			'limit'			=> 1,
			'flatten'		=> true,
			'dependent'		=> false,
		),
		'Thread'	=> array(
			'table'			=> 'forum_threads',
			'plugin'		=> 'Forums',
			'foreignKey'	=> 'fk_forum_forums_id',
			'fields'		=> array('id', 'title', 'body', 'view_count', 'is_sticky', 'is_locked', 'is_closed', 'fk_user_id', 'seo_url', 'created', 'last_post_id', 'last_post_created'),
			'subQueries'	=> array(
				'username'		=> 'SELECT username		FROM users		 WHERE users.id = Thread.fk_user_id',
				'post_count'	=> 'SELECT COUNT(*) 	FROM forum_posts WHERE forum_posts.fk_forum_thread_id = Thread.id',
			),
			'order'	 		=> array(
				'Thread.is_sticky' => 'DESC',
				'GREATEST(Thread.created, Thread.last_post_created)' => 'DESC',
			),
			'recursive'		=> array(
				'hasMany'				=> array('LastPost'),
				'hasAndBelongsToMany'	=> array('UserHasRead'),
			),
			'dependent'		=> false,
		),
	);

	public $hasOne = array();
}