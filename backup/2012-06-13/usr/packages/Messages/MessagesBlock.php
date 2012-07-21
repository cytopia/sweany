<?php
class MessagesBlock extends BlockController
{


	/**
	 *
	 * Renders a bsdforen.de like overview of the forum
	 */
	public function view_getUnreadMessage($user_id)
	{
		$numMessages = $this->model->UserMessages->countMyUnreadInboxMessages($user_id);

		$this->set('numMessages', $numMessages);
		$this->view('unread_messages.tpl.php');
	}


}