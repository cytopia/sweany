<?php
class ForumBlock extends BlockController
{
	protected $plugin			= 'Forums';

	private $userProfileLink	= false;
	private $userProfileCtl;
	private $userProfileMethod;

	public function __construct()
	{
		parent::__construct();

		// Controller Defines needed to build <href> links in the views
		$this->userProfileLink		= Config::get('userProfileLinkEnable', 'forum');
		$this->userProfileCtl		= Config::get('userProfileCtl', 'forum');
		$this->userProfileMethod	= Config::get('userProfileMethod', 'forum');
	}

	/**
	 *
	 * Renders a bsdforen.de like overview of the forum
	 */
	public function ActivityOverview($numEntries = 10, $detailed = true)
	{
		$tblThreads = Loader::loadPluginTable('ForumThreads', $this->plugin);

		$forumThreads = $tblThreads->getLatestActiveThreads(null, $numEntries);

		$this->set('language', $this->language);
		$this->set('userProfileLink', $this->userProfileLink);
		$this->set('userProfileCtl', $this->userProfileCtl);
		$this->set('userProfileMethod', $this->userProfileMethod);

		$this->set('forumThreads', $forumThreads);

		if ( $detailed )
			$this->view('activity_overview');
		else
			$this->view('activity_overview_list');
	}

	/**
	 *
	 * Enter description here ...
	 * @param	integer	$numEntries		number of entries to show
	 * @param	string	$css			custom css class to use
	 */
	public function latestEntryList($numEntries, $css = null)
	{
		$tblThreads	= Loader::loadPluginTable('ForumThreads', $this->plugin);
		$entries	= $tblThreads->getLatestActiveThreads(null, $numEntries);

		$this->set('entries', $entries);
		$this->set('css', $css);
		$this->set('userProfileLink', $this->userProfileLink);
		$this->set('userProfileCtl', $this->userProfileCtl);
		$this->set('userProfileMethod', $this->userProfileMethod);
		$this->view('latest_entry_list');
	}

	/**
	 * Renders the latest news forum entry
	 *
	 * @param integer $forum_id Specifies the id of the news ForumPosts
	 * @return integer Success	If the news Forum does not have any entries, returns 0, otherwise 1
	 */
	public function NewsOverview($numEntries, $forum_id)
	{
		$tblThreads	= Loader::loadPluginTable('ForumThreads', $this->plugin);
		$threads	= $tblThreads->getByForum($forum_id, $numEntries);

		$this->set('language', $this->language);
		$this->set('userProfileLink', $this->userProfileLink);
		$this->set('userProfileCtl', $this->userProfileCtl);
		$this->set('userProfileMethod', $this->userProfileMethod);
		$this->set('threads', $threads);


		$this->view('news_overview');

		return count($threads) ? 1 : 0;
	}


	public function latestPostsByUser($user_id, $numEntries = 10)
	{
		$tblPosts	= Loader::loadPluginTable('ForumPosts', $this->plugin);
		$posts		= $tblPosts->getMyPosts($user_id, array('created' => 'ASC'), $numEntries);

		$this->set('language', $this->language);
		$this->set('posts', $posts);
		$this->view('latest_posts_by_user');
	}

	public function onlineUsers()
	{
		$this->set('language', $this->language);
		$this->set('countOnlineUsers', $this->user->countOnlineUsers());
		$this->set('countLoggedInOnlineUsers', $this->user->countLoggedInOnlineUsers());
		$this->set('countAnonymousOnlineUsers', $this->user->countAnonymousOnlineUsers());
		$this->set('LoggedInOnlineUsers', $this->user->getLoggedInOnlineUsers());

		$this->set('userProfileLink', $this->userProfileLink);
		$this->set('userProfileCtl', $this->userProfileCtl);
		$this->set('userProfileMethod', $this->userProfileMethod);
		$this->view('online_users');
	}
}
