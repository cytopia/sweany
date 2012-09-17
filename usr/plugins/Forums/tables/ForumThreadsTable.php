<?php
class ForumThreadsTable extends Table
{
	protected $table 	= 'forum_threads';

	protected $tableHolders	= array(
		'[[forum]]'		=> 'forum_forums',
		'[[post]]'		=> 'forum_posts',
		'[[user]]'		=> 'users',
	);

	protected $fields	= array(
		// FIELDS
		'id'					=> 'id',
		'fk_forum_forums_id'	=> 'fk_forum_forums_id',
		'fk_user_id'			=> 'fk_user_id',
		'title'					=> 'title',
		'body'					=> 'body',
		'view_count'			=> 'view_count',
		'is_sticky'				=> 'is_sticky',
		'is_locked'				=> 'is_locked',
		'is_closed'				=> 'is_closed',
		'seo_url'				=> 'seo_url',
		'last_post_id'			=> 'last_post_id',
		'last_post_created'		=> 'last_post_created',
		'created'				=> 'created',
		'modified'				=> 'modified',

		// SUBSELECTS
		'last_post_title'		=> '(SELECT title		FROM [[post]]	WHERE fk_forum_thread_id=[[this]].id ORDER BY created DESC LIMIT 1)',
		'last_post_body'		=> '(SELECT body		FROM [[post]]	WHERE fk_forum_thread_id=[[this]].id ORDER BY created DESC LIMIT 1)',
		'last_post_user_id'		=> '(SELECT fk_user_id	FROM [[post]]	WHERE fk_forum_thread_id=[[this]].id ORDER BY created DESC LIMIT 1)',
		'last_post_username'	=> '(SELECT username	FROM [[user]]	WHERE id=last_post_user_id)',
		'count_posts'			=> '(SELECT COUNT(*)	FROM [[post]]	WHERE fk_forum_thread_id=[[this]].id )',
		'forum_name'			=> '(SELECT name		FROM [[forum]]	WHERE id = fk_forum_forums_id)',
		'username'				=> '(SELECT username	FROM [[user]]	WHERE id=[[this]].fk_user_id)',
	);

	/************************************************** GET FUNCTIONS **************************************************/


	/**
	 *
	 * Gets the latest threads ordered by
	 * latest created and latest post (like bsd f)
	 * This can be used for an overview page
	 * to Always have the most active thread up
	 */
	public function getLatestActiveThreads($fields = null, $limit = 10)
	{
		return $this->_get($fields, null, null, array('GREATEST(created, last_post_created)' => 'DESC'), $limit);
	}


	public function getLatestForumThread($forum_id)
	{
		$where	= sprintf("`fk_forum_forums_id` = %d", $forum_id);
		$thread		= $this->_get(NULL, $where, NULL, array('created' => 'DESC'));
		return isset($thread[0]) ? $thread[0] : array();
	}

	// TODO: this might even be better for activity overview
	public function getLatestEntries()
	{
		$aliases	= array(
			'(SELECT IF((SELECT forum_posts.id FROM forum_posts WHERE forum_posts.fk_forum_thread_id = forum_threads.id ORDER BY created DESC LIMIT 1), 1,0))' => 'is_post',
			'(SELECT forum_posts.id FROM forum_posts WHERE forum_posts.fk_forum_thread_id = forum_threads.id ORDER BY created DESC LIMIT 1)' => 'post_id',
			'(SELECT IFNULL((SELECT forum_posts.username FROM forum_posts WHERE forum_posts.fk_forum_thread_id = forum_threads.id ORDER BY created DESC LIMIT 1), username))' => 'username',
			'(SELECT IFNULL((SELECT forum_posts.created FROM forum_posts WHERE forum_posts.fk_forum_thread_id = forum_threads.id ORDER BY created DESC LIMIT 1), created))' => 'created',
			'(SELECT name FROM forum_forums WHERE id = fk_forum_forums_id)' => 'forum_name',
			'id'					=> 'thread_id',
			'username'				=> 'thread_username',
			'created'				=> 'thread_created',
			'fk_forum_forums_id'	=> 'forum_id',
		);
		$this->aliases = array_merge($aliases, $this->aliases);
		return $this->_get();
	}

	// public function getAll
	public function getByForum($forum_id, $limit = null)
	{
		$where	= sprintf("`fk_forum_forums_id` = %d", $forum_id);

		return $this->_get(NULL, $where, NULL, array('is_sticky' => 'DESC', 'last_post_created' => 'DESC'), $limit);
	}


	public function getSeoUrl($thread_id)
	{
		return $this->getField($thread_id, 'seo_url');
	}

	public function isMyThread($thread_id, $user_id)
	{
		$where = sprintf('`id` = %d AND `fk_user_id` = %d', $thread_id, $user_id);
		return $this->_count($where);
	}
	public function countUserThreads($user_id)
	{
		return $this->_count(sprintf('`fk_user_id` = %d', $user_id));
	}

	/************************************************** ADD/UPDATE FUNCTIONS **************************************************/
	public function add($forum_id, $title, $body, $user_id, $seo_url)
	{
		$fields	= array(
			'fk_forum_forums_id'	=> $forum_id,
			'fk_user_id'			=> $user_id,
			'title'					=> $title,
			'body'					=> $body,
			'seo_url'				=> $seo_url,
		);
		return $this->_add($fields);
	}
	public function update($thread_id, $body)
	{
		return $this->_updateRow($thread_id, array('body' => $body));
	}

	public function incrementViewCount($thread_id)
	{
		$this->db->incrementField($this->table, 'view_count', sprintf("id = %d", $thread_id));
	}

	public function count($condition)
	{
		return $this->_count($condition);
	}
}
