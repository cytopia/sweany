<?phpclass Forums extends Controller{	public $helpers = array('Html', 'HtmlTemplate', 'Form', 'Javascript', 'Css');	public $package = 'Forums';	protected $formValidator = array(		// Form for adding threads		'form_add_thread'	=> array(			'forum_id'	=> array(				'equalsUrlParam' => array(					'rule'	=> array('equalsUrlParam', 0),					'error'	=> 'Ung&uuml;ltiges Forum',				),			),			'title' => array(				'minLen' => array(					'rule'	=> array('minLen', 1),					'error'	=> 'Der Titel ist erforderlich',				),				'minLen' => array(					'rule'	=> array('maxLen', 70),					'error'	=> 'Max. 70 Zeichen',				),			),			'body' => array(				'minLen' => array(					'rule'	=> array('minLen', 1),					'error'	=> 'Schreibe deinen Beitrag hier',				),			),		),		// Form for adding posts		'form_add_post'	=> array(			'forum_id'	=> array(				'equalsUrlParam' => array(					'rule'	=> array('equalsUrlParam', 0),					'error'	=> 'Ung&uuml;ltiges Forum',				),			),			'thread_id'	=> array(				'equalsUrlParam' => array(					'rule'	=> array('equalsUrlParam', 1),					'error'	=> 'Ung&uuml;ltiges Forum',				),			),			'body' => array(				'minLen' => array(					'rule'	=> array('minLen', 1),					'error'	=> 'Schreibe deine Antwort hier',				),			),		),	);	/* **********************************************************************************************************************	*	*   S E T T I N G S	*	* **********************************************************************************************************************/	private $dateFormat			= 'd.m.Y';	private $timeFormat			= 'H:i';	private $txtForumName		= 'Forum';	private $txtLastEntry		= 'Letzter Beitrag';	private $txtLastPost		= 'Letzte Antwort';	private $txtThread			= 'Thema';	private $txtThreads			= 'Themen';	private $txtNewThread		= 'Neues Thema';	private $txtCreateNewThread	= 'Neues Thema erstellen';	private $txtCreateThreadBtn	= 'Thema erstellen';	private $txtAnswerThreadBtn	= 'Antworten';	private $txtPreviewBtn		= 'Vorschau';	private $txtGoAdvancedBtn	= 'Erweitert';	private $txtReplyThread		= 'Auf Thema antworten';	private $txtThreadIsSticky	= 'Der Beitrag ist gepinnt';	private $txtThreadIsLocked	= 'Der Beitrag wurde gesperrt';	private $txtThreadIsClosed	= 'Der Beitrag wurde geschlossen';	private $txtPosts			= 'Antworten';	private $txtReplies			= 'Antworten';	private $txtAnswer			= 'Antworten';	private $txtDirectAnswer	= 'Direkt antworten';	private $txtMessage			= 'Nachricht';	private $txtTitle			= 'Titel';	private $txtUser			= 'Benutzer';	private $txtAuthor			= 'Autor';	private $txtCurrentOnline	= 'Derzeit aktive Benutzer';	private $txtRegisteredUsers	= 'registrierte Benutzer';	private $txtRegisteredUser	= 'registrierter Benutzer';	private $txtGuestUsers		= 'G&auml;ste';	private $txtGuestUser		= 'Gast';	private $txtCreatedBy		= 'von';	private $txtViews			= 'Aufrufe';	private $txtCannotThread	= 'Du kannst in diesem Forum keinen neuen Beitrag erstellen.';	private $txtCannotReply		= 'Du kannst in diesem Forum nicht antworten';	// Controller Defines needed to build <href> links in the views	private $userProfileCtl		= 'Profiles';	private $userProfileMethod	= 'ansehen';	private $userLoginCtl		= 'User';	private $userLoginMethod	= 'login';	private $userRegisterCtl	= 'User';	private $userRegisterMethod	= 'login';	private $userMessageToCtl	= 'Nachrichten';	private $userMessageToMethod= 'write';	/* **********************************************************************************************************************	*	*    A J A X   F U N C T I O N S	*	* **********************************************************************************************************************/	public function ajax_edit_post($post_id)	{		header('Content-Type: text/html; charset=utf-8');		// do not render		$this->render = false;		if ( !isset($_POST['postId']) )				{return -1;	}	// If no post value is set, exit		if ( $_POST['postId'] != $post_id )			{return -2;	}	// basic check: if POST and GET values differ, return false		if ( !isset($_POST['body']))				{return -3; }	// Body is not set!!!		if ( !$this->user->isLoggedIn() )			{return -4;	}	// If not logged in you cannot edit post		if ( !$this->model->postExists($post_id) )	{return -5;	}	// If the Post does not exist, you cannot edit it		if ( !$this->model->isMyPost($post_id, $this->user->id()) ) {return -6;}	// If it is not my post, I cannot edit it		$postBody = $_POST['body'];		$this->model->ForumPosts->update($post_id, $postBody);		$post= $this->model->getPost($post_id);		// return the new box		$box = '<strong>'.$post['title'].'</strong><br/><hr/><br/>';		$box.= Bbcode::parse($post['body']);		$box.= '<br/><br/>';		return $box;	}	public function ajax_edit_thread($thread_id)	{		header('Content-Type: text/html; charset=utf-8');		// do not render		$this->render = false;		if ( !isset($_POST['threadId']) )				{return -1;}	// If no post value is set, exit		if ( $_POST['threadId'] != $thread_id )			{return -2;}	// basic check: if POST and GET values differ, return false		if ( !isset($_POST['body']))					{return -3;}	// Body is not set!!!		if ( !$this->user->isLoggedIn() )				{return -4;}	// If not logged in you cannot edit post		if ( !$this->model->threadExists($thread_id) )	{return -5;	}	// If the Post does not exist, you cannot edit it		if ( !$this->model->isMyThread($thread_id, $this->user->id()) ) {return -6;}	// If it is not my post, I cannot edit it		$threadBody = $_POST['body'];		$this->model->ForumThreads->update($thread_id, $threadBody);		$thread= $this->model->getThread($thread_id);		// return the new box		$box = '<strong>'.$thread['title'].'</strong><br/><hr/><br/>';		$box.= Bbcode::parse($thread['body']);		$box.= '<br/><br/>';		return $box;	}	public function ajax_get_quick_edit_post_box($post_id = null)	{		header('Content-Type: text/html; charset=utf-8');		// do not render		$this->render = false;		if ( !isset($_POST['postId']) )				{ return ''; }	// If no post value is set, exit		if ( $_POST['postId'] != $post_id )			{ return ''; }	// basic check: if POST and GET values differ, return false		if ( !$this->user->isLoggedIn() )			{ return ''; }	// If not logged in you cannot edit post		if ( !$this->model->postExists($post_id) )	{ return ''; }	// If the Post does not exist, you cannot edit it		if ( !$this->model->isMyPost($post_id, $this->user->id()) ) { return ''; }	// If it is not my post, I cannot edit it		$post= $this->model->getPost($post_id);		$box = '<div style="text-align:left; padding:8px; border:solid 1px black; width:505px; background-color:#FDFDFD;">';		$box.= 		'<div style="height:20px;">';		$box.=			$this->model->getMessageBBCodeIconBar('quickEditBoxText');		$box.= 		'</div>';		$box.= 		'<div>';		$box.= 			$this->form->textArea('body', 60, 5, $post['body'], array('id' => 'quickEditBoxText'));		$box.= 		'</div>';		$box.=		'<button onclick=\'submitEditPost('.$post_id.')\'>&auml;ndern</button>';		$box.=		'<button onclick=\'cancelEdit()\'>abbrechen</button>';		$box.= '</div><br/>';		return $box;	}	public function ajax_get_quick_edit_thread_box($thread_id = null)	{		header('Content-Type: text/html; charset=utf-8');		// do not render		$this->render = false;		if ( !isset($_POST['threadId']) )				{return '';}	// If no post value is set, exit		if ( $_POST['threadId'] != $thread_id )			{return '';}	// basic check: if POST and GET values differ, return false		if ( !$this->user->isLoggedIn() )				{return '';}	// If not logged in you cannot edit post		if ( !$this->model->threadExists($thread_id) )	{return '';}	// If the Post does not exist, you cannot edit it		if ( !$this->model->isMyThread($thread_id, $this->user->id()) ) {return '';}	// If it is not my post, I cannot edit it		$thread = $this->model->getThread($thread_id);		$box = '<div style="text-align:left; padding:8px; border:solid 1px black; width:505px; background-color:#FDFDFD;">';		$box.= 		'<div style="height:20px;">';		$box.=			$this->model->getMessageBBCodeIconBar('quickEditBoxText');		$box.= 		'</div>';		$box.= 		'<div>';		$box.= 			$this->form->textArea('body', 60, 5, $thread['body'], array('id' => 'quickEditBoxText'));		$box.= 		'</div>';		$box.=		'<button onclick=\'submitEditThread('.$thread_id.')\'>&auml;ndern</button>';		$box.=		'<button onclick=\'cancelEdit()\'>abbrechen</button>';		$box.= '</div><br/>';		return $box;	}	/* **********************************************************************************************************************	*	*   F U N C T I O N S	*	* **********************************************************************************************************************/	public function show()	{		$categories = $this->model->getForum();		// ADD TEMPLATE ELEMENTS		$this->htmltemplate->setTitle('Forum');		// ADD CSS		$this->css->addFile('/css/forum.css');		// VIEW VARIABLES		$this->set('categories', $categories);		$this->set('date_format', $this->dateFormat);		$this->set('time_format', $this->timeFormat);		$this->set('txtThreads', $this->txtThreads);		$this->set('txtPosts', $this->txtPosts);		$this->set('txtLastEntry', $this->txtLastEntry);		$this->set('txtCreatedBy', $this->txtCreatedBy);		$this->set('txtRegisteredUsers', $this->txtRegisteredUsers);		$this->set('txtRegisteredUser', $this->txtRegisteredUser);		$this->set('txtGuestUsers', $this->txtGuestUsers);		$this->set('txtGuestUser', $this->txtGuestUser);		$this->set('txtCurrentOnline', $this->txtCurrentOnline);		$this->set('countOnlineUsers', $this->user->countOnlineUsers());		$this->set('countLoggedInOnlineUsers', $this->user->countLoggedInOnlineUsers());		$this->set('countAnonymousOnlineUsers', $this->user->countAnonymousOnlineUsers());		$this->set('LoggedInOnlineUsers', $this->user->getLoggedInOnlineUsers());		$this->set('userProfileCtl', $this->userProfileCtl);		$this->set('userProfileMethod', $this->userProfileMethod);		// VIEW OPTIONS		$this->set('menu', 'forum');		$this->set('headline', 'Forum');		$this->view('show.tpl.php');	}	public function showForum($forum_id = null, $seo_url = null)	{		if ( !$this->model->forumExists($forum_id) )		{			$this->redirect(NULL, 'show');			return;		}		if ( !$this->model->ForumForums->isDisplayable($forum_id) )		{			$this->redirect(NULL, 'show');			return;		}		if ( $this->model->getForumSeoUrl($forum_id) != $seo_url )		{			$this->redirect(NULL, NULL, array($forum_id, $this->model->getForumSeoUrl($forum_id)));			return;		}		$forum_name = $this->model->getForumName($forum_id);		$threads	= $this->model->getThreads($forum_id);		// check wheter thread or post was last and sort by it accordingly		usort($threads, array('ForumsModel', 'sortForumThreadsByLastEntry'));		$can_create	= $this->model->ForumForums->canCreate($forum_id);		$isAdmin	= $this->user->isAdmin();		$navigation	= $this->html->l($this->txtForumName, __CLASS__, 'show').' -&gt; '.$forum_name;		// ADD TEMPLATE ELEMENTS		$this->htmltemplate->setTitle($forum_name.' Forum');		// ADD CSS		$this->css->addFile('/css/forum.css');		// VIEW VARIABLES		$this->set('forum_name', $forum_name);		$this->set('forum_id', $forum_id);		$this->set('can_create', $can_create);		$this->set('isAdmin', $isAdmin);		$this->set('threads', $threads);		$this->set('date_format', $this->dateFormat);		$this->set('time_format', $this->timeFormat);		$this->set('txtAuthor', $this->txtAuthor);		$this->set('txtLastPost', $this->txtLastPost);		$this->set('txtThreads', $this->txtThreads);		$this->set('txtReplies', $this->txtReplies);		$this->set('txtViews', $this->txtViews);		$this->set('txtCannotThread', $this->txtCannotThread);		$this->set('txtNewThread', $this->txtNewThread);		$this->set('userProfileCtl', $this->userProfileCtl);		$this->set('userProfileMethod', $this->userProfileMethod);		$this->set('txtThreadIsSticky', $this->txtThreadIsSticky);		$this->set('txtThreadIsClosed', $this->txtThreadIsClosed);		$this->set('txtThreadIsLocked', $this->txtThreadIsLocked);		// VIEW OPTIONS		$this->set('navi', $navigation);		$this->set('menu', 'forum');		$this->set('headline', $forum_name.' Forum');		$this->view('show_forum.tpl.php');	}	public function showThread($forum_id = null, $thread_id = null, $seo_url = null)	{		$forum_seo_url = $this->model->getForumSeoUrl($forum_id);		if ( !$this->model->forumExists($forum_id) )		{			$this->redirect(NULL, 'show');			return;		}		if ( !$this->model->threadExists($thread_id) )		{			$this->redirect(NULL, 'showForum', array($forum_id, $forum_seo_url));			return;		}		if ( !$this->model->forumOwnsThread($forum_id, $thread_id) )		{			$this->redirect(NULL, 'showForum', array($forum_id, $forum_seo_url));			return;		}		if ( $this->model->getThreadSeoUrl($thread_id) != $seo_url )		{			$this->redirect(NULL, NULL, array($forum_id, $thread_id, $this->model->getThreadSeoUrl($thread_id)));			return;		}		if ( !$this->user->isLoggedIn() )		{			// SET SESSION			// Also inform user to log in, he will be redirected here afterwards			Session::set('referrer', array('controller'	=> __CLASS__, 'method' => __FUNCTION__, 'params' => array($forum_id, $thread_id, $seo_url)));		}		$can_reply	= $this->model->ForumForums->canReply($forum_id);		$thread		= $this->model->getThreadWithUserInfo($thread_id);		// ------------------------- FORM SUBMITTED AND VALID -------------------------		if ( $this->validateForm('form_add_post') && $this->user->isLoggedIn() && !$thread['is_closed'] && !$thread['is_locked'] )		{			// ------------------------- GET FORM VALUES -------------------------			$post_forum_id	= $this->form->getValue('forum_id');			$post_thread_id	= $this->form->getValue('thread_id');			$title			= $this->form->getValue('title');			$body			= $this->form->getValue('body');			$user_id		= $this->user->id();			$title			= Strings::removeTags($title);			$post_id		= $this->model->ForumPosts->add($thread_id, $title, $body, $user_id);			$this->redirect(NULL, 'showThread', array($forum_id, $thread_id, $this->model->getThreadSeoUrl($thread_id)));			return;		}		else		{			$this->model->updateThreadView($thread_id);		}		$forum_name 	= $this->model->getForumName($forum_id);		$posts			= $this->model->getPostsWithUserInfo($thread_id);		$navigation	= $this->html->l($this->txtForumName, __CLASS__, 'show').' -&gt; '.$this->html->l($forum_name, __CLASS__, 'showForum', array($forum_id, $forum_seo_url));		// ADD TEMPLATE ELEMENTS		$this->htmltemplate->setTitle($forum_name.' - '.$thread['title']);		// ADD CSS		$this->css->addFile('/css/forum.css');		// ADD JS		$this->javascript->addFile('/js/ajax.js');		$this->javascript->addFile('/js/profiles_thank_user.js');		$this->javascript->addFile('/js/forum.js');		// VIEW VARIABLES		$this->set('can_reply', $can_reply);		$this->set('forum_name', $forum_name);		$this->set('forum_id', $forum_id);		$this->set('thread_id', $thread_id);		$this->set('thread', $thread);		$this->set('posts', $posts);		$this->set('messageBBCodeIconBar', $this->model->getMessageBBCodeIconBar('postMessage'));		$this->set('date_format', $this->dateFormat);		$this->set('time_format', $this->timeFormat);		$this->set('txtCannotReply', $this->txtCannotReply);		$this->set('txtDirectAnswer', $this->txtDirectAnswer);		$this->set('txtAnswer', $this->txtAnswer);		$this->set('txtMessage', $this->txtMessage);		$this->set('txtGoAdvancedBtn', $this->txtGoAdvancedBtn);		$this->set('userProfileCtl', $this->userProfileCtl);		$this->set('userProfileMethod', $this->userProfileMethod);		$this->set('userLoginCtl', $this->userLoginCtl);		$this->set('userLoginMethod', $this->userLoginMethod);		$this->set('userRegisterCtl', $this->userRegisterCtl);		$this->set('userRegisterMethod', $this->userRegisterMethod);		$this->set('userMessageToCtl', $this->userMessageToCtl);		$this->set('userMessageToMethod', $this->userMessageToMethod);		// VIEW OPTIONS		$this->set('navi', $navigation);		$this->set('menu', 'forum');		$this->set('headline', $forum_name.' Forum');		$this->view('show_thread.tpl.php');	}	public function addThread($forum_id = null)	{		if ( !$this->model->forumExists($forum_id) )		{			$this->redirect(NULL, 'show');			return;		}		if ( !$this->model->ForumForums->isDisplayable($forum_id) )		{			$this->redirect(NULL, 'show');			return;		}		if ( ! ($this->model->ForumForums->canCreate($forum_id) || $this->user->isAdmin()) )		{			$this->redirect(NULL, 'showForum', array($forum_id, $this->model->getForumSeoUrl($forum_id)));			return;		}		if ( !$this->user->isLoggedIn() )		{			// SET SESSION			// Also inform user to log in, he will be redirected here afterwards			Session::set('referrer', array('controller'	=> __CLASS__, 'method' => __FUNCTION__, 'params' => array($forum_id)));		}		// ------------------------- FORM SUBMITTED AND VALID -------------------------		if ( $this->validateForm('form_add_thread') && $this->user->isLoggedIn()  )		{			// Note:			// need to check for the submit button of this form			// as the user can also click 'preview'			if ( $this->form->fieldIsSet('add_thread_submit') )			{				// ------------------------- GET FORM VALUES -------------------------				$post_forum_id	= $this->form->getValue('forum_id');				$title			= $this->form->getValue('title');				$body			= $this->form->getValue('body');				$user_id		= $this->user->id();				$title			= Strings::removeTags($title);				$seo_url		= clean_url_param($title).'.html';				$thread_id		= $this->model->ForumThreads->add($forum_id, $title, $body, $user_id, $seo_url);				$this->redirect(NULL, 'showThread', array($forum_id, $thread_id, $this->model->getThreadSeoUrl($thread_id)));				return;			}			else if ( $this->form->fieldIsSet('add_thread_preview') )			{				$this->set('preview', true);				$this->set('threadPreview', array('title' => $this->form->getValue('title'), 'body' => $this->form->getValue('body')));			}		}		$forum_name = $this->model->getForumName($forum_id);		$navigation	= $this->html->l($this->txtForumName, __CLASSLL, 'show').' -&gt; '.$this->html->l($forum_name, __CLASS__, 'showForum', array($forum_id, $this->model->getForumSeoUrl($forum_id)));		// ADD TEMPLATE ELEMENTS		$this->htmltemplate->setTitle($forum_name.' - '.$this->txtCreateNewThread);		// ADD CSS		$this->css->addFile('/css/forum.css');		// ADD JS		$this->javascript->addFile('/js/forum.js');		// VIEW VARIABLES		$this->set('forum_id', $forum_id);		$this->set('forum_name', $forum_name);		$this->set('txtTitle', $this->txtTitle);		$this->set('txtMessage', $this->txtMessage);		$this->set('txtCreateNewThread', $this->txtCreateNewThread);		$this->set('txtCreateThreadBtn', $this->txtCreateThreadBtn);		$this->set('txtPreviewBtn', $this->txtPreviewBtn);		$this->set('messageBBCodeIconBar', $this->model->getMessageBBCodeIconBar('postBody'));		$this->set('userLoginCtl', $this->userLoginCtl);		$this->set('userLoginMethod', $this->userLoginMethod);		$this->set('userRegisterCtl', $this->userRegisterCtl);		$this->set('userRegisterMethod', $this->userRegisterMethod);		// VIEW OPTIONS		$this->set('navi', $navigation);		$this->set('menu', 'forum');		$this->set('headline', $forum_name.' Forum');		$this->view('add_thread.tpl.php');	}	public function addPost($forum_id = null, $thread_id = null)	{		// --------------- VALIDATE FORUM		if ( !$this->model->forumExists($forum_id) )		{			$this->redirect(NULL, 'show');			return;		}		if ( !$this->model->ForumForums->isDisplayable($forum_id) )		{			$this->redirect(NULL, 'show');			return;		}		if ( !$this->model->ForumForums->canReply($forum_id) )		{			$this->redirect(NULL, 'show');			return;		}		// --------------- VALIDATE TREAD		if ( !$this->model->threadExists($thread_id) )		{			$this->redirect(NULL, 'showForum', array($forum_id, $this->model->getForumSeoUrl($forum_id)));			return;		}		if ( !$this->model->forumOwnsThread($forum_id, $thread_id) )		{			$this->redirect(NULL, 'showForum', array($forum_id, $this->model->getForumSeoUrl($forum_id)));			return;		}		if ( $this->model->threadIsLocked($thread_id) )		{			$this->redirect(NULL, 'showForum', array($forum_id, $this->model->getForumSeoUrl($forum_id)));			return 6;		}		if ( $this->model->threadIsClosed($thread_id) )		{			$this->redirect(NULL, 'showForum', array($forum_id, $this->model->getForumSeoUrl($forum_id)));			return;		}		// --------------- VALIDATE USER		if ( !$this->user->isLoggedIn() )		{			// SET SESSION			// Also inform user to log in, he will be redirected here afterwards			Session::set('referrer', array('controller'	=> __CLASS__, 'method' => __FUNCTION__, 'params' => array($forum_id, $thread_id)));		}		// ------------------------- FORM SUBMITTED AND VALID -------------------------		if ( $this->validateForm('form_add_post') && $this->user->isLoggedIn() )		{			// Note:			// need to check for the submit button of this form			// as the user can be redirected here			// from quick edit via 'advanced', so we dont want to insert in that case			if ( $this->form->fieldIsSet('add_post_submit') )			{				// ------------------------- GET FORM VALUES -------------------------				$post_thread_id	= $this->form->getValue('thread_id');				$title			= $this->form->getValue('title');				$body			= $this->form->getValue('body');				$user_id		= $this->user->id();				$title			= Strings::removeTags($title);				$post_id		= $this->model->ForumPosts->add($thread_id, $title, $body, $user_id);				$this->redirect(NULL, 'showThread', array($forum_id, $thread_id, $this->model->getThreadSeoUrl($thread_id)));				return;			}			else if ( $this->form->fieldIsSet('add_post_preview') )			{				$this->set('preview', true);				$this->set('postPreview', array('title' => $this->form->getValue('title'), 'body' => $this->form->getValue('body')));			}		}		// Get Posts in reverse order (and append thread) to display below add box		$posts			= $this->model->ForumPosts->getPosts($thread_id, array('created' => 'DESC'));		$thread			= $this->model->getThread($thread_id);		$entries		= array_merge($posts, array($thread));		$forum_name = $this->model->getForumName($forum_id);		$navigation	= $this->html->l($this->txtForumName, __CLASS__, 'show').' -&gt; '.$this->html->l($forum_name, __CLASS__, 'showForum', array($forum_id, $this->model->getForumSeoUrl($forum_id))).' -&gt; '.$thread['title'];		// ADD TEMPLATE ELEMENTS		$this->htmltemplate->setTitle($forum_name.' - '.$this->txtReplyThread);		// ADD CSS		$this->css->addFile('/css/forum.css');		// ADD JS		$this->javascript->addFile('/js/forum.js');		// VIEW VARIABLES		$this->set('forum_id', $forum_id);		$this->set('thread_id', $thread_id);		$this->set('forum_name', $forum_name);		$this->set('entries', $entries);		$this->set('date_format', $this->dateFormat);		$this->set('time_format', $this->timeFormat);		$this->set('messageBBCodeIconBar', $this->model->getMessageBBCodeIconBar('postBody'));		$this->set('txtTitle', $this->txtTitle);		$this->set('txtMessage', $this->txtMessage);		$this->set('txtReplyThread', $this->txtReplyThread);		$this->set('txtAnswerThreadBtn', $this->txtAnswerThreadBtn);		$this->set('txtPreviewBtn', $this->txtPreviewBtn);		$this->set('userLoginCtl', $this->userLoginCtl);		$this->set('userLoginMethod', $this->userLoginMethod);		$this->set('userRegisterCtl', $this->userRegisterCtl);		$this->set('userRegisterMethod', $this->userRegisterMethod);		// VIEW OPTIONS		$this->set('navi', $navigation);		$this->set('menu', 'forum');		$this->set('headline', $forum_name.' Forum');		$this->view('add_post.tpl.php');	}}?>