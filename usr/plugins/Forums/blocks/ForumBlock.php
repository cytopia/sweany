<?php
class ForumBlock extends BlockController
{
	protected $plugin			= 'Forums';
	private $userProfileCtl		= 'Profile';
	private $userProfileMethod	= 'show';

	/**
	 *
	 * Renders a bsdforen.de like overview of the forum
	 */
	public function ActivityOverview($numEntries = 10)
	{
		$tblThreads = Loader::loadPluginTable('ForumThreads', $this->plugin);

		$forumThreads = $tblThreads->getLatestActiveThreads(null, $numEntries);

		$this->set('language', $this->language);
		$this->set('userProfileCtl', $this->userProfileCtl);
		$this->set('userProfileMethod', $this->userProfileMethod);

		$this->set('forumThreads', $forumThreads);
		$this->view('activity_overview.tpl.php');
	}

	public function latestPostsByUser($user_id, $numEntries = 10)
	{
		$tblPosts	= Loader::loadPluginTable('ForumPosts', $this->plugin);
		$posts		= $tblPosts->getMyPosts($user_id, array('created' => 'ASC'), $numEntries);

		$this->set('language', $this->language);
		$this->set('posts', $posts);
		$this->view('latest_posts_by_user.tpl.php');
	}

	public function onlineUsers()
	{
		$this->set('language', $this->language);
		$this->set('countOnlineUsers', $this->user->countOnlineUsers());
		$this->set('countLoggedInOnlineUsers', $this->user->countLoggedInOnlineUsers());
		$this->set('countAnonymousOnlineUsers', $this->user->countAnonymousOnlineUsers());
		$this->set('LoggedInOnlineUsers', $this->user->getLoggedInOnlineUsers());

		$this->set('userProfileCtl', $this->userProfileCtl);
		$this->set('userProfileMethod', $this->userProfileMethod);
		$this->view('online_users.tpl.php');
	}
}