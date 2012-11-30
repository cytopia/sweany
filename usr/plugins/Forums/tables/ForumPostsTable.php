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
			'table'			=> 'core_users',
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

	public function countUserPosts($user_id)
	{
		$condition = array('`fk_user_id` = :uid', array(':uid' => $user_id));
		return $this->count($condition);
	}

	/************************************************** INSERT/UPDATE FUNCTIONS **************************************************/

	/**
	 *	@Override
	 */
	public function beforeSave(&$data)
	{
		$data['title']		= Strings::removeTags($data['title']);
	}
	public function afterSave($Post)
	{
		$Thread = Loader::loadTable('ForumThreads', 'Forums');
		$tid	= $Post->fk_forum_thread_id;
		
		$data['last_post_created']	= $Post->created;
		$data['last_post_id']		= $Post->id;
		
		$Thread->update($tid, $data);
	}
}