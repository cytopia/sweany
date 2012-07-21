<?php

class Users extends CoreTemplate
{
	private static $tbl_users			= 'users';
	private static $tbl_user_groups		= 'user_groups';
	private static $tbl_failed_logins	= 'user_failed_logins';
	private static $tbl_online_users	= 'user_online';

	private static $onlineSinceMinutes	= 20;	// count online users from last XX Minutes till now
	private static $fakeOnlineGuests	= 10;	// set the amount of fake online guests

	public function __construct()
	{
		// Add current user to online users table
		MySql::insertRow(self::$tbl_online_users, array('time' => time(), 'fk_user_id' => self::id(), 'session_id' => Session::getId(), 'ip' => $_SERVER['REMOTE_ADDR'], 'current_page' => Url::$request));

		// Delete all entries since last XX minutes
		MySql::delete(self::$tbl_online_users, sprintf('`time` < %d', strtotime('-'.self::$onlineSinceMinutes.' minute', time())));
	}
	public static function initialize()
	{
		// TODO:
		return true;
	}

	public static function countOnlineUsers()
	{
		return (self::$fakeOnlineGuests + MySql::selectNumRows('SELECT DISTINCT `session_id` FROM '.self::$tbl_online_users));
	}
	public static function countLoggedInOnlineUsers()
	{
		return MySql::selectNumRows('SELECT
										fk_user_id
									FROM(
										SELECT		DISTINCT fk_user_id, session_id
										FROM		user_online
										WHERE		fk_user_id>0
										GROUP BY	session_id
									) AS tbl_result
									GROUP BY
										tbl_result.fk_user_id');
	}
	public static function countAnonymousOnlineUsers()
	{
		return (self::$fakeOnlineGuests +
				MySql::selectNumRows('SELECT
										DISTINCT session_id
									FROM
										user_online
									WHERE
										fk_user_id=0 AND
										session_id NOT IN
										(SELECT session_id
											FROM(
												SELECT		DISTINCT fk_user_id, session_id
												FROM		user_online
												WHERE		fk_user_id>0
												GROUP BY	session_id
											) AS tbl_result
										GROUP BY
											tbl_result.fk_user_id
										)
									GROUP BY
										session_id')
		);
	}

	public static function getLoggedInOnlineUsers()
	{
		$query = 'SELECT
						fk_user_id AS id,
						users.username
					FROM(
						SELECT		DISTINCT fk_user_id, session_id
						FROM		user_online
						WHERE		fk_user_id>0
						GROUP BY	session_id
					) AS tbl_result
					JOIN users ON (users.id = tbl_result.fk_user_id)
					GROUP BY tbl_result.fk_user_id';

		return MySql::select($query);
	}


	// get Rows
	public static function getAllUsers()
	{
		return MySql::select('SELECT * FROM '.self::$tbl_users);
	}
	public static function getAllUserGroups($order = array())
	{
		return MySql::select('SELECT * FROM '.self::$tbl_user_groups);
	}
	public static function getAllFailedLogins($order = array())
	{
		return MySql::select('SELECT * FROM '.self::$tbl_failed_logins);
	}


	public static function update($fields = array())
	{
		if ( isset($fields['modified']) )
			$fields['modified'] = date("Y-m-d H:i:s", time());
		if ( isset($fields['password']) )
			$fields['password'] = self::_encryptPassword($fields['password']);

		return MySql::updateRow(self::$tbl_users, $fields, self::id());
	}


	public static function isMyPassword($clearTextPwd)
	{
		$encrypted	= self::_encryptPassword($clearTextPwd);
		$data		= self::data();

		return ($encrypted == $data['password']);
	}

	public static function data()
	{
		$query	= sprintf('SELECT * FROM `%s` WHERE `id` = %d', self::$tbl_users, self::id());
		$data	= Mysql::select($query);
		return $data[0];
	}

	public static function id()
	{
		return self::isLoggedIn();
	}

	public static function name()
	{
		return MySql::fetchFieldById(self::$tbl_users, 'username', self::_isLoggedIn());
	}

	public static function getNameById($id)
	{
		return MySql::fetchFieldById(self::$tbl_users, 'username', $id);
	}
	public static function getIdByNameAndPassword($username, $password)
	{
		return MySql::fetchField(self::$tbl_users, 'id', sprintf("`username` = '%s' AND `password` = '%s'",
			mysql_real_escape_string($username),
			self::_encryptPassword($password))
		);
	}

	public static function getEnabledUser()
	{
		$user_id = self::isLoggedIn();
		return self::_getEnabledUser($user_id);
	}

	public static function isLoggedIn()
	{
		return self::_isLoggedIn();
	}

	public static function isAdmin()
	{
		$user_id = self::_isLoggedIn();

		return self::_isAdmin($user_id);
	}

	public static function logout()
	{
		Session::destroy();
	}

	public static function getIdByName($username)
	{
		return MySql::fetchField(self::$tbl_users, 'id', sprintf("`username`= '%s'", mysql_real_escape_string($username)));
	}

	public static function usernameExists($username)
	{
		return MySql::count(self::$tbl_users, sprintf("username = '%s'", mysql_real_escape_string($username)));
	}
	public static function emailExists($email)
	{
		return MySql::count(self::$tbl_users, sprintf("email = '%s'", mysql_real_escape_string($email)));
	}
	public static function otherUserHasThisEmail($email)
	{
		return MySql::count(self::$tbl_users, sprintf("`email` = '%s' AND `id` <> %d", mysql_real_escape_string($email), self::id()));
	}
	public static function exists($user_id)
	{
		return MySql::count(self::$tbl_users, sprintf("`id` = %d", mysql_real_escape_string($user_id)));
	}
	public static function isLocked($user_id)
	{
		return MySql::count(self::$tbl_users, sprintf("`id` = %d AND is_locked = 1", mysql_real_escape_string($user_id)));
	}
	public static function isEnabled($user_id)
	{
		return MySql::count(self::$tbl_users, sprintf("`id` = %d AND is_enabled = 1", mysql_real_escape_string($user_id)));
	}
	public static function isDeleted($user_id)
	{
		return MySql::count(self::$tbl_users, sprintf("`id` = %d AND is_deleted = 1", mysql_real_escape_string($user_id)));
	}

	public static function checkLogin($username, $password, $log_bad_attempts = true)
	{
		$password	= self::_encryptPassword($password);
		$user_id	= self::_getUserIdByName($username);
		$user		= self::_getEnabledUser($user_id);

		$condition	= sprintf(
			"username = '%s' AND password = '%s' AND is_enabled = 1 AND is_deleted = 0 AND is_locked = 0",
			mysql_real_escape_string($username),
			$password
		);

		// can login
		if ( MySql::count(self::$tbl_users, $condition) )
		{
			return true;
		}

		// count failed login attempt per user
		MySql::incrementField(self::$tbl_users, 'last_failed_login_count', sprintf("id = %d", (int)$user_id));

		// log failed login attempts
		if ( $log_bad_attempts )
		{
			self::_logFailedLogin($username, $password);
		}

		return false;
	}

	public static function validate($validation_key)
	{
		$condition	= sprintf("validation_key = '%s'", mysql_real_escape_string($validation_key));
		$user_id	= MySql::fetchField(self::$tbl_users, 'id', $condition);

		if ( $user_id )
		{
			return MySql::updateRow(self::$tbl_users, array('is_enabled' => 1, 'validation_key' => ''), $user_id);
		}
		else
		{
			return false;
		}

	}

	public static function login($username, $password, $log_bad_attempts = true)
	{
		$user_id	= self::_getUserIdByName($username);

		$condition	= sprintf("username = '%s' AND password = '%s' AND is_enabled = 1 AND is_deleted = 0 AND is_locked = 0",
			mysql_real_escape_string($username),
			$password
		);

		// can login
		if ( self::checkLogin($username, $password, $log_bad_attempts) )
		{
			$user = self::_getEnabledUser($user_id);

			// update login session, time and ip
			self::_updateSuccessfulLogin($user_id);

			// Set user session
			unset($user['password']);
			$user['auth'] = TRUE;
			Session::set('user', $user);

			return true;
		}
		return false;
	}



	public static function addUser($username, $password, $email)
	{
		$data = array(
				'username'			=> $username,
				'password'			=> self::_encryptPassword($password),
				'email'				=> $email,
				'has_accepted_terms'=> 1,
				'is_enabled'		=> 0,		// needs to validate with validation_key
				'is_locked'			=> 0,
				'is_deleted'		=> 0,
				'validation_key'	=> md5(Session::getId().$username.$password.$email.time()),
				'created'			=> date("Y-m-d H:i:s", time()),
		);
		return MySql::insertRow('users', $data);
	}


	/******************************************************** private functions ********************************************************/



	private static function _encryptPassword($password)
	{
		return md5(md5($password).$GLOBALS['MY_PWD_SALT']);
	}


	private static function _isLoggedIn()
	{
		$user		= Session::get('user');
		$user_id	= isset($user['id']) ? $user['id'] : 0;
		$sess_id	= Session::getId();


		// validate php session
		if ( !$user['auth'] )
		{
			return FALSE;
		}

		// check php session against stored session in mysql
		if ( $sess_id != MySql::fetchFieldById(self::$tbl_users, 'session_id', $user_id) )
		{
			return FALSE;
		}

		return $user_id;
	}

	private static function _isAdmin($user_id)
	{
		return MySql::fetchFieldById(self::$tbl_users, 'is_admin', $user_id);
	}


	private static function _getEnabledUser($user_id)
	{
		$query	= sprintf('SELECT * FROM '.self::$tbl_users.' WHERE id = %d AND is_deleted = 0 AND is_locked = 0 AND is_enabled = 1', $user_id);
		$data 	= MySql::select($query);
		return isset($data[0]) ? $data[0] : array();
	}
	private static function _getUserIdByName($username)
	{
		return MySQL::fetchField(self::$tbl_users, 'id', sprintf("username = '%s'", $username));
	}

	private static function _updateSuccessfulLogin($user_id)
	{
		$session_id = Session::getId();
		$ip			= $_SERVER['REMOTE_ADDR'];
		$hostname	= $hostname	= @gethostbyaddr($ip);
		$login_time	= date("Y-m-d H:i:s", time());

		$fields = array(
			'session_id'				=> $session_id,
			'last_ip'					=> $ip,
			'last_host'					=> $hostname,
			'last_login'				=> $login_time,
			'last_failed_login_count'	=> 0,
		);

		return MySQL::updateRow(self::$tbl_users, $fields, $user_id);
	}

	private static function _logFailedLogin($username, $password)
	{
		$session_id = Session::getId();
		$ip			= $_SERVER['REMOTE_ADDR'];
		$hostname	= $hostname	= @gethostbyaddr($ip);
		$login_time	= date("Y-m-d H:i:s", time());

		$fields = array(
			'username'		=> $username,
			'password'		=> self::_encryptPassword($password),
			'referer'		=> isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
			'useragent'		=> $_SERVER['HTTP_USER_AGENT'],
			'session_id'	=> $session_id,
			'ip'			=> $ip,
			'hostname'		=> $hostname,
			'created'		=> date("Y-m-d H:i:s", time()),
		);
		return MySql::insertRow(self::$tbl_failed_logins, $fields);
	}
}

?>