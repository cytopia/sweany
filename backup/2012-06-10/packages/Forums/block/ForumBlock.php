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


}