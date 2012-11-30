<?php
class ForumsModel extends PageModel
{
	protected $tables	= array('Forums' => array('ForumCategories', 'ForumForums', 'ForumThreads', 'ForumPosts'));


	/************************************************** GET FUNCTIONS **************************************************/

	public function getMessageBBCodeIconBar($htmlMessageBoxId)
	{
		$box = '<a title="bold text" onClick="insertBBTag(\''.$htmlMessageBoxId.'\',\'[b]\',\'[/b]\');"><img class="bbCodeIcons" src="/plugins/Forums/img/text/bold.png" alt="bold" /></a>';
		$box.= '<a title="italic text" onClick="insertBBTag(\''.$htmlMessageBoxId.'\',\'[i]\',\'[/i]\');"><img class="bbCodeIcons" src="/plugins/Forums/img/text/italic.png" alt="italic" /></a>';
		$box.= '<a title="underlined text" onClick="insertBBTag(\''.$htmlMessageBoxId.'\',\'[u]\',\'[/u]\');"><img class="bbCodeIcons" src="/plugins/Forums/img/text/underline.png" alt="underline" /></a>';
		$box.= '<a title="striked through text" onClick="insertBBTag(\''.$htmlMessageBoxId.'\',\'[s]\',\'[/s]\');"><img class="bbCodeIcons" src="/plugins/Forums/img/text/strike.png" alt="strike through" /></a>';
		$box.= '<a title="insert link" onClick="document.getElementById(\''.$htmlMessageBoxId.'\').value+=add_link();"><img class="bbCodeIcons" src="/plugins/Forums/img/text/link.png" alt="link" /></a>';
		$box.= '<a title="insert picture" onClick="document.getElementById(\''.$htmlMessageBoxId.'\').value+=add_img();"><img class="bbCodeIcons" src="/plugins/Forums/img/text/image.png" alt="picture" /></a>';
		$box.= '<a title="insert code block" onClick="insertBBTag(\''.$htmlMessageBoxId.'\',\'[code]\',\'[/code]\');"><img class="bbCodeIcons" src="/plugins/Forums/img/text/code.png" alt="code block" /></a>';

		$box.= '<a style="float:left;">&nbsp;&nbsp;|&nbsp;&nbsp;</a>';

		$box.= '<a title="smile" onClick="insertBBTag(\''.$htmlMessageBoxId.'\',\':)\',\'\');"><img class="bbCodeIcons" src="/plugins/Forums/img/smiley/smile.png" alt="smile" /></a>';
		$box.= '<a title="grin" onClick="insertBBTag(\''.$htmlMessageBoxId.'\',\':D\',\'\');"><img class="bbCodeIcons" src="/plugins/Forums/img/smiley/grin.png" alt="grin" /></a>';
		$box.= '<a title="roll eyes" onClick="insertBBTag(\''.$htmlMessageBoxId.'\',\':roll:\',\'\');"><img class="bbCodeIcons" src="/plugins/Forums/img/smiley/roll.png" alt="roll eyes" /></a>';
		$box.= '<a title="unhappy" onClick="insertBBTag(\''.$htmlMessageBoxId.'\',\':(\',\'\');"><img class="bbCodeIcons" src="/plugins/Forums/img/smiley/unhappy.png" alt="unhappy" /></a>';
		$box.= '<a title="show tongue" onClick="insertBBTag(\''.$htmlMessageBoxId.'\',\':p\',\'\');"><img class="bbCodeIcons" src="/plugins/Forums/img/smiley/tongue.png" alt="show tongue" /></a>';
		$box.= '<a title="cry" onClick="insertBBTag(\''.$htmlMessageBoxId.'\',\':cry:\',\'\');"><img class="bbCodeIcons" src="/plugins/Forums/img/smiley/cry.png" alt="cry" /></a>';
		$box.= '<a title="blush" onClick="insertBBTag(\''.$htmlMessageBoxId.'\',\':red:\',\'\');"><img class="bbCodeIcons" src="/plugins/Forums/img/smiley/red.png" alt="blush" /></a>';
		$box.= '<a title="confused" onClick="insertBBTag(\''.$htmlMessageBoxId.'\',\':confuse:\',\'\');"><img class="bbCodeIcons" src="/plugins/Forums/img/smiley/confuse.png" alt="confused" /></a>';
		return $box;
	}



	/************************************************** UPDATE FUNCTIONS **************************************************/

	public function updateThreadView($thread_id)
	{
		$this->ForumThreads->increment($thread_id, array('view_count'));
	}

	/************************************************** CHECK FUNCTIONS **************************************************/
	public function isMyPost($post_id, $user_id)
	{
		return $this->ForumPosts->isMyPost($post_id, $user_id);
	}
	public function isMyThread($thread_id, $user_id)
	{
		return $this->ForumThreads->isMyThread($thread_id, $user_id);
	}
}
