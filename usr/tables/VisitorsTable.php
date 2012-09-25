<?phpclass VisitorsTable extends Table{	public $table	= 'visitors';	public $alias	= 'Visitor';	public $fields = array(		'id'			=> 'id',		'url'			=> 'url',		'referer'		=> 'referer',		'useragent'		=> 'useragent',		'ip'			=> 'ip',		'host'			=> 'host',		'session_id'	=> 'session_id',		'created'		=> 'created',		'fk_user_id'	);	protected $hasCreated	= array(		'created'		=> 'timestamp',	);	public $belongsTo = array(		'User'	=> array(			'table'			=> 'users',			'fields'		=> array('id', 'username'),			'foreignKey'	=> 'fk_user_id',			'subQueries'	=> array('test' => 'SELECT username FROM users WHERE User.id=Visitor.fk_user_id LIMIT 1'),			'recursive'		=> true,		),	);	public function countOnlineUsers()	{		$query = "SELECT					COUNT(*) AS count				FROM					(SELECT						*					FROM						visitors					WHERE						created > (NOW() - INTERVAL 5 MINUTE)					ORDER BY						created DESC					)				AS online_users				GROUP BY session_id";		$count = $this->db->select($query);		return isset($count[0]['count']) ? $count[0]['count'] : 0;	}	public function add()	{		$hostname	= gethostbyaddr($_SERVER['REMOTE_ADDR']);		$fields = array(			'url'		=> Url::getRequest(),			'referer'	=> isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',			'useragent'	=> $_SERVER['HTTP_USER_AGENT'],			'ip'		=> $_SERVER['REMOTE_ADDR'],			'host'		=> $hostname,			'session_id'=> Session::getId(),		);		return $this->save($fields);	}	public function countUniqueSessVisitors()	{		$query = "SELECT COUNT(*) AS count FROM (					SELECT						*					FROM						visitors					GROUP BY						session_id					) AS tbl";		return $this->db->select($query);	}	public function countUniqueHostVisitors()	{		$query = "SELECT COUNT(*) AS count FROM (					SELECT						*					FROM						visitors					GROUP BY						host					) AS tbl						";		return $this->db->select($query);	}	public function getUniqueSessions($session_id)	{		$query = sprintf("SELECT							*						FROM							visitors						WHERE							session_id = '%s'						ORDER BY							created DESC", $session_id);		return $this->db->select($query);	}	public function getUniqueHosts($hostname)	{		$query = sprintf("SELECT							*						FROM							visitors						WHERE							host = '%s'						ORDER BY							created DESC", $hostname);		return $this->db->select($query);	}}