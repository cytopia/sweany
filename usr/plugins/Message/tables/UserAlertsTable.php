<?php
class UserAlertsTable extends Table
{
	public $table	= 'user_alerts';
	public $alias	= 'Alert';

	public $fields	= array(
		'id',
		'fk_to_user_id',
		'subject',
		'message',
		'flag_prio_low',
		'flag_prio_medium',
		'flag_prio_high',
		'is_read',
		'is_archived',
		'is_trashed',
		'is_deleted',
		'read_count',
		'created',
	);
	public $subQueries = array(
		'to_username'		=> 'SELECT `username` FROM `%s` AS User WHERE User.`id`=Alert.`fk_to_user_id`',
	);

	public $hasCreated = 'integer';

	/**
	 *	Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->subQueries['to_username']	= sprintf($this->subQueries['to_username'], \Sweany\Settings::tblUsers);
	}

	/* ************************************  S E N D   M E S S A G E S  ************************************/

	public function send($to_user_id, $subject, $message)
	{
		$data	= array(
			'fk_to_user_id'		=> $to_user_id,
			'subject'			=> $subject,
			'message'			=> $message,
		);
		return $this->save($data);
	}



	/* ************************************  G E T   M E S S A G E S  ************************************/

	public function getMyInboxAlerts($my_user_id)
	{
		$condition = array('
			`fk_to_user_id` = :uid AND
			`is_archived` = 0 AND
			`is_trashed` = 0 AND
			`is_deleted` = 0',
			array(':uid' => $my_user_id),
		);
		$options = array(
			'condition'	=> $condition,
			'order'		=> array('created' => 'DESC'),
		);
		return $this->find('all', $options);
	}

	public function getMyArchiveAlerts($my_user_id)
	{
		$condition = array('
			`fk_to_user_id` = :uid AND
			`is_archived` = 1 AND
			`is_trashed` = 0 AND
			`is_deleted` = 0',
			array(':uid' => $my_user_id),
		);
		$options = array(
			'condition'	=> $condition,
			'order'		=> array('created' => 'DESC'),
		);
		return $this->find('all', $options);
	}

	public function getMyTrashAlerts($my_user_id)
	{
		$condition = array('
			`fk_to_user_id` = :uid AND
			`is_trashed` = 1 AND
			`is_deleted` = 0',
			array(':uid' => $my_user_id),
		);
		$options = array(
			'condition'	=> $condition,
			'order'		=> array('created' => 'DESC'),
		);
		return $this->find('all', $options);
	}



	/* ************************************  C O U N T   F U N C T I O N S  ************************************/

	public function countMyUnreadInboxAlerts($my_user_id)
	{
		$condition = array('
			`fk_to_user_id` = :id AND
			`is_read` = 0 AND
			`is_archived` = 0 AND
			`is_trashed` = 0 AND
			`is_deleted` = 0',
			array(':id' => $my_user_id)
		);
		return $this->count($condition);
	}
	public function countMyInboxAlerts($my_user_id)
	{
		$condition = array('
			`fk_to_user_id` = :id AND
			`is_archived` = 0 AND
			`is_trashed` = 0 AND
			`is_deleted` = 0',
			array(':id' => $my_user_id)
		);
		return $this->count($condition);
	}
	public function countMyArchiveAlerts($my_user_id)
	{
		$condition = array('
			`fk_to_user_id` = :id AND
			`is_archived` = 1 AND
			`is_trashed` = 0 AND
			`is_deleted` = 0',
			array(':id' => $my_user_id)
		);
		return $this->count($condition);
	}

	public function countMyTrashAlerts($my_user_id)
	{
		$condition = array('
			`fk_to_user_id` = :id AND
			`is_trashed` = 1 AND
			`is_deleted` = 0',
			array(':id' => $my_user_id)
		);
		return $this->count($condition);
	}



	/* ************************************  C H E C K   F U N C T I O N S  ************************************/

	// generic (can be in inbox, archive or trash, but not in outbox)
	public function isMyReceivedAlert($my_user_id, $alert_id)
	{
		$condition = array('
			`id` = :aid AND
			`fk_to_user_id` = :uid AND
			`is_deleted` = 0',
			array(
				':aid'	=> $alert_id,
				':uid' 	=> $my_user_id,
			),
		);
		return $this->count($condition);
	}

	// specific (rather use them)
	public function isMyInboxAlert($my_user_id, $alert_id)
	{
		$condition = array('
			`id` = :aid AND
			`fk_to_user_id` = :uid AND
			`is_archived` = 0 AND
			`is_trashed` = 0 AND
			`is_deleted` = 0',
			array(
				':aid'	=> $alert_id,
				':uid' 	=> $my_user_id,
			),
		);
		return $this->count($condition);
	}
	public function isMyArchiveAlert($my_user_id, $alert_id)
	{
		$condition = array('
			`id` = :aid AND
			`fk_to_user_id` = :uid AND
			`is_archived` = 1 AND
			`is_trashed` = 0 AND
			`is_deleted` = 0',
			array(
				':aid'	=> $alert_id,
				':uid' 	=> $my_user_id,
			),
		);
		return $this->count($condition);
	}
	public function isMyTrashAlert($my_user_id, $alert_id)
	{
		$condition = array('
			`id` = :aid AND
			`fk_to_user_id` = :uid AND
			`is_trashed` = 1 AND
			`is_deleted` = 0',
			array(
				':aid'	=> $alert_id,
				':uid' 	=> $my_user_id,
			),
		);
		return $this->count($condition);
	}




	/* ************************************  A C T I O N   F U N C T I O N S  ************************************/

	public function markAlertRead($alert_id)
	{
		$this->update($alert_id, array('is_read' => 1));
	}

	public function markDeletedTrashed($alert_id)
	{
		$this->update($alert_id, array('is_deleted' => 1));
	}
}