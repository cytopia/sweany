<?php
class ForumThreadsTable extends Table
{
	public $table 	= 'forum_threads';
	public $alias	= 'Thread';

	public $hasModified	= array('modified' => 'integer');
	public $hasCreated	= array('created' => 'integer');


	public $fields	= array(
		// FIELDS
		'id',
		'fk_forum_forums_id',
		'forum_id' => 'fk_forum_forums_id',
		'fk_user_id',
		'user_id' => 'fk_user_id',
		'title',
		'body',
		'view_count',
		'is_sticky',
		'is_locked',
		'is_closed',
		'seo_url',
		'last_post_id',
		'last_post_created',
		'created',
		'modified'
	);

	public $subQueries	= array(
		'post_count'		=> 'SELECT COUNT(id) FROM forum_posts WHERE Thread.id=fk_forum_thread_id',
		'username'			=> 'SELECT username FROM users WHERE Thread.fk_user_id=users.id',
	);

	public $order = array('Thread.created' => 'DESC');

	// many to one
	public $belongsTo = array(
		'User' => array(
			'table'			=> 'users',
			'core'			=> true,
			'primaryKey'	=> 'id',			// foreign key in the current model
			'foreignKey'	=> 'fk_user_id',	// foreign key in the current model
//			'condition'		=> '',
			'fields'		=> array('id', 'username'),
			'subQueries'	=> array(
				'num_entries'	=> 'SELECT (SELECT COUNT(*) FROM forum_threads WHERE fk_user_id = User.id) + (SELECT COUNT(*) FROM forum_posts WHERE fk_user_id = User.id)'
			),
        ),
		'Forum' => array(
			'table'			=> 'forum_forums',
			'plugin'		=> 'Forums',
			'primaryKey'	=> 'id',					// foreign key in the current model
			'foreignKey'	=> 'fk_forum_forums_id',	// foreign key in the current model
//			'condition'		=> '',
			'fields'		=> array('id', 'name', 'seo_url', 'display', 'can_reply'),
			'subQueries'	=> array(),
        ),
    );
	// one to many
	public $hasMany = array(
		'Post'	=> array(
			'table'			=> 'forum_posts',					# Name of the sql table
			'plugin'		=> 'Forums',
			'foreignKey'	=> 'fk_forum_thread_id',			# Foreign key in other table (<table_name>) (defaults to: 'fk_<$this->table>_id')
			'condition'		=> '',								# String of conditions
			'fields'		=> array('id', 'title', 'body', 'fk_user_id', 'created', 'modified'),							# Array of fields to fetch
			'subQueries'	=> array(
				'username'		=> 'SELECT username FROM users WHERE users.id=Post.fk_user_id',
				'num_entries'	=> 'SELECT (SELECT COUNT(*) FROM forum_threads WHERE forum_threads.fk_user_id = Post.fk_user_id) + (SELECT COUNT(*) FROM forum_posts WHERE forum_posts.fk_user_id = Post.fk_user_id)',
			),
			'order'			=> array('Post.created'=>'ASC'),		# Array of order clauses on the given table
			'dependent'		=> false,
			'recursive'		=> true,							# true|false or array('hasMany' => array('Alias1', 'Alias2')) <= from Post-Table
		),

		'LastPost'	=> array(
			'table'			=> 'forum_posts',					# Name of the sql table
			'plugin'		=> 'Forums',
			'foreignKey'	=> 'fk_forum_thread_id',			# Foreign key in other table (<table_name>) (defaults to: 'fk_<$this->table>_id')
//			'condition'		=> '',								# String of conditions
			'fields'		=> array('id', 'title', 'body', 'fk_user_id', 'created'),							# Array of fields to fetch
			'subQueries'	=> array('username' => 'SELECT username FROM users WHERE users.id=LastPost.fk_user_id'),	# Array of subqueries to append
			'order'			=> array('LastPost.created'=>'DESC'),		# Array of order clauses on the given table
			'limit'			=> 1,
			'flatten'		=> true,							# As we only receive one element, we will flatten it down #data[0] to $data7
			'dependent'		=> false,
			'recursive'		=> true,							# true|false or array('hasMany' => array('Alias1', 'Alias2')) <= from Post-Table
		),
	);

	// one to one
	public $hasOne = array();

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
		return $this->find('all', array(
			'fields'	=> $fields,
			'order'		=> array('GREATEST(Thread.created, Thread.last_post_created)' => 'DESC'),
			'limit'		=> $limit,
			'relation'	=> array(
				'hasMany'	=> array('LastPost'),
				'belongsTo' => array('User', 'Forum')
			),
		));
	}

/*
	public function getLatestForumThread($forum_id)
	{
		$where	= sprintf("`fk_forum_forums_id` = %d", $forum_id);
		$thread		= $this->_get(NULL, $where, NULL, array('created' => 'DESC'));
		return isset($thread[0]) ? $thread[0] : array();
	}
*/
	// TODO: this might even be better for activity overview
	/*
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
	}*/

	// public function getAll
	/*
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
*/
	/************************************************** ADD/UPDATE FUNCTIONS **************************************************/
	
	/**
	 *	@Override
	 */
	public function save($fields, $return = 1)
	{
		$fields['title']	= Strings::removeTags($fields['title']);
		$fields['seo_url']	= Url::cleanUrlParams($fields['title']).'.html';
		
		return parent::save($fields, $return);
	}
/*
	public function incrementViewCount($thread_id)
	{
		$this->db->incrementField($this->table, 'view_count', sprintf("id = %d", $thread_id));
	}

	public function count($condition)
	{
		return $this->_count($condition);
	}
	*/
}
