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

		if ( !isset($_POST['postId']) )				{return -1;	}	// If no post value is set, exit
		if ( $_POST['postId'] != $post_id )			{return -2;	}	// basic check: if POST and GET values differ, return false
		if ( !isset($_POST['body']))				{return -3; }	// Body is not set!!!
		if ( !$this->user->isLoggedIn() )			{return -4;	}	// If not logged in you cannot edit post
		if ( !$this->model->postExists($post_id) )	{return -5;	}	// If the Post does not exist, you cannot edit it
		if ( !$this->model->isMyPost($post_id, $this->user->id()) ) {return -6;}	// If it is not my post, I cannot edit it

		$postBody = $_POST['body'];
		$this->model->ForumPosts->update($post_id, $postBody);
		$post= $this->model->getPost($post_id);

		// return the new box
		$box = '<strong>'.$post['title'].'</strong><br/><hr/><br/>';
		$box.= Bbcode::parse($post['body'], '/plugins/Forums/img/smiley');
		$box.= '<br/><br/>';
		return $box;
	}
	public function ajax_edit_thread($thread_id)
	{
		header('Content-Type: text/html; charset=utf-8');

		// do not render
		$this->render = false;

		if ( !isset($_POST['threadId']) )				{return -1;}	// If no post value is set, exit
		if ( $_POST['threadId'] != $thread_id )			{return -2;}	// basic check: if POST and GET values differ, return false
		if ( !isset($_POST['body']))					{return -3;}	// Body is not set!!!
		if ( !$this->user->isLoggedIn() )				{return -4;}	// If not logged in you cannot edit post
		if ( !$this->model->threadExists($thread_id) )	{return -5;	}	// If the Post does not exist, you cannot edit it
		if ( !$this->model->isMyThread($thread_id, $this->user->id()) ) {return -6;}	// If it is not my post, I cannot edit it

		$threadBody = $_POST['body'];
		$this->model->ForumThreads->update($thread_id, $threadBody);
		$thread = $this->model->getThread($thread_id);

		// return the new box
		$box = '<strong>'.$thread['title'].'</strong><br/><hr/><br/>';
		$box.= Bbcode::parse($thread['body'], '/plugins/Forums/img/smiley');
		$box.= '<br/><br/>';
		return $box;
	}

	public function ajax_get_quick_edit_post_box($post_id = null)
	{
		header('Content-Type: text/html; charset=utf-8');

		// do not render
		$this->render = false;

		if ( !isset($_POST['postId']) )				{ return ''; }	// If no post value is set, exit
		if ( $_POST['postId'] != $post_id )			{ return ''; }	// basic check: if POST and GET values differ, return false
		if ( !$this->user->isLoggedIn() )			{ return ''; }	// If not logged in you cannot edit post
		if ( !$this->model->postExists($post_id) )	{ return ''; }	// If the Post does not exist, you cannot edit it
		if ( !$this->model->isMyPost($post_id, $this->user->id()) ) { return ''; }	// If it is not my post, I cannot edit it

		$post= $this->model->getPost($post_id);

		$box = '<div style="text-align:left; padding:8px; border:solid 1px black; width:505px; background-color:#FDFDFD;">';
		$box.= 		'<div style="height:20px;">';
		$box.=			$this->model->getMessageBBCodeIconBar('quickEditBoxText');
		$box.= 		'</div>';
		$box.= 		'<div>';
		$box.= 			Form::textArea('body', 60, 5, $post['body'], array('id' => 'quickEditBoxText'));
		$box.= 		'</div>';
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

		if ( !isset($_POST['threadId']) )				{return '';}	// If no post value is set, exit
		if ( $_POST['threadId'] != $thread_id )			{return '';}	// basic check: if POST and GET values differ, return false
		if ( !$this->user->isLoggedIn() )				{return '';}	// If not logged in you cannot edit post
		if ( !$this->model->threadExists($thread_id) )	{return '';}	// If the Post does not exist, you cannot edit it
		if ( !$this->model->isMyThread($thread_id, $this->user->id()) ) {return '';}	// If it is not my post, I cannot edit it

		$thread = $this->model->getThread($thread_id);

		$box = '<div style="text-align:left; padding:8px; border:solid 1px black; width:505px; background-color:#FDFDFD;">';
		$box.= 		'<div style="height:20px;">';
		$box.=			$this->model->getMessageBBCodeIconBar('quickEditBoxText');
		$box.= 		'</div>';
		$box.= 		'<div>';
		$box.= 			Form::textArea('body', 60, 5, $thread['body'], array('id' => 'quickEditBoxText'));
		$box.= 		'</div>';
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
		$data = $this->model->ForumCategories->find('all', array('recursive' => 2));
		debug($data);
		
		$this->attachBlock('bOnlineUsers', 'Forums', 'Forum', 'onlineUsers');

		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($this->language->forum);

		// ADD CSS
		Css::addFile('/plugins/Forums/css/forum.css');

		// VIEW VARIABLES
		$this->set('language', $this->language);
		$this->set('data', $data);
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
		if ( !$this->model->forumExists($forum_id) )
		{
			$this->redirect(__CLASS__, 'index');
			return;
		}
		if ( !$this->model->ForumForums->isDisplayable($forum_id) )
		{
			$this->redirect(__CLASS__, 'index');
			return;
		}
		if ( $this->model->getForumSeoUrl($forum_id) != $seo_url )
		{
			$this->redirect(__CLASS__, __FUNCTION__, array($forum_id, $this->model->getForumSeoUrl($forum_id)));
			return;
		}


		$forum_name 	= $this->model->getForumName($forum_id);
		$threads		= $this->model->getThreads($forum_id);

		// check wheter thread or post was last and sort by it accordingly
		usort($threads, array('ForumsModel', 'sortForumThreadsByLastEntry'));

		$can_create		= $this->model->ForumForums->canCreate($forum_id);
		$isAdmin		= $this->user->isAdmin();

		$bOnlineUsers	= Blocks::get('Forums', 'Forum', 'onlineUsers');
		$navigation		= Html::l($this->language->forum, __CLASS__, '').' -&gt; '.$forum_name;


		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($forum_name.' '.$this->language->forum);

		// ADD CSS
		Css::addFile('/plugins/Forums/css/forum.css');

		// VIEW VARIABLES
		$this->set('language', $this->language);
		$this->set('bOnlineUsers', $bOnlineUsers['html']);
		$this->set('forum_name', $forum_name);
		$this->set('forum_id', $forum_id);
		$this->set('can_create', $can_create);
		$this->set('isAdmin', $isAdmin);
		$this->set('threads', $threads);

		$this->set('date_format', $this->dateFormat);
		$this->set('time_format', $this->timeFormat);

		$this->set('userProfileLink', $this->userProfileLink);
		$this->set('userProfileCtl', $this->userProfileCtl);
		$this->set('userProfileMethod', $this->userProfileMethod);

		// VIEW OPTIONS
		$this->set('navi', $navigation);
		$this->set('headline', $forum_name.' '.$this->language->forum);
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
		$forum_seo_url = $this->model->getForumSeoUrl($forum_id);


		if ( !$this->model->forumExists($forum_id) )
		{
			$this->redirect(__CLASS__, 'index');
			return;
		}
		if ( !$this->model->threadExists($thread_id) )
		{
			$this->redirect(__CLASS__, 'showForum', array($forum_id, $forum_seo_url));
			return;
		}
		if ( !$this->model->forumOwnsThread($forum_id, $thread_id) )
		{
			$this->redirect(__CLASS__, 'showForum', array($forum_id, $forum_seo_url));
			return;
		}
		if ( $this->model->getThreadSeoUrl($thread_id) != $seo_url )
		{
			$this->redirect(__CLASS__, __FUNCTION__, array($forum_id, $thread_id, $this->model->getThreadSeoUrl($thread_id)));
			return;
		}
		if ( !$this->user->isLoggedIn() )
		{
			// SET SESSION
			// Also inform user to log in, he will be redirected here afterwards
			Session::set('referrer', array('controller'	=> __CLASS__, 'method' => __FUNCTION__, 'params' => array($forum_id, $thread_id, $seo_url)));
		}

		$can_reply	= $this->model->ForumForums->canReply($forum_id);
		$thread		= $this->model->getThreadWithUserInfo($thread_id);

		// ------------------------- FORM SUBMITTED AND VALID -------------------------
		if ( $this->validateForm('form_add_post') && $this->user->isLoggedIn() && !$thread['is_closed'] && !$thread['is_locked'] )
		{
			// ------------------------- GET FORM VALUES -------------------------
			$post_forum_id	= Form::getValue('forum_id');
			$post_thread_id	= Form::getValue('thread_id');

			$title			= Form::getValue('title');
			$body			= Form::getValue('body');
			$user_id		= $this->user->id();
			$title			= Strings::removeTags($title);
			$post_id		= $this->model->ForumPosts->add($thread_id, $title, $body, $user_id);

			$this->redirect(__CLASS__, 'showThread', array($forum_id, $thread_id, $this->model->getThreadSeoUrl($thread_id)));
			return;
		}
		else
		{
			$this->model->updateThreadView($thread_id);
		}

		$forum_name 	= $this->model->getForumName($forum_id);
		$posts			= $this->model->getPostsWithUserInfo($thread_id);


		$bOnlineUsers	= Blocks::get('Forums', 'Forum', 'onlineUsers');
		$navigation		= Html::l($this->language->forum, __CLASS__, 'index').' -&gt; '.Html::l($forum_name, __CLASS__, 'showForum', array($forum_id, $forum_seo_url));


		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($forum_name.' - '.$thread['title']);

		// ADD CSS
		Css::addFile('/plugins/Forums/css/forum.css');

		// ADD JS
		Javascript::addFile('/plugins/Forums/js/ajax.js');
		Javascript::addFile('/plugins/Forums/js/forum.js');

		// VIEW VARIABLES
		$this->set('language', $this->language);
		$this->set('bOnlineUsers', $bOnlineUsers['html']);
		$this->set('user', $this->user);
		$this->set('can_reply', $can_reply);
		$this->set('forum_name', $forum_name);
		$this->set('forum_id', $forum_id);
		$this->set('thread_id', $thread_id);
		$this->set('thread', $thread);
		$this->set('posts', $posts);
		$this->set('messageBBCodeIconBar', $this->model->getMessageBBCodeIconBar('postMessage'));
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
		$this->set('headline', $forum_name.' '.$this->language->forum);
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
		if ( !$this->model->forumExists($forum_id) )
		{
			$this->redirect(__CLASS__, 'index');
			return;
		}
		if ( !$this->model->ForumForums->isDisplayable($forum_id) )
		{
			$this->redirect(__CLASS__, 'index');
			return;
		}
		if ( ! ($this->model->ForumForums->canCreate($forum_id) || $this->user->isAdmin()) )
		{
			$this->redirect(__CLASS__, 'showForum', array($forum_id, $this->model->getForumSeoUrl($forum_id)));
			return;
		}
		if ( !$this->user->isLoggedIn() )
		{
			// SET SESSION
			// Also inform user to log in, he will be redirected here afterwards
			Session::set('referrer', array('controller'	=> __CLASS__, 'method' => __FUNCTION__, 'params' => array($forum_id)));
		}


		// ------------------------- FORM SUBMITTED AND VALID -------------------------
		if ( $this->validateForm('form_add_thread') && $this->user->isLoggedIn()  )
		{
			// Note:
			// need to check for the submit button of this form
			// as the user can also click 'preview'
			if ( Form::fieldIsSet('add_thread_submit') )
			{
				// ------------------------- GET FORM VALUES -------------------------
				$post_forum_id	= Form::getValue('forum_id');
				$title			= Form::getValue('title');
				$body			= Form::getValue('body');
				$user_id		= $this->user->id();

				$title			= Strings::removeTags($title);
				$seo_url		= Url::cleanUrlParams($title).'.html';

				$thread_id		= $this->model->ForumThreads->add($forum_id, $title, $body, $user_id, $seo_url);

				$this->redirect(__CLASS__, 'showThread', array($forum_id, $thread_id, $this->model->getThreadSeoUrl($thread_id)));
				return;
			}
			else if ( Form::fieldIsSet('add_thread_preview') )
			{
				$this->set('preview', true);
				$this->set('threadPreview', array('title' => Form::getValue('title'), 'body' => Form::getValue('body')));
			}
		}

		$forum_name = $this->model->getForumName($forum_id);
		$navigation	= Html::l($this->language->forum, __CLASS__, 'index').' -&gt; '.Html::l($forum_name, __CLASS__, 'showForum', array($forum_id, $this->model->getForumSeoUrl($forum_id)));


		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($forum_name.' - '.$this->language->createThread);

		// ADD CSS
		Css::addFile('/plugins/Forums/css/forum.css');

		// ADD JS
		Javascript::addFile('/plugins/Forums/js/forum.js');

		// VIEW VARIABLES
		$this->set('language', $this->language);
		$this->set('user', $this->user);
		$this->set('forum_id', $forum_id);
		$this->set('forum_name', $forum_name);
		$this->set('messageBBCodeIconBar', $this->model->getMessageBBCodeIconBar('postBody'));

		$this->set('userLoginCtl', $this->userLoginCtl);
		$this->set('userLoginMethod', $this->userLoginMethod);
		$this->set('userRegisterCtl', $this->userRegisterCtl);
		$this->set('userRegisterMethod', $this->userRegisterMethod);

		// VIEW OPTIONS
		$this->set('navi', $navigation);
		$this->set('headline', $forum_name.' '.$this->language->forum);
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
		// --------------- VALIDATE FORUM
		if ( !$this->model->forumExists($forum_id) )
		{
			$this->redirect(__CLASS__, 'index');
			return;
		}
		if ( !$this->model->ForumForums->isDisplayable($forum_id) )
		{
			$this->redirect(__CLASS__, 'index');
			return;
		}
		if ( !$this->model->ForumForums->canReply($forum_id) )
		{
			$this->redirect(__CLASS__, 'index');
			return;
		}

		// --------------- VALIDATE TREAD
		if ( !$this->model->threadExists($thread_id) )
		{
			$this->redirect(__CLASS__, 'showForum', array($forum_id, $this->model->getForumSeoUrl($forum_id)));
			return;
		}
		if ( !$this->model->forumOwnsThread($forum_id, $thread_id) )
		{
			$this->redirect(__CLASS__, 'showForum', array($forum_id, $this->model->getForumSeoUrl($forum_id)));
			return;
		}
		if ( $this->model->threadIsLocked($thread_id) )
		{
			$this->redirect(__CLASS__, 'showForum', array($forum_id, $this->model->getForumSeoUrl($forum_id)));
			return 6;
		}
		if ( $this->model->threadIsClosed($thread_id) )
		{
			$this->redirect(__CLASS__, 'showForum', array($forum_id, $this->model->getForumSeoUrl($forum_id)));
			return;
		}

		// --------------- VALIDATE USER
		if ( !$this->user->isLoggedIn() )
		{
			// SET SESSION
			// Also inform user to log in, he will be redirected here afterwards
			Session::set('referrer', array('controller'	=> __CLASS__, 'method' => __FUNCTION__, 'params' => array($forum_id, $thread_id)));
		}

		// ------------------------- FORM SUBMITTED AND VALID -------------------------
		if ( $this->validateForm('form_add_post') && $this->user->isLoggedIn() )
		{
			// Note:
			// need to check for the submit button of this form
			// as the user can be redirected here
			// from quick edit via 'advanced', so we dont want to insert in that case
			if ( Form::fieldIsSet('add_post_submit') )
			{
				// ------------------------- GET FORM VALUES -------------------------
				$post_thread_id	= Form::getValue('thread_id');
				$title			= Form::getValue('title');
				$body			= Form::getValue('body');
				$user_id		= $this->user->id();
				$title			= Strings::removeTags($title);

				$post_id		= $this->model->ForumPosts->add($thread_id, $title, $body, $user_id);

				$this->redirect(__CLASS__, 'showThread', array($forum_id, $thread_id, $this->model->getThreadSeoUrl($thread_id)));
				return;
			}
			else if ( Form::fieldIsSet('add_post_preview') )
			{
				$this->set('preview', true);
				$this->set('postPreview', array('title' => Form::getValue('title'), 'body' => Form::getValue('body')));
			}
		}
		// Get Posts in reverse order (and append thread) to display below add box
		$posts			= $this->model->ForumPosts->getPosts($thread_id, array('created' => 'DESC'));
		$thread			= $this->model->getThread($thread_id);
		$entries		= array_merge($posts, array($thread));

		$forum_name = $this->model->getForumName($forum_id);
		$navigation	= Html::l($this->language->forum, __CLASS__, 'index').' -&gt; '.Html::l($forum_name, __CLASS__, 'showForum', array($forum_id, $this->model->getForumSeoUrl($forum_id))).' -&gt; '.$thread['title'];


		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($forum_name.' - '.$this->language->reply);

		// ADD CSS
		Css::addFile('/plugins/Forums/css/forum.css');

		// ADD JS
		Javascript::addFile('/plugins/Forums/js/forum.js');

		// VIEW VARIABLES
		$this->set('language', $this->language);
		$this->set('user', $this->user);
		$this->set('forum_id', $forum_id);
		$this->set('thread_id', $thread_id);
		$this->set('forum_name', $forum_name);
		$this->set('entries', $entries);
		$this->set('date_format', $this->dateFormat);
		$this->set('time_format', $this->timeFormat);

		$this->set('messageBBCodeIconBar', $this->model->getMessageBBCodeIconBar('postBody'));

		$this->set('userLoginCtl', $this->userLoginCtl);
		$this->set('userLoginMethod', $this->userLoginMethod);
		$this->set('userRegisterCtl', $this->userRegisterCtl);
		$this->set('userRegisterMethod', $this->userRegisterMethod);

		// VIEW OPTIONS
		$this->set('navi', $navigation);
		$this->set('headline', $forum_name.' '.$this->language->forum);
		$this->view('add_post');

		// LAYOUT OPTIONS
		if  ( Config::exists('layout', 'forum') )
		{
			$layout = Config::get('layout', 'forum');
			$this->layout($layout[0], $layout[1], isset($layout[3]) ? $layout[3] : array());
		}
	}
}