<?phpclass Visitors extends Controller{	public $helpers		= array('Html', 'Form');	public $adminArea	= TRUE;			public function lastVisits($count = NULL)	{		if ( !$this->user->isAdmin() )			$this->redirectBackend('Login', 'enter');					if ($count == NULL)			$count = 500;		$sessCount		= $this->model->Visitors->countUniqueSessVisitors();		$hostCount		= $this->model->Visitors->countUniqueHostVisitors();		$visitors		= $this->model->Visitors->getAll(NULL, array('id' => 'DESC'), $count);		$total			= $this->model->Visitors->countAll();				$this->set('sessCount', $sessCount);		$this->set('hostCount', $hostCount);		$this->set('visitors', $visitors);		$this->set('count', $count);		$this->set('total', $total);				$this->set('headline', 'Last Visists');		$this->view('visitors.tpl.php');	}	public function showUniqueSession($session_id = NULL)	{		if ( !$this->user->isAdmin() )			$this->redirectBackend('Login', 'enter');					$visitors		= $this->model->Visitors->getUniqueSessions($session_id);					$this->set('visitors', $visitors);					$this->set('headline', 'Unique Sessions');		$this->view('visitors.tpl.php');	}	public function showUniqueHost($hostname = NULL)	{		if ( !$this->user->isAdmin() )			$this->redirectBackend('Login', 'enter');					$visitors		= $this->model->Visitors->getUniqueHosts($hostname);							$this->set('headline', 'Unique Hosts');		$this->set('visitors', $visitors);					$this->view('visitors.tpl.php');	}	}?>