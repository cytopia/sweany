<?php
class MessageBlock extends BlockController
{
	protected $plugin			= 'Message';

	public function getMyNewMessageCountLink()
	{
		$Message		= Loader::loadPluginTable('UserMessages', 'Message');
		$Alert			= Loader::loadPluginTable('UserAlerts', 'Message');
		$num_messages	= $Message->countMyUnreadInboxMessages($this->core->user->id());
		$num_alerts		= $Alert->countMyUnreadInboxAlerts($this->core->user->id());

		$this->render	= false;

		if ( $num_messages == 0 && $num_alerts == 0 )
		{
			return Html::l($this->core->language->noNewMessages, 'Message', 'inbox');
		}
		else if ( $num_messages )
		{
			if ( $num_messages == 1)
			{
				return Html::l($num_messages.' '.$this->core->language->aNewMessage, 'Message', 'inbox');
			}
			else
			{
				return Html::l($num_messages.' '.$this->core->language->NewMessages, 'Message', 'inbox');
			}
		}
		else
		{
			if ( $num_alerts == 1)
			{
				return Html::l($num_alerts.' '.$this->core->language->aNewAlert, 'Message', 'alerts');
			}
			else
			{
				return Html::l($num_alerts.' '.$this->core->language->NewAlerts, 'Message', 'alerts');
			}
		}
	}
}

