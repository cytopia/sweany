<?php
class Message extends PageController
{
	/**
	 *  This is a plugin
	 */
	protected $plugin = 'Message';

	/* ***************************************** FORM VALIDATOR ******************************************/
	protected $formValidator = array(

		// Form for replying message
		'form_reply_message'	=> array(
			'subject'	=> array(
				'minLen'	=> array(
					'rule'	=> array('minLen', 1),
					'error'	=> '',
				),
			),
			'message'	=> array(
				'minLen'	=> array(
					'rule'	=> array('minLen', 1),
					'error'	=> '',
				),
			),
		),
	);



	/* **********************************************************************************************************************
	*
	*   S E T T I N G S
	*
	* **********************************************************************************************************************/
	private $userLoginCtl;
	private $userLoginMethod;
	private $userRegisterCtl;
	private $userRegisterMethod;

	private $userProfileLink	= false;
	private $userProfileCtl;
	private $userProfileMethod;

	// The name of the user, when it is the system
	// and not a real user
	// you cannot reply to this user as well
	private $systemUserName;

	public function __construct()
	{
		parent::__construct();

		// Controller Defines needed to build <href> links in the views
		$this->userLoginCtl			= Config::get('loginCtl', 'message');
		$this->userLoginMethod		= Config::get('loginMethod', 'message');
		$this->userRegisterCtl		= Config::get('registerCtl', 'message');
		$this->userRegisterMethod	= Config::get('registerMethod', 'message');

		$this->userProfileLink		= Config::get('userProfileLinkEnable', 'message');
		$this->userProfileCtl		= Config::get('userProfileCtl', 'message');
		$this->userProfileMethod	= Config::get('userProfileMethod', 'message');

		$this->systemUserName		= Config::get('systemMessageUserDisplayName', 'message');
	}


	public function ajax_live_search_username()
	{
		header('Content-Type: text/html; charset=utf-8');
		$this->render = false;

		$query = $_POST['query'];

		if ( strlen($query)>1 )
		{
			$getResults = function($row, &$data)  {
				$data[] = $row['username'];
			};
			$db		= \Sweany\Database::getInstance();
			$query	= 'SELECT username FROM `'.\Sweany\Settings::tblUsers.'` WHERE username LIKE '.$db->escape($query.'%', true);
			$result	= $db->select($query, $getResults);

			return json_encode($result);
		}
		return json_encode(array());
	}






	/* **********************************************************************************************************************
	*
	*   F U N C T I O N S
	*
	* **********************************************************************************************************************/



	public function inbox()
	{
		if ( !$this->core->user->isLoggedIn() )
		{
			Session::set('referrer', array('controller'	=> __CLASS__, 'method' => __FUNCTION__));
			$this->redirect($this->userLoginCtl, $this->userLoginMethod);
		}

		if ( Form::isSubmitted('form_message_action') )
		{
			if ( !is_null($Ids = Form::getValue('message_id')) )
			{
				if ( (Form::fieldIsSet('trash')) )
				{
					foreach ($Ids as $message_id)
					{
						// Validate, if it is actually my inbox message.
						// User could have injected random id's
						if ( $this->model->UserMessages->isMyInboxMessage($this->core->user->id(), $message_id) )
						{
							$this->model->UserMessages->moveMyInboxMessageToTrash($message_id);
						}
					}
				}
				else if ( (Form::fieldIsSet('archive')) )
				{
					foreach ($Ids as $message_id)
					{
						// Validate, if it is actually my inbox message.
						// User could have injected random id's
						if ( $this->model->UserMessages->isMyInboxMessage($this->core->user->id(), $message_id) )
						{
							$this->model->UserMessages->moveMyInboxMessageToArchive($message_id);
						}
					}
				}
			}
		}
		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($this->core->language->pageTitle);


		// VIEW VARIABLES
		$this->set('language', $this->core->language);
		$this->set('messages', $this->model->UserMessages->getMyInboxMessages($this->core->user->id()));
		$this->set('className', __CLASS__);
		$this->set('userProfileCtl', $this->userProfileCtl);
		$this->set('userProfileMethod', $this->userProfileMethod);
		$this->set('systemUserName', $this->systemUserName);

		// VIEW OPTIONS
		$this->view('inbox');

		// CSS OPTIONS
		if ( Config::exists('messageCssEnable', 'message') && Config::exists('messageCssName', 'message') && Config::get('messageCssEnable', 'message') )
		{
			Css::addFile('/plugins/Message/css/'.Config::get('messageCssName', 'message'));
		}
		if ( Config::exists('customCssEnable', 'message') && Config::exists('customCssName', 'message') && Config::get('customCssEnable', 'message') )
		{
			Css::addFile('/css/'.Config::get('customCssName', 'message'));
		}

		// LAYOUT OPTIONS
		if ( Config::exists('layout', 'message') )
		{
			$layout = Config::get('layout', 'message');
			$this->layout($layout[0], $layout[1], isset($layout[3]) ? $layout[3] : array());
		}
	}

	public function alerts()
	{
		if ( !$this->core->user->isLoggedIn() )
		{
			Session::set('referrer', array('controller'	=> __CLASS__, 'method' => __FUNCTION__));
			$this->redirect($this->userLoginCtl, $this->userLoginMethod);
		}

		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($this->core->language->pageTitle);


		// VIEW VARIABLES
		$this->set('messages', $this->model->UserAlerts->getMyInboxAlerts($this->core->user->id()));
		$this->set('className', __CLASS__);
		$this->set('language', $this->core->language);
		$this->set('systemUserName', $this->systemUserName);
		$this->set('userProfileCtl', $this->userProfileCtl);
		$this->set('userProfileMethod', $this->userProfileMethod);

		// VIEW OPTIONS
		$this->view('alerts');

		// CSS OPTIONS
		if ( Config::exists('messageCssEnable', 'message') && Config::exists('messageCssName', 'message') && Config::get('messageCssEnable', 'message') )
		{
			Css::addFile('/plugins/Message/css/'.Config::get('messageCssName', 'message'));
		}
		if ( Config::exists('customCssEnable', 'message') && Config::exists('customCssName', 'message') && Config::get('customCssEnable', 'message') )
		{
			Css::addFile('/css/'.Config::get('customCssName', 'message'));
		}

		// LAYOUT OPTIONS
		if ( Config::exists('layout', 'message') )
		{
			$layout = Config::get('layout', 'message');
			$this->layout($layout[0], $layout[1], isset($layout[3]) ? $layout[3] : array());
		}
	}


	public function alert($alert_id = NULL)
	{
		if ( !$this->core->user->isLoggedIn() )
		{
			Session::set('referrer', array('controller'	=> __CLASS__, 'method' => __FUNCTION__));
			$this->redirect($this->userLoginCtl, $this->userLoginMethod);
		}

		if ( !$this->model->UserAlerts->isMyReceivedAlert($this->core->user->id(), $alert_id) )
		{
			$this->redirect(__CLASS__, 'alerts');
		}

		$this->model->UserAlerts->markAlertRead($alert_id);


		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($this->core->language->pageTitle);


		// VIEW VARIABLES
		$this->set('user', $this->core->user);
		$this->set('message', $this->model->UserAlerts->load($alert_id));
		$this->set('language', $this->core->language);
		$this->set('systemUserName', $this->systemUserName);

		// VIEW OPTIONS
		$this->view('read_alert');


		// CSS OPTIONS
		if ( Config::exists('messageCssEnable', 'message') && Config::exists('messageCssName', 'message') && Config::get('messageCssEnable', 'message') )
		{
			Css::addFile('/plugins/Message/css/'.Config::get('messageCssName', 'message'));
		}
		if ( Config::exists('customCssEnable', 'message') && Config::exists('customCssName', 'message') && Config::get('customCssEnable', 'message') )
		{
			Css::addFile('/css/'.Config::get('customCssName', 'message'));
		}

		// LAYOUT OPTIONS
		if ( Config::exists('layout', 'message') )
		{
			$layout = Config::get('layout', 'message');
			$this->layout($layout[0], $layout[1], isset($layout[3]) ? $layout[3] : array());
		}
	}






	public function archive()
	{
		if ( !$this->core->user->isLoggedIn() )
		{
			Session::set('referrer', array('controller'	=> __CLASS__, 'method' => __FUNCTION__));
			$this->redirect($this->userLoginCtl, $this->userLoginMethod);
		}

		if ( Form::isSubmitted('form_message_action') )
		{
			if ( !is_null($Ids = Form::getValue('message_id')) )
			{
				if ( (Form::fieldIsSet('trash')) )
				{
					foreach ($Ids as $message_id)
					{
						// Validate, if it is actually my inbox message.
						// User could have injected random id's
						if ( $this->model->UserMessages->isMyArchiveMessage($this->core->user->id(), $message_id) )
						{
							$this->model->UserMessages->moveMyArchiveMessageToTrash($message_id);
						}
					}
				}
			}
		}
		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($this->core->language->pageTitle);


		// VIEW VARIABLES
		$this->set('messages', $this->model->UserMessages->getMyArchiveMessages($this->core->user->id()));
		$this->set('className', __CLASS__);
		$this->set('language', $this->core->language);
		$this->set('userProfileCtl', $this->userProfileCtl);
		$this->set('userProfileMethod', $this->userProfileMethod);
		$this->set('systemUserName', $this->systemUserName);

		// VIEW OPTIONS
		$this->view('archive');

		// CSS OPTIONS
		if ( Config::exists('messageCssEnable', 'message') && Config::exists('messageCssName', 'message') && Config::get('messageCssEnable', 'message') )
		{
			Css::addFile('/plugins/Message/css/'.Config::get('messageCssName', 'message'));
		}
		if ( Config::exists('customCssEnable', 'message') && Config::exists('customCssName', 'message') && Config::get('customCssEnable', 'message') )
		{
			Css::addFile('/css/'.Config::get('customCssName', 'message'));
		}

		// LAYOUT OPTIONS
		if ( Config::exists('layout', 'message') )
		{
			$layout = Config::get('layout', 'message');
			$this->layout($layout[0], $layout[1], isset($layout[3]) ? $layout[3] : array());
		}
	}

	public function trash()
	{
		if ( !$this->core->user->isLoggedIn() )
		{
			Session::set('referrer', array('controller'	=> __CLASS__, 'method' => __FUNCTION__));
			$this->redirect($this->userLoginCtl, $this->userLoginMethod);
		}

		if ( Form::isSubmitted('form_message_action') )
		{
			if ( !is_null($Ids = Form::getValue('message_id')) )
			{
				if ( (Form::fieldIsSet('restore')) )
				{
					foreach ($Ids as $message_id)
					{
						// Validate, if it is actually my inbox message.
						// User could have injected random id's
						if ( $this->model->UserMessages->isMyTrashMessage($this->core->user->id(), $message_id) )
						{
							$this->model->UserMessages->restoreMyMessageFromTrash($message_id);
						}
					}
				}
				else if ( (Form::fieldIsSet('delete')) )
				{
					foreach ($Ids as $message_id)
					{
						// Validate, if it is actually my inbox message.
						// User could have injected random id's
						if ( $this->model->UserMessages->isMyTrashMessage($this->core->user->id(), $message_id) )
						{
							$this->model->UserMessages->markMyReceivedMessageDeleted($message_id);
						}
					}
				}
			}
		}
		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($this->core->language->pageTitle);


		// VIEW VARIABLES
		$this->set('messages', $this->model->UserMessages->getMyTrashMessages($this->core->user->id()));
		$this->set('className', __CLASS__);
		$this->set('language', $this->core->language);
		$this->set('userProfileCtl', $this->userProfileCtl);
		$this->set('userProfileMethod', $this->userProfileMethod);
		$this->set('systemUserName', $this->systemUserName);

		// VIEW OPTIONS
		$this->view('trash');

		// CSS OPTIONS
		if ( Config::exists('messageCssEnable', 'message') && Config::exists('messageCssName', 'message') && Config::get('messageCssEnable', 'message') )
		{
			Css::addFile('/plugins/Message/css/'.Config::get('messageCssName', 'message'));
		}
		if ( Config::exists('customCssEnable', 'message') && Config::exists('customCssName', 'message') && Config::get('customCssEnable', 'message') )
		{
			Css::addFile('/css/'.Config::get('customCssName', 'message'));
		}

		// LAYOUT OPTIONS
		if ( Config::exists('layout', 'message') )
		{
			$layout = Config::get('layout', 'message');
			$this->layout($layout[0], $layout[1], isset($layout[3]) ? $layout[3] : array());
		}
	}



	public function outbox()
	{
		if ( !$this->core->user->isLoggedIn() )
		{
			Session::set('referrer', array('controller'	=> __CLASS__, 'method' => __FUNCTION__));
			$this->redirect($this->userLoginCtl, $this->userLoginMethod);
		}

		if ( Form::isSubmitted('form_message_action') )
		{
			if ( !is_null($Ids = Form::getValue('message_id')) )
			{
				if ( (Form::fieldIsSet('delete')) )
				{
					foreach ($Ids as $message_id)
					{
						// Validate, if it is actually my inbox message.
						// User could have injected random id's
						if ( $this->model->UserMessages->isMyOutboxMessage($this->core->user->id(), $message_id) )
						{
							$this->model->UserMessages->markMySendMessageDeleted($message_id);
						}
					}
				}
			}
		}

		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($this->core->language->pageTitle);


		// VIEW VARIABLES
		$this->set('messages', $this->model->UserMessages->getMyOutboxMessages($this->core->user->id()));
		$this->set('className', __CLASS__);
		$this->set('language', $this->core->language);
		$this->set('userProfileCtl', $this->userProfileCtl);
		$this->set('userProfileMethod', $this->userProfileMethod);

		// VIEW OPTIONS
		$this->view('outbox');

		// CSS OPTIONS
		if ( Config::exists('messageCssEnable', 'message') && Config::exists('messageCssName', 'message') && Config::get('messageCssEnable', 'message') )
		{
			Css::addFile('/plugins/Message/css/'.Config::get('messageCssName', 'message'));
		}
		if ( Config::exists('customCssEnable', 'message') && Config::exists('customCssName', 'message') && Config::get('customCssEnable', 'message') )
		{
			Css::addFile('/css/'.Config::get('customCssName', 'message'));
		}

		// LAYOUT OPTIONS
		if ( Config::exists('layout', 'message') )
		{
			$layout = Config::get('layout', 'message');
			$this->layout($layout[0], $layout[1], isset($layout[3]) ? $layout[3] : array());
		}
	}



	public function read($message_id = NULL, $type = 'inbox')
	{
		if ( !$this->core->user->isLoggedIn() )
		{
			Session::set('referrer', array('controller'	=> __CLASS__, 'method' => __FUNCTION__));
			$this->redirect($this->userLoginCtl, $this->userLoginMethod);
		}


		/*
		 * Handle POST:
		 *
		 * 01) differentiate between received and send messages
		 */
		if ( Form::isSubmitted('form_message_action') )
		{
			// ======================== (01) MY RECEIVED MESSAGES ========================//

			// ---------- ONLY FOR: MY INBOX MESSAGE
			if ( $this->model->UserMessages->isMyInboxMessage($this->core->user->id(), $message_id) )
			{
				// Inbox can have trash and archive
				if ( Form::fieldIsSet('trash') )
				{
					$this->model->UserMessages->moveMyInboxMessageToTrash($message_id);
					$this->redirect(__CLASS__, 'trash');
				}
				else if ( Form::fieldIsSet('archive') )
				{
					$this->model->UserMessages->moveMyInboxMessageToArchive($message_id);
					$this->redirect(__CLASS__, 'archive');
				}
			}
			// ---------- ONLY FOR: MY ARCHIVE MESSAGE
			else if ( $this->model->UserMessages->isMyArchiveMessage($this->core->user->id(), $message_id) )
			{
				// Archive can have only have trash
				if ( Form::fieldIsSet('trash') )
				{
					$this->model->UserMessages->moveMyArchiveMessageToTrash($message_id);
					$this->redirect(__CLASS__, 'trash');
				}
			}
			// ---------- ONLY FOR: MY TRASH MESSAGE
			else if ( $this->model->UserMessages->isMyTrashMessage($this->core->user->id(), $message_id) )
			{
				// Archive can have only restore
				if ( Form::fieldIsSet('restore') )
				{
					$this->model->UserMessages->restoreMyMessageFromTrash($message_id);

					if ( $this->model->UserMessages->isMyInboxMessage($this->core->user->id(), $message_id) )
					{
						$this->redirect(__CLASS__, 'inbox');
					}
					else
					{
						$this->redirect(__CLASS__, 'archive');
					}
				}
				else if ( Form::fieldIsSet('delete') )
				{
					$this->model->UserMessages->markMyReceivedMessageDeleted($message_id);
					$this->redirect(__CLASS__, 'trash');
				}
			}

			// ======================== (02) MY SEND MESSAGES ========================//
			else if ( $this->model->UserMessages->isMyOutboxMessage($this->core->user->id(), $message_id) )
			{
				// Outbox can only have permanent delete
				if ( Form::fieldIsSet('delete') )
				{
					$this->model->UserMessages->markMySendMessageDeleted($message_id);
					$this->redirect(__CLASS__, 'inbox');
				}
			}
		}



		if ( $type == 'inbox' )
		{
			// If it is not my RECEIVED message, redirect silently to inbox
			if ( !$this->model->UserMessages->isMyReceivedMessage($this->core->user->id(), $message_id) )
			{
				$this->redirect(__CLASS__, 'inbox');
			}
			// Mark the inbox Message as read
			else
			{
				$this->model->UserMessages->markMessageRead($message_id);
			}
		}
		else if ( $type == 'outbox' )
		{
			// If it is not my message, redirect silently to inbox
			if ( !$this->model->UserMessages->isMyOutboxMessage($this->core->user->id(), $message_id) )
			{
				$this->redirect(__CLASS__, 'outbox');
			}
		}
		else
		{
			$this->redirect(__CLASS__, 'inbox');
		}

		$message = $this->model->UserMessages->load($message_id);

		// determine special type, if it is not the outbox
		// We have to check what inbox type it is (archive or trash)
		if ( $type == 'inbox' )
		{
			if ( $message->is_received_trashed )
			{
				$type = 'trash';
			}
			else if ( $message->is_received_archived )
			{
				$type = 'archive';
			}
			// else is inbox
		}

		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($this->core->language->pageTitle);


		// VIEW VARIABLES
		$this->set('user', $this->core->user);
		$this->set('message', $message);
		$this->set('language', $this->core->language);
		$this->set('type', $type);
		$this->set('userProfileCtl', $this->userProfileCtl);
		$this->set('userProfileMethod', $this->userProfileMethod);
		$this->set('systemUserName', $this->systemUserName);

		// VIEW OPTIONS
		$this->view('read');

		// CSS OPTIONS
		if ( Config::exists('messageCssEnable', 'message') && Config::exists('messageCssName', 'message') && Config::get('messageCssEnable', 'message') )
		{
			Css::addFile('/plugins/Message/css/'.Config::get('messageCssName', 'message'));
		}
		if ( Config::exists('customCssEnable', 'message') && Config::exists('customCssName', 'message') && Config::get('customCssEnable', 'message') )
		{
			Css::addFile('/css/'.Config::get('customCssName', 'message'));
		}

		// LAYOUT OPTIONS
		if ( Config::exists('layout', 'message') )
		{
			$layout = Config::get('layout', 'message');
			$this->layout($layout[0], $layout[1], isset($layout[3]) ? $layout[3] : array());
		}
	}


	public function write($user_id = null)
	{
		if ( !$this->core->user->isLoggedIn() )
		{
			Session::set('referrer', array('controller'	=> __CLASS__, 'method' => __FUNCTION__, 'params' => array($user_id)));
			$this->redirect($this->userLoginCtl, $this->userLoginMethod);
		}

		$to_username = $this->core->user->name($user_id);

		if ( Form::isSubmitted('form_reply_message') )
		{
			$subject		= Form::getValue('subject');
			$message		= Form::getValue('message');
			$to_username	= Form::getValue('to_username');

			if ( !strlen($subject) )
			{
				Form::setError('subject', $this->core->language->errFillOutSubject);
			}
			if ( !strlen($message) )
			{
				Form::setError('message', $this->core->language->errFillOutBody);
			}
			if ( !$this->core->user->usernameExists($to_username) )
			{
				Form::setError('to_username', $this->core->language->errUserNameDoesNotExist);
			}
			if ( !$this->core->user->getIdByName($to_username) )
			{
				Form::setError('to_username', $this->core->language->errUserNameDoesNotExist);
			}

			if ( Form::isValid('form_reply_message') )
			{
				$to_user_id = $this->core->user->getIdByName($to_username);
				$message_id	= $this->model->UserMessages->send($this->core->user->id(), $to_user_id, $subject, $message);
				$this->redirect(__CLASS__, 'read', array($message_id, 'outbox'));
			}
		}




		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($this->core->language->pageTitle);

		// VIEW VARIABLES
		$this->set('to_username', $to_username);
		$this->set('language', $this->core->language);

		// VIEW OPTIONS
		$this->view('write');

		// CSS OPTIONS
		if ( Config::exists('messageCssEnable', 'message') && Config::exists('messageCssName', 'message') && Config::get('messageCssEnable', 'message') )
		{
			Css::addFile('/plugins/Message/css/'.Config::get('messageCssName', 'message'));
		}
		if ( Config::exists('customCssEnable', 'message') && Config::exists('customCssName', 'message') && Config::get('customCssEnable', 'message') )
		{
			Css::addFile('/css/'.Config::get('customCssName', 'message'));
		}

		// LAYOUT OPTIONS
		if ( Config::exists('layout', 'message') )
		{
			$layout = Config::get('layout', 'message');
			$this->layout($layout[0], $layout[1], isset($layout[3]) ? $layout[3] : array());
		}
	}




	public function reply($user_id = null, $message_id = null)
	{
		if ( !$this->core->user->isLoggedIn() )
		{
			Session::set('referrer', array('controller'	=> __CLASS__, 'method' => __FUNCTION__, 'params' => array($user_id)));
			$this->redirect($this->userLoginCtl, $this->userLoginMethod);
		}

		// Only allow answering messages that I have received
		if ( !$this->model->UserMessages->isMyReceivedMessage($this->core->user->id(), $message_id) )
		{
			$this->redirect(__CLASS__, 'inbox');
			return;
		}

		// Only allow writing messages to active enabled users
		// And do not allow writing to myself
		if ( !$this->core->user->exists($user_id) || !$this->core->user->isEnabled($user_id) ||
			$this->core->user->isLocked($user_id) || $this->core->user->isDeleted($user_id) )
		{
			$canMessage = false;
		}
		else
		{
			$canMessage = true;
		}

		$this->formValidator['form_reply_message']['subject']['minLen']['error'] = $this->core->language->errFillOutSubject;
		$this->formValidator['form_reply_message']['message']['minLen']['error'] = $this->core->language->errFillOutBody;


		if ( $this->validateForm('form_reply_message') && $canMessage  )
		{
			$subject	= Form::getValue('subject');
			$message	= Form::getValue('message');
			$to_user_id	= Form::getValue('to_user_id');

			$message_id	= $this->model->UserMessages->reply($this->core->user->id(), $to_user_id, $message_id, $subject, $message);
			$this->redirect(__CLASS__, 'read', array($message_id, 'outbox'));
			return;
		}



		// Add old message and title to the form
		$message	= $this->model->UserMessages->load($message_id);
		$title		= 'Re: '.$message->subject;
		$tmp		= explode("\n", $message->message);
		$body		= "\n\n\n".$this->core->user->name($message->fk_from_user_id);
		$body		.= ' '.$this->core->language->on.' ' .TimeHelper::date('d.m.Y H:m', $message->created)."\n";
		$body		.= '---------------------------------'."\n";
		for ($i=0; $i<sizeof($tmp);$i++)
		{
			$body .= '<< '.$tmp[$i]."\n";
		}

		Form::setFormValue('form_reply_message', 'subject', $title);
		Form::setFormValue('form_reply_message', 'message', $body);

		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($this->core->language->pageTitle);

		// VIEW VARIABLES
		$this->set('can_message', $canMessage);
		$this->set('to_user_id', $user_id);
		$this->set('language', $this->core->language);
		$this->set('to_username', $this->core->user->name($user_id));
		$this->set('userProfileCtl', $this->userProfileCtl);
		$this->set('userProfileMethod', $this->userProfileMethod);

		// VIEW OPTIONS
		$this->view('reply');

		// CSS OPTIONS
		if ( Config::exists('messageCssEnable', 'message') && Config::exists('messageCssName', 'message') && Config::get('messageCssEnable', 'message') )
		{
			Css::addFile('/plugins/Message/css/'.Config::get('messageCssName', 'message'));
		}
		if ( Config::exists('customCssEnable', 'message') && Config::exists('customCssName', 'message') && Config::get('customCssEnable', 'message') )
		{
			Css::addFile('/css/'.Config::get('customCssName', 'message'));
		}

		// LAYOUT OPTIONS
		if ( Config::exists('layout', 'message') )
		{
			$layout = Config::get('layout', 'message');
			$this->layout($layout[0], $layout[1], isset($layout[3]) ? $layout[3] : array());
		}
	}
}