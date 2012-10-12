<?php
class ForumPostsTable extends Table
{
	public $table	= 'forum_posts';
	public $alias	= 'Post';

	public $hasModified	= array('modified' => 'integer');
	public $hasCreated	= array('created' => 'integer');

	public $fields	= array(
		// FIELDS
		'id',
		'fk_forum_thread_id',
		'thread_id'	=> 'fk_forum_thread_id',
		'fk_user_id',
		'user_id'	=> 'fk_user_id',
		'title',
		'body',
		'created',
		'modified',
	);

	// many to one
	public $belongsTo = array(
		'User' => array(
			'table'			=> 'users',
			'core'			=> true,
			'primaryKey'	=> 'id',
			'foreignKey'	=> 'fk_user_id',
//			'condition'		=> '',
			'fields'		=> array('id', 'username'),
//			'subQueries'	=> array(),
        ),
		'Thread' => array(
			'table'			=> 'forum_threads',
			'plugin'		=> 'Forums',
			'primaryKey'	=> 'id',
			'foreignKey'	=> 'fk_forum_thread_id',
//			'condition'		=> '',
			'fields'		=> array('id', 'title', 'fk_forum_forums_id', 'seo_url', 'is_locked', 'is_closed'),
//			'subQueries'	=> array(''),
        ),
    );

	/************************************************** GET FUNCTIONS **************************************************/
/*
	public function getMyPosts($user_id, $order = array('created' => 'ASC'), $limit_num = NULL)
	{
		$where	= sprintf('`fk_user_id` = %d', $user_id);
		return $this->_get(null, $where, null, $order, $limit_num);
	}
*/
	/************************************************** INSERT/UPDATE FUNCTIONS **************************************************/

	/**
	 *	@Override
	 */
	public function save($fields, $return = 1)
	{
		$fields['title']	= Strings::removeTags($fields['title']);

		$Post	= parent::save($fields, 2);

		// Update the thread's last_post time and id (so we can order by it to get the last entries)
		$updFields['last_post_created']	= $Post->created;
		$updFields['last_post_id']		= $Post->id;
		$this->db->updateRow('forum_threads', $updFields, $fields['fk_forum_thread_id']);

		return $Post->id;
	}
}