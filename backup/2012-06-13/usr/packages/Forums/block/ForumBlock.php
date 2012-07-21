<?php
class ForumBlock extends Block
{

	/**
	 * 
	 * Renders a bsdforen.de like overview of the forum
	 */
	public function ActivityOverview($numEntries = 10)
	{
		$tblThreads = Loader::loadTable('ForumThreads', 'Forums');
		
		$forumThreads = $tblThreads->getLatestActiveThreads(null, $numEntries);
	
		$this->set('forumThreads', $forumThreads);
		$this->view('activity_overview.tpl.php');
	}
	
	public function myLatestPosts($user_id, $numEntries = 10)
	{
		$tblPosts	= Loader::loadTable('ForumPosts', 'Forums');
		$myPosts	= $tblPosts->getMyPosts($user_id, array('created' => 'ASC'), $numEntries);
		
		$this->set('myLatestPosts', $myPosts);
		$this->view('my_latest_posts.tpl.php');
	}


}