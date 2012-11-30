<?php
class UserMessagesTable extends Table
{
	public $table	= 'user_messages';

	public $alias	= 'Message';

	public $fields	= array(
		'id',
		'fk_reply_id',
		'fk_from_user_id',
		'fk_to_user_id',
		'subject',
		'message',
		'flag_prio_low',
		'flag_prio_medium',
		'flag_prio_high',
		'is_read',
		'is_answered',
		'is_received_archived',
		'is_received_trashed',
		'is_received_deleted',
		'is_send_deleted',
		'can_reply',
		'read_count',
		'first_read',
		'created',
	);

	public $subQueries = array(
		'from_username'	=> 'SELECT `username` FROM `%s` AS User WHERE User.`id` = Message.`fk_from_user_id`',
		'to_username'	=> 'SELECT `username` FROM `%s` AS User WHERE User.`id` = Message.`fk_to_user_id`',
	);


	public $hasCreated = 'integer';


	/**
	 *	Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->subQueries['from_username']	= sprintf($this->subQueries['from_username'], \Sweany\Settings::tblUsers);
		$this->subQueries['to_username']	= sprintf($this->subQueries['to_username'], \Sweany\Settings::tblUsers);
	}


	/* ************************************  S E N D   M E S S A G E S  ************************************/

	public function send($from_user_id, $to_user_id, $subject, $message, $can_reply = 1)
	{
		$data	= array(
			'fk_from_user_id'	=> $from_user_id,
			'fk_to_user_id'		=> $to_user_id,
			'subject'			=> $subject,
			'message'			=> $message,
			'can_reply'			=> $can_reply,
		);
		return $this->save($data);
	}
	public function reply($from_user_id, $to_user_id, $reply_message_id, $subject, $message)
	{
		// reply to the message
		$data	= array(
			'fk_from_user_id'	=> $from_user_id,
			'fk_to_user_id'		=> $to_user_id,
			'fk_reply_id'		=> $reply_message_id,
			'subject'			=> $subject,
			'message'			=> $message,
			'can_reply'			=> 1,
		);
		$new_msg_id = $this->save($data);

		// set my message as replied|answered
		$this->_markMyMessageAsAnswered($reply_message_id);

		return $new_msg_id;
	}



	/* ************************************  G E T   M E S S A G E S  ************************************/

	public function getMyInboxMessages($my_user_id)
	{
		$condition = array('
			`fk_to_user_id` = :uid AND
			`is_received_archived` = 0 AND
			`is_received_trashed` = 0 AND
			`is_received_deleted` = 0',
			array(':uid' => $my_user_id),
		);
		$options = array(
			'condition'	=> $condition,
			'order'		=> array('created' => 'DESC'),
		);

		return $this->find('all', $options);
	}

	public function getMyOutboxMessages($my_user_id)
	{
		$condition = array('
			`fk_from_user_id` = :uid AND
			`is_send_deleted` = 0',
			array(':uid' => $my_user_id),
		);
		$options = array(
			'condition'	=> $condition,
			'order'		=> array('created' => 'DESC'),
		);

		return $this->find('all', $options);
	}

	public function getMyArchiveMessages($my_user_id)
	{
		$condition = array('
			`fk_to_user_id` = :uid AND
			`is_received_archived` = 1 AND
			`is_received_trashed` = 0 AND
			`is_received_deleted` = 0',
			array(':uid' => $my_user_id),
		);
		$options = array(
			'condition'	=> $condition,
			'order'		=> array('created' => 'DESC'),
		);

		return $this->find('all', $options);
	}

	public function getMyTrashMessages($my_user_id)
	{
		$condition = array('
			`fk_to_user_id` = :uid AND
			`is_received_trashed` = 1 AND
			`is_received_deleted` = 0',
			array(':uid' => $my_user_id),
		);
		$options = array(
			'condition'	=> $condition,
			'order'		=> array('created' => 'DESC'),
		);

		return $this->find('all', $options);
	}



	/* ************************************  C O U N T   F U N C T I O N S  ************************************/

	public function countMyUnreadInboxMessages($my_user_id)
	{
		$condition = array('
			`fk_to_user_id` = :uid AND
			`is_read` = 0 AND
			`is_received_archived` = 0 AND
			`is_received_trashed` = 0 AND
			`is_received_deleted` = 0',
			array(':uid' => $my_user_id),
		);
		return $this->count($condition);
	}

	public function countMyInboxMessages($my_user_id)
	{
		$condition = array('
			`fk_to_user_id` = :uid AND
			`is_received_archived` = 0 AND
			`is_received_trashed` = 0 AND
			`is_received_deleted` = 0',
			array(':uid' => $my_user_id),
		);
		return $this->count($condition);
	}

	public function countMyArchiveMessages($my_user_id)
	{
		$condition = array('
			`fk_to_user_id` = :uid AND
			`is_received_archived` = 1 AND
			`is_received_trashed` = 0 AND
			`is_received_deleted` = 0',
			array(':uid' => $my_user_id),
		);
		return $this->count($condition);
	}

	public function countMyTrashMessages($my_user_id)
	{
		$condition = array('
			`fk_to_user_id` = :uid AND
			`is_received_trashed` = 1 AND
			`is_received_deleted` = 0',
			array(':uid' => $my_user_id),
		);
		return $this->count($condition);
	}

	public function countMyOutboxMessages($my_user_id)
	{
		$condition = array('
			`fk_from_user_id` = :uid AND
			`is_send_deleted` = 0',
			array(':uid' => $my_user_id),
		);
		return $this->count($condition);
	}



	/* ************************************  C H E C K   F U N C T I O N S  ************************************/

	// generic (can be in inbox, archive or trash, but not in outbox)
	public function isMyReceivedMessage($my_user_id, $message_id)
	{
		$condition = array('
			`id` = :id AND
			`fk_to_user_id` = :uid AND
			`is_received_deleted` = 0',
			array(
				':id'	=> $message_id,
				':uid'	=> $my_user_id
			),
		);
		return $this->count($condition);
	}

	// specific (rather use them)
	public function isMyInboxMessage($my_user_id, $message_id)
	{
		$condition = array('
			`id` = :id AND
			`fk_to_user_id` = :uid AND
			`is_received_archived` = 0 AND
			`is_received_trashed` = 0 AND
			`is_received_deleted` = 0',
			array(
				':id'	=> $message_id,
				':uid'	=> $my_user_id
			),
		);
		return $this->count($condition);
	}

	public function isMyOutboxMessage($my_user_id, $message_id)
	{
		$condition = array('
			`id` = :id AND
			`fk_from_user_id` = :uid AND
			`is_send_deleted` = 0',
			array(
				':id'	=> $message_id,
				':uid'	=> $my_user_id
			),
		);
		return $this->count($condition);
	}

	public function isMyArchiveMessage($my_user_id, $message_id)
	{
		$condition = array('
			`id` = :id AND
			`fk_to_user_id` = :uid AND
			`is_received_archived` = 1 AND
			`is_received_trashed` = 0 AND
			`is_received_deleted` = 0',
			array(
				':id'	=> $message_id,
				':uid'	=> $my_user_id
			),
		);
		return $this->count($condition);
	}

	public function isMyTrashMessage($my_user_id, $message_id)
	{
		$condition = array('
			`id` = :id AND
			`fk_to_user_id` = :uid AND
			`is_received_trashed` = 1 AND
			`is_received_deleted` = 0',
			array(
				':id'	=> $message_id,
				':uid'	=> $my_user_id
			),
		);
		return $this->count($condition);
	}




	/* ************************************  R E A D / U N R E A D   F U N C T I O N S  ************************************/

	public function markMessageRead($message_id)
	{
		$this->update($message_id, array('is_read' => 1));

		// If the message has not been read once yet,
		// it will be read for the first time,
		// so we set the 'read_first' date here
		if ( !$this->_hasAlreadyBeenReadOnce($message_id) )
		{
			$this->update($message_id, array('first_read' => time()));
		}
	}
	public function markMessageUnread($message_id)
	{
		$this->update($message_id, array('is_read' => 0));
	}



	/* ************************************  M O V E   F U N C T I O N S  ************************************/

	// generic
	public function moveMessageToTrash($message_id)
	{
		$this->update($message_id, array('is_received_trashed' => 0));
	}

	// Specific (rather use them)
	public function moveMyInboxMessageToArchive($message_id)
	{
		$this->update($message_id, array('is_received_archived' => 1));
	}
	public function moveMyInboxMessageToTrash($message_id)
	{
		$this->update($message_id, array('is_received_trashed' => 1));
	}
	public function moveMyArchiveMessageToTrash($message_id)
	{
		$this->update($message_id, array('is_received_trashed' => 1));
	}
	public function restoreMyMessageFromTrash($message_id)
	{
		$this->update($message_id, array('is_received_trashed' => 0));
	}



	/* ************************************  D E L E T E   F U N C T I O N S  ************************************/

	public function markMyReceivedMessageDeleted($message_id)
	{
		$this->update($message_id, array('is_received_deleted' => 1));
	}
	public function markMySendMessageDeleted($message_id)
	{
		$this->update($message_id, array('is_send_deleted' => 1));
	}



	/* ************************************  P R I V A T E   F U N C T I O N S  ************************************/

	/**
	 *
	 * Check if the 'first_read' date is < than the 'created' date
	 * If so, the 'first_read' has not been set yet
	 */
	private function _hasAlreadyBeenReadOnce($message_id)
	{
		return !( $this->field($message_id, 'first_read') < $this->field($message_id, 'created') );
	}


	private function _markMyMessageAsAnswered($message_id)
	{
		$this->update($message_id, array('is_answered' => 1));
	}
}