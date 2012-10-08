<?php
class ForumPostsTable extends Table
{
	public $table	= 'forum_posts';
	public $alias	= 'Post';

	protected $hasModified	= array('modified' => 'datetime');
	protected $hasCreated	= array('created' => 'datetime');	
	
	public $fields	= array(
		// FIELDS
		'id'					=> 'id',
		'fk_forum_thread_id'	=> 'fk_forum_thread_id',
		'fk_user_id'			=> 'fk_user_id',
		'title'					=> 'title',
		'body'					=> 'body',
		'created'				=> 'created',
		'modified'				=> 'modified',
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
			'dependent'		=> false,
        ),
		'Thread' => array(
			'table'			=> 'forum_threads',
			'plugin'		=> 'Forums',
			'primaryKey'	=> 'id',
			'foreignKey'	=> 'fk_forum_thread_id',
//			'condition'		=> '',
			'fields'		=> array('id', 'title', 'fk_forum_forums_id', 'seo_url', 'is_locked', 'is_closed'),
//			'subQueries'	=> array(''),
			'dependent'		=> false,
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
		$updFields['last_post_created']	= $Post->Post->created;
		$updFields['last_post_id']		= $Post->Post->id;
		$this->db->updateRow('forum_threads', $updFields, $fields['fk_forum_thread_id']);
		
		return $Post->Post->id;
	}
}