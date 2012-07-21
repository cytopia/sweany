<?php
class ForumBlock extends BlockController
{
	private $userProfileCtl		= 'Profile';
	private $userProfileMethod	= 'show';

	/**
	 * 
	 * Renders a bsdforen.de like overview of the forum
	 */
	public function ActivityOverview($numEntries = 10)
	{
		$tblThreads = Loader::loadTable('ForumThreads');
		
		$forumThreads = $tblThreads->getLatestActiveThreads(null, $numEntries);
	
		$this->set('userProfileCtl', $this->userProfileCtl);
		$this->set('userProfileMethod', $this->userProfileMethod);

		$this->set('forumThreads', $forumThreads);
		$this->view('activity_overview.tpl.php');
	}
	
	public function myLatestPosts($user_id, $numEntries = 10)
	{
		$tblPosts	= Loader::loadTable('ForumPosts');
		$myPosts	= $tblPosts->getMyPosts($user_id, array('created' => 'ASC'), $numEntries);
		
		$this->set('myLatestPosts', $myPosts);
		$this->view('my_latest_posts.tpl.php');
	}


}