<?php
class ForumForumsTable extends Table
{
	public $table	= 'forum_forums';

	protected $tableHolders	= array(
		'[[thread]]'	=> 'forum_threads',
		'[[post]]'		=> 'forum_posts',
		'[[user]]'		=> 'users',
	);

	public $fields	= array(
		// FIELDS
		'id'					=> 'id',
		'fk_forum_category_id'	=> 'fk_forum_category_id',
		'sort'					=> 'sort',
		'display'				=> 'display',
		'can_create'			=> 'can_create',
		'can_reply'				=> 'can_reply',
		'name'					=> 'name',
		'description'			=> 'description',
		'icon'					=> 'icon',
		'seo_url'				=> 'seo_url',
		'created'				=> 'created',
		'modified'				=> 'modified',

		// SUBSELECTS
//		'thread_count'			=> '(SELECT COUNT(*)	FROM [[thread]] WHERE fk_forum_forums_id = [[this]].id)',
//		'post_count'			=> '(SELECT COUNT(*)	FROM [[post]]	WHERE fk_forum_thread_id IN (SELECT id FROM [[thread]] WHERE fk_forum_forums_id = [[this]].id))',

//		'last_thread_id'		=> '(SELECT id			FROM [[thread]] WHERE fk_forum_forums_id = [[this]].id ORDER BY [[thread]].created DESC LIMIT 1)',
//		'last_thread_created'	=> '(SELECT created		FROM [[thread]] WHERE fk_forum_forums_id = [[this]].id ORDER BY [[thread]].created DESC LIMIT 1)',
//		'last_thread_title'		=> '(SELECT title		FROM [[thread]] WHERE fk_forum_forums_id = [[this]].id ORDER BY [[thread]].created DESC LIMIT 1)',
//		'last_thread_seo_url'	=> '(SELECT seo_url		FROM [[thread]] WHERE fk_forum_forums_id = [[this]].id ORDER BY [[thread]].created DESC LIMIT 1)',
//		'last_thread_user_id'	=> '(SELECT fk_user_id	FROM [[thread]] WHERE fk_forum_forums_id = [[this]].id ORDER BY [[thread]].created DESC LIMIT 1)',
//		'last_thread_username'	=> '(SELECT username	FROM [[user]]	WHERE [[user]].id = last_thread_user_id )',

//		'last_post_thread_id'	=> '(SELECT fk_forum_thread_id FROM [[post]] WHERE fk_forum_thread_id IN (SELECT id FROM [[thread]] WHERE fk_forum_forums_id = [[this]].id ) ORDER BY [[post]].created DESC LIMIT 1)',
//		'last_post_created'		=> '(SELECT created		FROM [[post]]	WHERE fk_forum_thread_id IN (SELECT id FROM [[thread]] WHERE fk_forum_forums_id = [[this]].id ) ORDER BY [[post]].created DESC LIMIT 1)',
//		'last_post_title'		=> '(SELECT title		FROM [[thread]]	WHERE id = last_post_thread_id)',
//		'last_post_seo_url'		=> '(SELECT seo_url		FROM [[thread]] WHERE id = last_post_thread_id)',
//		'last_post_user_id'		=> '(SELECT fk_user_id	FROM [[post]]	WHERE fk_forum_thread_id IN (SELECT id FROM [[thread]] WHERE fk_forum_forums_id = [[this]].id ) ORDER BY [[post]].created DESC LIMIT 1)',
//		'last_post_username'	=> '(SELECT username	FROM [[user]]	WHERE [[user]].id = last_post_user_id )',
	);


	/************************************************** GET FUNCTIONS **************************************************/

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
	}

	/**
	 *
	 * Get all posts/threads ordered by last submitted
	 * @param unknown_type $limit
	 */
	public function getLatestEntries($limit = 10)
	{
		$query = '';
	}

	/************************************************** CHECK FUNCTIONS **************************************************/

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
	}
}