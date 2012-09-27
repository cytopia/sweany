<?php
class ForumPostsTable extends Table
{
	public $table	= 'forum_posts';

	protected $tableHolders	= array(
		'[[forum]]'		=> 'forum_forums',
		'[[thread]]'	=> 'forum_threads',
		'[[post]]'		=> 'forum_posts',
		'[[user]]'		=> 'users',
	);
	public $fields	= array(
		// FIELDS
		'id'					=> 'id',
		'fk_forum_thread_id'	=> 'fk_forum_thread_id',
		'fk_user_id'			=> 'fk_user_id',
		'title'					=> 'title',
		'body'					=> 'body',
		'created'				=> 'created',
		'modified'				=> 'modified',

		// POST SUBSELECTS
//		'username'				=> '(SELECT username	FROM [[user]]	WHERE [[user]].id = [[this]].fk_user_id)',

		// THREAD SUBSELECTS
//		'thread_title'			=> '(SELECT title		FROM [[thread]]	WHERE [[thread]].id = fk_forum_thread_id)',
//		'thread_seo_url'		=> '(SELECT seo_url		FROM [[thread]] WHERE [[thread]].id = fk_forum_thread_id)',
//		'thread_user_id'		=> '(SELECT id			FROM [[user]]	WHERE [[user]].id = (SELECT [[thread]].fk_user_id FROM [[thread]] WHERE [[thread]].id = [[this]].fk_forum_thread_id))',
//		'thread_user_name'		=> '(SELECT username	FROM [[user]]	WHERE [[user]].id = (SELECT [[thread]].fk_user_id FROM [[thread]] WHERE [[thread]].id = [[this]].fk_forum_thread_id))',

		// FORUM SUBSELECTS
//		'forum_id'				=> '(SELECT id    		FROM [[forum]]	WHERE id = (SELECT fk_forum_forums_id FROM [[thread]] WHERE id = fk_forum_thread_id))',
//		'forum_name'			=> '(SELECT name  		FROM [[forum]]	WHERE id = (SELECT fk_forum_forums_id FROM [[thread]] WHERE id = fk_forum_thread_id))',
	);


	/************************************************** GET FUNCTIONS **************************************************/

	public function getPosts($thread_id, $order = array('created' => 'ASC'), $limit_num = NULL)
	{
		$where	= sprintf("`fk_forum_thread_id` = %d", $thread_id);

		return $this->_get(NULL, $where, NULL, $order, $limit_num);
	}


	public function getMyPosts($user_id, $order = array('created' => 'ASC'), $limit_num = NULL)
	{
		$where	= sprintf('`fk_user_id` = %d', $user_id);
		return $this->_get(null, $where, null, $order, $limit_num);
	}
	/**
	 *  Returns everything where I did open a thread or
	 *  reply with a post
	 *
	 */
	public function getMyEntries($user_id, $order = array('created' => 'ASC'), $limit_num = NULL)
	{
	}

	public function isMyPost($post_id, $user_id)
	{
		$where = sprintf('`id` = %d AND `fk_user_id` = %d', $post_id, $user_id);
		return $this->_count($where);
	}

	public function countUserPosts($user_id)
	{
		return $this->_count(sprintf('`fk_user_id` = %d', $user_id));
	}

	/************************************************** INSERT/UPDATE FUNCTIONS **************************************************/
	public function add($thread_id, $title, $body, $user_id)
	{
		$fields	= array(
			'fk_forum_thread_id'	=> $thread_id,
			'title'					=> $title,
			'body'					=> $body,
			'fk_user_id'			=> $user_id,
		);
		$post_id =  $this->_add($fields);

		$db = \Core\Init\CoreDatabase::$db;
		// Update the thread's last_post time and id (so we can order by it to get the last entries)
		$this->db->updateRow('forum_threads', array('last_post_created' => $this->getField($post_id, 'created')), $thread_id);
		$this->db->updateRow('forum_threads', array('last_post_id' => $post_id), $thread_id);

		return $post_id;
	}
}