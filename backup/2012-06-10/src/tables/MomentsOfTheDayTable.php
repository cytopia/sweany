<?phpclass MomentsOfTheDayTable extends Table{	protected $table	= 'moments_of_the_day';	protected $fields	= array(		'id'				=> 'id',		'fk_user_id'		=> 'fk_user_id',		'fk_moment_id'		=> 'fk_moment_id',		'is_active'			=> 'is_active',		'is_finished'		=> 'is_finished',		'created'			=> 'created',		'modified'			=> 'modified',	);	public function getTodaysMomentIds($limit = 2)	{		$condition = sprintf('`is_active` = %d AND `is_finished` = %d', 1, 0);		return $this->getColumnFields('fk_moment_id', $condition, null, null, $limit);	}		public function getAllByUserId($user_id)	{		$condition = sprintf('`fk_user_id` = %d', $user_id);		return $this->_get(null, $condition, null, array('created' => 'DESC'));	}	public function add($data)	{		return $this->_add($data);	}}?>