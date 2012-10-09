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
			'foreignKey'	=> 'fk_forum_forums_id',		# Foreign key in Forum's table
			'condition'		=> '',
			'fields'		=> array('id', 'title', 'fk_user_id', 'seo_url', 'created', 'last_post_id', 'last_post_created'),
			'subQueries'	=> array(
				'username'		=> 'SELECT username		FROM users		 WHERE users.id = LastThread.fk_user_id',
				'post_count'	=> 'SELECT COUNT(*) 	FROM forum_posts WHERE forum_posts.id = LastThread.last_post_id',
				'post_title'	=> 'SELECT title		FROM forum_posts WHERE forum_posts.id = LastThread.last_post_id',
				'post_user_id'	=> 'SELECT fk_user_id	FROM forum_posts WHERE forum_posts.id = LastThread.last_post_id',
				'post_username'	=> 'SELECT username		FROM users		 WHERE users.id = (SELECT fk_user_id FROM forum_posts WHERE forum_posts.id = LastThread.last_post_id)',
			),
			'order'			=> array('GREATEST(LastThread.created, LastThread.last_post_created)'=>'DESC'),	# order by last thread or last post in thread
			'limit'			=> 1,							# Limit by one thread only
			'flatten'		=> true,
			'dependent'		=> false,
		),
		'Thread'	=> array(
			'table'			=> 'forum_threads',
			'plugin'		=> 'Forums',
			'primaryKey'	=> 'id',						# primary key in Category table
			'foreignKey'	=> 'fk_forum_forums_id',		# Foreign key in Forum's table
//			'condition'		=> '',
			'fields'		=> array('id', 'title', 'body', 'view_count', 'is_sticky', 'is_locked', 'is_closed', 'fk_user_id', 'seo_url', 'created', 'last_post_id', 'last_post_created'),
			'subQueries'	=> array(
				'username'		=> 'SELECT username		FROM users		 WHERE users.id = Thread.fk_user_id',
				'post_count'	=> 'SELECT COUNT(*) 	FROM forum_posts WHERE forum_posts.fk_forum_thread_id = Thread.id',
			),
			'order'			=> array(
				'Thread.is_sticky' => 'DESC',	# sticky threads first!!!
				'GREATEST(Thread.created, Thread.last_post_created)' => 'DESC'),	# 2nd order: threads or posts of threads with latest activity
//			'limit'			=> array(),
			'recursive'		=> array('hasMany' => array('LastPost')),	// only follow PostThread in $hasMany of ForumThreadsTable | instead of TRUE (which follows all relations)
			'dependent'		=> false,
		),
	);

	public $hasOne = array();



	/************************************************** GET FUNCTIONS **************************************************/
/*
	public function getAllByCat($category_id, $order = array('sort' => 'ASC'), $limit = NULL)
	{
		$where	= sprintf("`fk_forum_category_id` = %d AND `display` = 1", $category_id);

		return $this->_get(NULL, $where, NULL, $order, $limit);
	}

	public function getName($forum_id)
	{
		return $this->getField($forum_id, 'name');
	}

	public function getSeoUrl($forum_id)
	{
		return $this->getField($forum_id, 'seo_url');
	}*/

	/**
	 *
	 * Get all posts/threads ordered by last submitted
	 * @param unknown_type $limit
	 */
	/*
	public function getLatestEntries($limit = 10)
	{
		$query = '';
	}*/

	/************************************************** CHECK FUNCTIONS **************************************************/
/*
	public function isDisplayable($forum_id)
	{
		return $this->getField($forum_id, 'display');
	}

	public function canCreate($forum_id)
	{
		return $this->getField($forum_id, 'can_create');
	}

	public function canReply($forum_id)
	{
		return $this->getField($forum_id, 'can_reply');
	}*/
}