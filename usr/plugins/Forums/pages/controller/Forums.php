<?php
class Forums extends PageController
{
	/**
	 *  This is a plugin
	 */
	protected $plugin = 'Forums';


	protected $formValidator = array(

		// Form for adding threads
		'form_add_thread'	=> array(
			'forum_id'	=> array(
				'equalsUrlParam' => array(
					'rule'	=> array('equalsUrlParam', 0),
					'error'	=> 'Invalid Forum',
				),
			),
			'title' => array(
				'minLen' => array(
					'rule'	=> array('minLen', 1),
					'error'	=> 'A title is required',
				),
				'maxLen' => array(
					'rule'	=> array('maxLen', 70),
					'error'	=> 'Max. 70 Characters',
				),
			),
			'body' => array(
				'minLen' => array(
					'rule'	=> array('minLen', 1),
					'error'	=> 'A body is required',
				),
			),
		),

		// Form for adding posts
		'form_add_post'	=> array(
			'forum_id'	=> array(
				'equalsUrlParam' => array(
					'rule'	=> array('equalsUrlParam', 0),
					'error'	=> 'invalid forum',
				),
			),
			'thread_id'	=> array(
				'equalsUrlParam' => array(
					'rule'	=> array('equalsUrlParam', 1),
					'error'	=> 'invalid forum',
				),
			),
			'body' => array(
				'minLen' => array(
					'rule'	=> array('minLen', 1),
					'error'	=> 'A body is required',
				),
			),
		),
	);



	/* **********************************************************************************************************************
	*
	*   S E T T I N G S
	*
	* **********************************************************************************************************************/

	private $dateFormat			= 'M d, Y';
	private $timeFormat			= 'H:i';

	private $userLoginCtl;
	private $userLoginMethod;
	private $userRegisterCtl;
	private $userRegisterMethod;

	private $userProfileLink	= false;
	private $userProfileCtl;
	private $userProfileMethod;

	private $userMessageLink	= false;
	private $userMessageToCtl;
	private $userMessageToMethod;


	public function __construct()
	{
		parent::__construct();

		// Controller Defines needed to build <href> links in the views
		$this->userLoginCtl			= Config::get('loginCtl', 'forum');
		$this->userLoginMethod		= Config::get('loginMethod', 'forum');
		$this->userRegisterCtl		= Config::get('registerCtl', 'forum');
		$this->userRegisterMethod	= Config::get('registerMethod', 'forum');

		$this->userProfileLink		= Config::get('userProfileLinkEnable', 'forum');
		$this->userProfileCtl		= Config::get('userProfileCtl', 'forum');
		$this->userProfileMethod	= Config::get('userProfileMethod', 'forum');

		$this->userMessageLink		= Config::get('writeMessageLinkEnable', 'forum');
		$this->userMessageToCtl		= Config::get('writeMessageCtl', 'forum');
		$this->userMessageToMethod	= Config::get('writeMessageMethod', 'forum');
	}





	/* **********************************************************************************************************************
	*
	*    A J A X   F U N C T I O N S
	*
	* **********************************************************************************************************************/

	public function ajax_edit_post($post_id)
	{
		header('Content-Type: text/html; charset=utf-8');

		// do not render
		$this->render = false;

		$Post = $this->model->ForumPosts->load($post_id, 0);

		if (!$Post) {
			return '';
		}

		if ( !isset($_POST['postId']) )				{return -1;	}	// If no post value is set, exit
		if ( $_POST['postId'] != $post_id )			{return -2;	}	// basic check: if POST and GET values differ, return false
		if ( !isset($_POST['body']))				{return -3; }	// Body is not set!!!
		if ( !$this->core->user->isLoggedIn() )			{return -4;	}	// If not logged in you cannot edit post
		if ( $Post->fk_user_id != $this->core->user->id() ) { return ''; }	// If it is not my post, I cannot edit it

		$this->model->ForumPosts->update($post_id, array('body' =>  $_POST['body']), 2);	// update and retrive updated Post

		// return the new box
		$box = '<strong>'.$Post->title.'</strong><br/><hr/><br/>';
		$box.= Bbcode::parse($_POST['body']);
		$box.= '<br/><br/>';
		return $box;
	}
	public function ajax_edit_thread($thread_id)
	{
		header('Content-Type: text/html; charset=utf-8');

		// do not render
		$this->render = false;

		$Thread = $this->model->ForumThreads->load($thread_id, 0);

		if (!$Thread) {
			return '';
		}

		if ( !isset($_POST['threadId']) )				{return -1;}	// If no post value is set, exit
		if ( $_POST['threadId'] != $thread_id )			{return -2;}	// basic check: if POST and GET values differ, return false
		if ( !isset($_POST['body']))					{return -3;}	// Body is not set!!!
		if ( !$this->core->user->isLoggedIn() )				{return -4;}	// If not logged in you cannot edit post
		if ( $Thread->user_id != $this->core->user->id() )	{return -6;}	// If it is not my post, I cannot edit it

		$this->model->ForumThreads->update($thread_id, array('body' => $_POST['body']), 2);

		// return the new box
		$box = '<strong>'.$Thread->title.'</strong><br/><hr/><br/>';
		$box.= Bbcode::parse($_POST['body']);
		$box.= '<br/><br/>';
		return $box;
	}

	public function ajax_get_quick_edit_post_box($post_id = null)
	{
		header('Content-Type: text/html; charset=utf-8');

		// do not render
		$this->render = false;

		$Post = $this->model->ForumPosts->load($post_id, 0);

		if (!$Post) {
			return '';
		}

		if ( !isset($_POST['postId']) )				{ return ''; }	// If no post value is set, exit
		if ( $_POST['postId'] != $post_id )			{ return ''; }	// basic check: if POST and GET values differ, return false
		if ( !$this->core->user->isLoggedIn() )			{ return ''; }	// If not logged in you cannot edit post
		if ( $Post->fk_user_id != $this->core->user->id() ) { return ''; }	// If it is not my post, I cannot edit it

		$box = '<div style="text-align:left; padding:8px; border:solid 1px black; width:505px; background-color:#FDFDFD;">';
		$box.=		Form::editor('body', $Post->body, 60, 5, array('id' => 'quickEditBoxText'));
		$box.=		'<button onclick=\'submitEditPost('.$post_id.')\'>&auml;ndern</button>';
		$box.=		'<button onclick=\'cancelEdit()\'>abbrechen</button>';
		$box.= '</div><br/>';

		return $box;
	}
	public function ajax_get_quick_edit_thread_box($thread_id = null)
	{
		header('Content-Type: text/html; charset=utf-8');

		// do not render
		$this->render = false;

		$Thread = $this->model->ForumThreads->load($thread_id, 0);

		if (!$Thread) {
			return '';
		}

		if ( !isset($_POST['threadId']) )				{return '';}	// If no post value is set, exit
		if ( $_POST['threadId'] != $thread_id )			{return '';}	// basic check: if POST and GET values differ, return false
		if ( !$this->core->user->isLoggedIn() )				{return '';}	// If not logged in you cannot edit post
		if ( $Thread->user_id != $this->core->user->id() )	{return '';}	// If it is not my thread, I cannot edit it


		$box = '<div style="text-align:left; padding:8px; border:solid 1px black; width:505px; background-color:#FDFDFD;">';
		$box.=		Form::editor('body', $Thread->body, 60, 5, array('id' => 'quickEditBoxText'));
		$box.=		'<button onclick=\'submitEditThread('.$thread_id.')\'>&auml;ndern</button>';
		$box.=		'<button onclick=\'cancelEdit()\'>abbrechen</button>';
		$box.= '</div><br/>';

		return $box;
	}





	/* **********************************************************************************************************************
	*
	*   F U N C T I O N S
	*
	* **********************************************************************************************************************/
	public function index()
	{
		$Categories = $this->model->ForumCategories->find('all', array('recursive' => 2));

		$this->attachPluginBlock('bOnlineUsers', 'Forums', 'Forum', 'onlineUsers');

		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($this->core->language->forum);

		// ADD CSS
		Css::addFile('/plugins/Forums/css/forum.css');

		// VIEW VARIABLES
		$this->set('Categories', $Categories);
		$this->set('language', $this->core->language);
		$this->set('date_format', $this->dateFormat);
		$this->set('time_format', $this->timeFormat);

		$this->set('userProfileLink', $this->userProfileLink);
		$this->set('userProfileCtl', $this->userProfileCtl);
		$this->set('userProfileMethod', $this->userProfileMethod);

		// VIEW OPTIONS
		$this->view('index');

		// LAYOUT OPTIONS
		if  ( Config::exists('layout', 'forum') )
		{
			$layout = Config::get('layout', 'forum');
			$this->layout($layout[0], $layout[1], isset($layout[3]) ? $layout[3] : array());
		}
	}


	public function showForum($forum_id = null, $seo_url = null)
	{
		$Forum = $this->model->ForumForums->load($forum_id, 2);

		if ( !$Forum || !$Forum->display )
		{
			$this->redirect(__CLASS__, 'index');
		}
		if ( $Forum->seo_url != $seo_url )
		{
			$this->redirect(__CLASS__, __FUNCTION__, array($forum_id, $Forum->seo_url));
		}
		// check wheter thread or post was last and sort by it accordingly
		//usort($threads, array('ForumsModel', 'sortForumThreadsByLastEntry'));

		$isAdmin		= $this->core->user->isAdmin();


//		debug($Forum);

		$navigation		= Html::l($this->core->language->forum, __CLASS__).' -&gt; '.$Forum->name;

		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($Forum->name.' '.$this->core->language->forum);

		// ADD CSS
		Css::addFile('/plugins/Forums/css/forum.css');

		// BLOCKS
		$this->attachPluginBlock('bOnlineUsers', 'Forums', 'Forum', 'onlineUsers');

		// VIEW VARIABLES
		$this->set('Forum', $Forum);
		$this->set('language', $this->core->language);
		$this->set('isAdmin', $isAdmin);

		$this->set('date_format', $this->dateFormat);
		$this->set('time_format', $this->timeFormat);

		$this->set('userProfileLink', $this->userProfileLink);
		$this->set('userProfileCtl', $this->userProfileCtl);
		$this->set('userProfileMethod', $this->userProfileMethod);

		// VIEW OPTIONS
		$this->set('navi', $navigation);
		$this->set('headline', $Forum->name.' '.$this->core->language->forum);
		$this->view('show_forum');

		// LAYOUT OPTIONS
		if  ( Config::exists('layout', 'forum') )
		{
			$layout = Config::get('layout', 'forum');
			$this->layout($layout[0], $layout[1], isset($layout[3]) ? $layout[3] : array());
		}
	}


	public function showThread($forum_id = null, $thread_id = null, $seo_url = null)
	{
		$Thread = $this->model->ForumThreads->load($thread_id);

		if ( !$forum_id || $forum_id != $Thread->Forum->id || !$Thread->Forum->display )
		{
			$this->redirect(__CLASS__, 'index');
		}

		if ( !$thread_id || $thread_id != $Thread->id )
		{
			$this->redirect(__CLASS__, 'showForum', array($Thread->Forum->id, $Thread->Forum->seo_url));
		}

		if ( $seo_url != $Thread->seo_url )
		{
			$this->redirect(__CLASS__, __FUNCTION__, array($forum_id, $thread_id, $Thread->seo_url));
		}

		if ( !$this->core->user->isLoggedIn() )
		{
			// SET SESSION
			// Also inform user to log in, he will be redirected here afterwards
			Session::set('referrer', array('controller'	=> __CLASS__, 'method' => __FUNCTION__, 'params' => array($forum_id, $thread_id, $seo_url)));
		}


		$this->model->updateThreadView($thread_id);

		$this->attachPluginBlock('bOnlineUsers', 'Forums', 'Forum', 'onlineUsers');

		$navigation = Html::l($this->core->language->forum, __CLASS__).' -&gt; '.Html::l($Thread->Forum->name, __CLASS__, 'showForum', array($forum_id, $Thread->Forum->seo_url));


		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($Thread->Forum->name.' - '.$Thread->title);

		// ADD CSS
		Css::addFile('/plugins/Forums/css/forum.css');

		// ADD JS
		Javascript::addFile('/plugins/Forums/js/ajax.js');
		Javascript::addFile('/plugins/Forums/js/forum.js');

		// VIEW VARIABLES
		$this->set('Thread', $Thread);
		$this->set('language', $this->core->language);
		$this->set('user', $this->core->user);
		$this->set('date_format', $this->dateFormat);
		$this->set('time_format', $this->timeFormat);

		$this->set('userLoginCtl', $this->userLoginCtl);
		$this->set('userLoginMethod', $this->userLoginMethod);
		$this->set('userRegisterCtl', $this->userRegisterCtl);
		$this->set('userRegisterMethod', $this->userRegisterMethod);

		$this->set('userProfileLink', $this->userProfileLink);
		$this->set('userProfileCtl', $this->userProfileCtl);
		$this->set('userProfileMethod', $this->userProfileMethod);
		$this->set('userMessageLink', $this->userMessageLink);
		$this->set('userMessageToCtl', $this->userMessageToCtl);
		$this->set('userMessageToMethod', $this->userMessageToMethod);


		// VIEW OPTIONS
		$this->set('navi', $navigation);
		$this->set('headline', $Thread->Forum->name.' '.$this->core->language->forum);
		$this->view('show_thread');

		// LAYOUT OPTIONS
		if  ( Config::exists('layout', 'forum') )
		{
			$layout = Config::get('layout', 'forum');
			$this->layout($layout[0], $layout[1], isset($layout[3]) ? $layout[3] : array());
		}
	}



	public function addThread($forum_id = null)
	{
		$Forum	= $this->model->ForumForums->load($forum_id, 0);

		if ( !$Forum || !$Forum->display )
		{
			$this->redirect(__CLASS__, 'index');
		}
		if ( ! ($Forum->can_create || $this->core->user->isAdmin()) )
		{
			$this->redirect(__CLASS__, 'showForum', array($forum_id, $this->model->getForumSeoUrl($forum_id)));
		}
		if ( !$this->core->user->isLoggedIn() )
		{
			// SET SESSION
			// Also inform user to log in, he will be redirected here afterwards
			Session::set('referrer', array('controller'	=> __CLASS__, 'method' => __FUNCTION__, 'params' => array($forum_id)));
		}


		// ------------------------- FORM SUBMITTED AND VALID -------------------------
		if ( $this->validateForm('form_add_thread') && $this->core->user->isLoggedIn()  )
		{
			// Note:
			// need to check for the submit button of this form
			// as the user can also click 'preview'
			if ( Form::fieldIsSet('add_thread_submit') )
			{
				// ------------------------- GET FORM VALUES -------------------------
				$fields['forum_id']	= Form::getValue('forum_id');
				$fields['title']	= Form::getValue('title');
				$fields['body']		= Form::getValue('body');
				$fields['user_id']	= $this->core->user->id();
				$tid	= $this->model->ForumThreads->save($fields,2);

				$this->redirect(__CLASS__, 'showThread', array($forum_id, $tid));
			}
			else if ( Form::fieldIsSet('add_thread_preview') )
			{
				$this->set('preview', true);
				$this->set('threadPreview', array('title' => Form::getValue('title'), 'body' => Form::getValue('body')));
			}
		}

		$navigation	= Html::l($this->core->language->forum, __CLASS__).' -&gt; '.Html::l($Forum->name, __CLASS__, 'showForum', array($forum_id, $Forum->seo_url));


		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($Forum->name.' - '.$this->core->language->createThread);

		// ADD CSS
		Css::addFile('/plugins/Forums/css/forum.css');

		// ADD JS
		Javascript::addFile('/plugins/Forums/js/forum.js');

		// VIEW VARIABLES
		$this->set('language', $this->core->language);
		$this->set('user', $this->core->user);
		$this->set('Forum', $Forum);

		$this->set('userLoginCtl', $this->userLoginCtl);
		$this->set('userLoginMethod', $this->userLoginMethod);
		$this->set('userRegisterCtl', $this->userRegisterCtl);
		$this->set('userRegisterMethod', $this->userRegisterMethod);

		// VIEW OPTIONS
		$this->set('navi', $navigation);
		$this->set('headline', $Forum->name.' '.$this->core->language->forum);
		$this->view('add_thread');

		// LAYOUT OPTIONS
		if  ( Config::exists('layout', 'forum') )
		{
			$layout = Config::get('layout', 'forum');
			$this->layout($layout[0], $layout[1], isset($layout[3]) ? $layout[3] : array());
		}
	}



	public function addPost($forum_id = null, $thread_id = null)
	{
		$Thread	= $this->model->ForumThreads->load($thread_id, 1);
		$Forum	= isset($Thread->Forum) ? $Thread->Forum : null;

		// --------------- VALIDATE FORUM
		if ( !$Forum || !$Thread || !$Forum->display || !$Forum->can_reply )
		{
			$this->redirect(__CLASS__, 'index');
		}
		if ( $Thread->id != $thread_id || $Forum->id != $forum_id || $Thread->is_locked || $Thread->is_closed )
		{
			$this->redirect(__CLASS__, 'showForum', array($forum_id, $Forum->seo_url));
		}

		// --------------- VALIDATE USER
		if ( !$this->core->user->isLoggedIn() )
		{
			// SET SESSION
			// Also inform user to log in, he will be redirected here afterwards
			Session::set('referrer', array('controller'	=> __CLASS__, 'method' => __FUNCTION__, 'params' => array($forum_id, $thread_id)));
		}

		// ------------------------- FORM SUBMITTED AND VALID -------------------------
		if ( $this->validateForm('form_add_post') && $this->core->user->isLoggedIn() && !$Thread->is_closed && !$Thread->is_locked )
		{
			// Note:
			// need to check for the submit button of this form
			// as the user can be redirected here
			// from quick edit via 'advanced', so we dont want to insert in that case
			if ( Form::fieldIsSet('add_post_submit') )
			{
				// ------------------------- GET FORM VALUES -------------------------
				$fields['fk_forum_thread_id']	= Form::getValue('thread_id');
				$fields['title']				= Form::getValue('title');
				$fields['body']					= Form::getValue('body');
				$fields['fk_user_id']			= $this->core->user->id();

				$post_id = $this->model->ForumPosts->save($fields);

				$this->redirect(__CLASS__, 'showThread', array($forum_id, $thread_id, $Thread->seo_url));
			}
			else if ( Form::fieldIsSet('add_post_preview') )
			{
				$this->set('preview', true);
				$this->set('postPreview', array('title' => Form::getValue('title'), 'body' => Form::getValue('body')));
			}
		}

		// Get Posts in reverse order (and append thread) to display below add box
		$posts			= array_reverse($Thread->Post);
		$_thread		= clone $Thread;
		unset($_thread->User);
		unset($_thread->Forum);
		unset($_thread->Post);
		unset($_thread->LastPost);
		$entries		= array_merge($posts, array($_thread));

		$navigation	= Html::l($this->core->language->forum, __CLASS__).' -&gt; '.Html::l($Forum->name, __CLASS__, 'showForum', array($forum_id, $Forum->seo_url)).' -&gt; '.$Thread->title;


		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($Forum->name.' - '.$this->core->language->reply);

		// ADD CSS
		Css::addFile('/plugins/Forums/css/forum.css');

		// ADD JS
		Javascript::addFile('/plugins/Forums/js/forum.js');

		// VIEW VARIABLES
		$this->set('Thread', $Thread);
		$this->set('entries', $entries);
		$this->set('language', $this->core->language);
		$this->set('user', $this->core->user);
		$this->set('date_format', $this->dateFormat);
		$this->set('time_format', $this->timeFormat);

		$this->set('userLoginCtl', $this->userLoginCtl);
		$this->set('userLoginMethod', $this->userLoginMethod);
		$this->set('userRegisterCtl', $this->userRegisterCtl);
		$this->set('userRegisterMethod', $this->userRegisterMethod);

		// VIEW OPTIONS
		$this->set('navi', $navigation);
		$this->set('headline', $Forum->name.' '.$this->core->language->forum);
		$this->view('add_post');

		// LAYOUT OPTIONS
		if  ( Config::exists('layout', 'forum') )
		{
			$layout = Config::get('layout', 'forum');
			$this->layout($layout[0], $layout[1], isset($layout[3]) ? $layout[3] : array());
		}
	}
}