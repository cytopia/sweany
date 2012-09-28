<?php
/**
 * Sweany MVC PHP framework
 * Copyright (C) 2011-2012 Patu.
 *
 * Sweany is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Sweany is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Sweany. If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright	Copyright 2011-2012, Patu
 * @link		none yet
 * @package		sweany.core.init
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.7 2012-08-08 11:13
 *
 *
 * This core module will provide an interface to handle users.
 *
 * TODO: most of the stuff should go into the session which updates on ins/upd/del
 *		 so we can safe some db operations
 *
 * FIXME: see above
 */
namespace Sweany;

class Users extends aBootTemplate
{

	/**************************************  V A R I A B L E S  **************************************/

	private static $tbl_users			= 'users';
	private static $tbl_user_groups		= 'user_groups';
	private static $tbl_failed_logins	= 'failed_logins';
	private static $tbl_online_users	= 'user_online';

	private static $onlineSinceMinutes;	// count online users from last XX Minutes till now
	private static $fakeOnlineGuests;	// set the amount of fake online guests


	/*
	 * How many rounds to loop through the hashing
	 * in order to produce key stretching-times to make
	 * it harder for bruteforce attacks
	 */
	private static $hashRounds			= 20;

	/**
	 *	@param object	$db	Database Object
	 */
	private static $db					= null;

	/**************************************  C O N S T R U C T O R S  **************************************/

	/**
	 * @Deprecated
	 */
	public function __construct()
	{
	}

	
	public static function initialize($options = null)
	{
		self::$db		= \Sweany\Database::getInstance();

		if ( $GLOBALS['USER_ONLINE_COUNT_ENABLE'] )
		{
			self::$onlineSinceMinutes	= $GLOBALS['USER_ONLINE_SINCE_MINUTES'];
			self::$fakeOnlineGuests		= $GLOBALS['USER_ONLINE_ADD_FAKE_GUESTS'];

			// Add current user to online users table
			self::$db->insert(self::$tbl_online_users, array('time' => time(), 'fk_user_id' => self::id(), 'session_id' => \Sweany\Session::getId(), 'ip' => $_SERVER['REMOTE_ADDR'], 'current_page' => \Sweany\Url::$request), false);

			// Delete all entries since last XX minutes
			self::$db->delete(self::$tbl_online_users, sprintf('`time` < %d', strtotime('-'.self::$onlineSinceMinutes.' minute', time())));
		}
		// TODO: check for valid initialization
		return true;
	}




	/************************************** ONLINE USERS **************************************/

	/**
	 * Count all current active users
	 *
	 * @return integer
	 */
	public static function countOnlineUsers()
	{
		return (self::$fakeOnlineGuests + self::$db->countDistinct(self::$tbl_online_users, 'session_id', null));
	}

	/**
	 *
	 * @return integer
	 */
	public static function countLoggedInOnlineUsers()
	{
		$query = 'SELECT
					COUNT(*) AS counter
				FROM(
					SELECT		DISTINCT fk_user_id, session_id
					FROM		user_online
					WHERE		fk_user_id>0
					GROUP BY	session_id
				) AS tbl_result
				GROUP BY
					tbl_result.fk_user_id;';
		$result	= self::$db->select($query);
		return isset($result[0]['counter']) ? $result[0]['counter'] : 0;
	}

	/**
	 * @param	boolean 	$include_faked_online_guests	Whether or not to also add the faked online guests
	 * @return	integer		total
	 */
	public static function countAnonymousOnlineUsers($include_faked_online_guests = true)
	{
		$fake	= ($include_faked_online_guests) ? self::$fakeOnlineGuests : 0;
		$query	= 'SELECT
						COUNT (DISTINCT session_id) AS counter
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
						session_id;';

		$result	= self::$db->select($query);
		$count	= isset($result[0]['counter']) ? $result[0]['counter'] : 0;
		return ($fake + $count);
	}


	/**
	 *
	 * @return array()
	 */
	public static function getLoggedInOnlineUsers()
	{
		$query	= 'SELECT
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

		return self::$db->select($query);
	}





	/**************************************  G E T   I D   **************************************/

	/**
	 *
	 * @return integer
	 */
	public static function id()
	{
		$user	= \Sweany\Session::get('user');
		return (int) ( isset($user['id']) && is_numeric($user['id']) && $user['id'] > 0 ) ? $user['id'] : 0;
	}

	/**
	 * @param string $username
	 * @param string $password (cleartext)
	 * @return integer
	 */
	public static function getIdByNameAndPassword($username, $password)
	{
		$user_id	= self::getIdByName($username);
		$salt		= self::_getPasswordSalt($user_id);
		$condition	= array(
				'`username` = :username AND `password` = :password',
				array(
					':username' => $username,
					':password' => self::_encryptPassword($password, $salt)
				)
		);
	
		return self::$db->fetchField(self::$tbl_users, 'id', $condition);
	}

	/**
	 * @param string $username
	 * @return integer
	 */
	public static function getIdByName($username)
	{
		$condition	= array('`username` = :username', array(':username' => $username));
		return self::$db->fetchField(self::$tbl_users, 'id', $condition);
	}

	/**
	 * @param string $email
	 * @return integer
	 */
	public static function getIdByEmail($email)
	{
		$condition	= array('`email` = :email', array(':email' => $email));
		return self::$db->fetchField(self::$tbl_users, 'id', $condition);
	}

	/**
	 * @param string $reset_password_key
	 * @return integer
	 */
	public static function getIdByResetPasswordKey($reset_password_key)
	{
		$condition	= array('`reset_password_key` = :key', array(':key' => $reset_password_key));
		return self::$db->fetchField(self::$tbl_users, 'id', $condition);
	}




	/**************************************  C U R R E N T   U S E R   **************************************/

	/**
	 * Returns the info whether or not the current user session is logged in.
	 *
	 * @param integer $paranoid Also check php session against session stored in database
	 * 							Downside is that a single user can only be logged in once.
	 * @return boolean
	 */
	public static function isLoggedIn($paranoid = false)
	{
		$user		= \Sweany\Session::get('user');

		// validate php session
		if ( !(isset($user['auth']) && $user['auth']) )
		{
			return false;
		}
		else
		{
			if ( $paranoid )
			{
				$user_id	= self::id();
				$sess_id	= \Sweany\Session::getId();

				// check php session against stored session in mysql
				if ( $sess_id != self::$db->fetchRowField(self::$tbl_users, 'session_id', $user_id) )
				{
					return false;
				}
				else
				{
					return true;
				}
			}
			else
			{
				return true;
			}
		}
	}


	/**
	 *
	 * @param string @clearTextPwd
	 * @return boolean
	 */
	public static function isMyPassword($clearTextPwd)
	{
		$user_id	= self::id();
		$salt		= self::_getPasswordSalt($user_id);
		$encrypted	= self::_encryptPassword($clearTextPwd, $salt);

		return ( $encrypted == self::$db->fetchRowField(self::$tbl_users, 'password', $user_id) );
	}




	/************************************** GET USER INFO **************************************/

	/**
	 * Returns the username of the current logged in user,
	 * or alternatively the one specified by the corresponding id.
	 *
	 * @param  integer $id (optional)
	 * @return string
	 */
	public static function name($id = null)
	{
		$id = (is_null($id)) ? self::id() : (int)$id;
		return self::$db->fetchRowField(self::$tbl_users, 'username', $id);
	}


	/**
	 * Returns the email address of the current logged in user,
	 * or alternatively the one specified by the corresponding id.
	 *
	 * @param  integer $id (optional)
	 * @return string
	 */
	public static function email($id = null)
	{
		$id = (is_null($id)) ? self::id() : (int)$id;
		return self::$db->fetchRowField(self::$tbl_users, 'email', $id);
	}


	/**
	 * Returns the data array of the current logged in user,
	 * or alternatively the one specified by the corresponding id.
	 *
	 * @param integer $id (optional)
	 * @return array()
	 */
	public static function data($id = null)
	{
		$id		= (is_null($id)) ? self::id() : (int)$id;
		$query	= sprintf('SELECT * FROM `%s` WHERE `id` = %d', self::$tbl_users, $id);
		$data	= self::$db->select($query);
		return (isset($data[0])) ? $data[0] : array();
	}


	/**
	 *
	 * @param integer $id (optional)
	 * @return boolean
	 */
	public static function isAdmin($id = null)
	{
		$id		= (is_null($id)) ? self::id() : (int)$id;
		return self::$db->fetchRowField(self::$tbl_users, 'is_admin', $id);
	}


	/**
	 *
	 * @param interger $id (optional)
	 * @return boolean
	 */
	public static function isEnabled($id = null)
	{
		$id = (is_null($id)) ? self::id() : (int)$id;
		return self::$db->count(self::$tbl_users, sprintf("`id` = %d AND is_enabled = 1", self::$db->escape($id)));
	}


	/**
	 *
	 * @param interger $id (optional)
	 * @return boolean
	 */
	public static function isLocked($id = null)
	{
		$id = (is_null($id)) ? self::id() : (int)$id;
		return self::$db->count(self::$tbl_users, sprintf("`id` = %d AND is_locked = 1", self::$db->escape($id)));
	}


	/**
	 *
	 * @param interger $id (optional)
	 * @return boolean
	 */
	public static function isDeleted($id = null)
	{
		$id = (is_null($id)) ? self::id() : (int)$id;
		return self::$db->count(self::$tbl_users, sprintf("`id` = %d AND is_deleted = 1", self::$db->escape($id)));
	}




	/************************************** E X I S T S   I N F O **************************************/

	/**
	 *
	 * @param integer $user_id
	 * @param boolean
	 */
	public static function exists($user_id)
	{
		return self::$db->rowExists(self::$tbl_users, $user_id);
	}

	/**
	 *
	 * @param string $username
	 * @return boolean
	 */
	public static function usernameExists($username)
	{
		return self::$db->count(self::$tbl_users, array('`username` = :username', array(':username' => $username)));
	}

	/**
	 *
	 * @param string $email
	 * @return boolean
	 */
	public static function emailExists($email)
	{
		return self::$db->count(self::$tbl_users, array('`email` = :email', array(':email' => $email)));
	}


	/**
	 * Check if the specified email already exists for another
	 * user other than the current logged in user.
	 * (Usefull if you want to edit the email of the current logged in user)
	 *
	 * @param	string	$email
	 * @return	bool
	 */
	public static function otherUserHasThisEmail($email)
	{
		$condition = array('`email` = :email AND `id` <> :id', array(':email' => $email, ':id' => self::id()));
		return self::$db->count(self::$tbl_users, $condition);
	}




	/************************************** GET MANY USERS **************************************/

	/**
	 * @return array()
	 */
	public static function getAllUsers()
	{
		return self::$db->select('SELECT * FROM '.self::$tbl_users);
	}

	/**
	 *
	 * Enter description here ...
	 * @param	integer	$total	Number to return
	 * @return	mixed[]
	 */
	public static function getLatestUsers($total)
	{
		return self::$db->select(sprintf('SELECT * FROM `'.self::$tbl_users.'` WHERE `is_enabled` = 1 AND `is_locked` = 0 AND `is_deleted` = 0 ORDER BY `created` DESC LIMIT %d', (int)$total));
	}

	/**
	 * @return array()
	 */
	public static function getAllUserGroups($order = array())
	{
		return self::$db->select('SELECT * FROM '.self::$tbl_user_groups);
	}

	/**
	 * @return array()
	 */
	public static function getAllFailedLogins($order = array())
	{
		return self::$db->select('SELECT * FROM '.self::$tbl_failed_logins);
	}




	/************************************** ACTIONS  **************************************/

	public static function login($username, $password, $log_bad_attempts = true)
	{
		$user_id	= self::getIdByName($username);

		// can login
		if ( self::checkLogin($username, $password, $log_bad_attempts) )
		{
			$user = self::data($user_id);

			// update login session, time and ip
			self::_updateSuccessfulLogin($user_id);

			// Set user session
			unset($user['password']);
			unset($user['password_salt']);
			unset($user['has_accepted_terms']);
			unset($user['validation_key']);
			unset($user['reset_password_key']);
			$user['auth'] = TRUE;
			\Sweany\Session::set('user', $user);

			return true;
		}
		return false;
	}


	public static function checkLogin($username, $clearTextPwd, $log_bad_attempts = true)
	{
		$user_id	= self::getIdByName($username);
		$salt		= self::_getPasswordSalt($user_id);
		$password	= self::_encryptPassword($clearTextPwd, $salt);

		$condition	= array("`username` = :username AND `password` = :password AND `is_enabled` = 1 AND `is_deleted` = 0 AND `is_locked` = 0",
			array(':username' => $username, ':password' => $password));

		// can login
		if ( self::$db->count(self::$tbl_users, $condition) )
		{
			return true;
		}

		// count failed login attempt per user
		self::$db->incrementFields(self::$tbl_users, array('last_failed_login_count'), null, sprintf('`id` = %d', (int)$user_id));

		// log failed login attempts
		if ( $log_bad_attempts )
		{
			self::_logFailedLogin($username, $clearTextPwd);
		}

		return false;
	}

	public static function logout($session_id)
	{
		if ( $session_id == \Sweany\Session::getId() )
		{
			\Sweany\Session::del('user');
			return true;
		}
		return false;
	}




	/************************************** ADD/UPDATE  **************************************/

	public static function addUser($username, $password, $email)
	{
		$salt	= self::_createPasswordSalt();
		$data	= array('username'			=> $username,
						'password'			=> self::_encryptPassword($password, $salt),
						'password_salt'		=> $salt,
						'email'				=> $email,
						'has_accepted_terms'=> 1,
						'is_enabled'		=> 0,		// needs to validate with validation_key
						'is_locked'			=> 0,
						'is_deleted'		=> 0,
						'validation_key'	=> md5(\Sweany\Session::getId().$username.$password.$email.time()),
						'created'			=> self::$db->getNowUnixTimeStamp(),
		);
		return self::$db->insert('users', $data, true);
	}

	/**
	 * Update current logged in user (by its id)
	 *
	 * @param array() $fields
	 */
	public static function update($fields = array(), $id = null)
	{
		$id = (is_null($id)) ? self::id() : (int)$id;

		if ( isset($fields['modified']) )
			$fields['modified'] = self::$db->getNowUnixTimeStamp();
		if ( isset($fields['password']) )
			$fields['password'] = self::_encryptPassword($fields['password'], self::_getPasswordSalt($id));

		return self::$db->updateRow(self::$tbl_users, $fields, $id);
	}

	/**
	 *
	 * @param integer $password (cleartext)
	 */
	public static function updatePassword($password, $id = null)
	{
		$id = (is_null($id)) ? self::id() : (int)$id;

		$fields['password'] = self::_encryptPassword($password, self::_getPasswordSalt($id));

		return self::$db->updateRow(self::$tbl_users, $fields, $id);
	}

	/**
	 * Validate a registered user by the validation key.
	 * This key should have been sent to him by email
	 * and makes the final step for registration
	 *
	 * @param string $validation_key
	 */
	public static function validate($validation_key)
	{
		$condition	= array('validation_key = :key', array(':key' => $validation_key));
		$user_id	= self::$db->fetchField(self::$tbl_users, 'id', $condition);

		return ($user_id) ? self::$db->updateRow(self::$tbl_users, array('is_enabled' => 1, 'validation_key' => ''), $user_id) : false;
	}

	/**
	 *
	 * Enter description here ...
	 * @param	integer $user_id
	 * @return	string	password reset key
	 */
	public static function setResetPasswordKey($user_id)
	{
		// Generate random key
		$session_id = \Session::getId();
		$ip			= $_SERVER['REMOTE_ADDR'];
		$hostname	= @gethostbyaddr($ip);
		$key		= md5(mt_rand().time().$session_id.$ip.$hostname.mt_rand().$user_id);

		self::$db->updateRow(self::$tbl_users, array('reset_password_key' => $key), $user_id);
		return $key;
	}

	/**
	 *
	 * @param string $password_reset_key
	 * @return boolean
	 */
	public static function checkPasswordResetKey($password_reset_key)
	{
		$condition	= array('`reset_password_key` = :key AND LENGTH(reset_password_key)>0', array(':key' => $password_reset_key));
		$user_id	= $db->fetchField(self::$tbl_users, 'id', $condition);

		return ($user_id) ? self::$db->updateRow(self::$tbl_users, array('is_enabled' => 1, 'validation_key' => ''), $user_id) : false;
	}

	/**
	 *
	 * @param integer $user_id
	 */
	public static function removeResetPasswordKey($user_id)
	{
		return self::$db->updateRow(self::$tbl_users, array('reset_password_key' => ''), $user_id);
	}

	/******************************************************** count functions ********************************************************/

	public static function countFakeUsers()
	{
		return self::$db->count(self::$tbl_users, '`is_fake` = 1');
	}
	public static function countRealUsers()
	{
		return self::$db->count(self::$tbl_users, '`is_fake` = 0');
	}




	/******************************************************** private functions ********************************************************/

	/**
	 *
	 * @param string $password
	 * @return string
	 */
	private static function _encryptPassword($clearTextPassword, $passwordSalt)
	{
		// start with some default value
		$hash = md5($passwordSalt.$clearTextPassword);

		// loop through to produce key stretching-times
		// in case of web bruteforce and hash via sha512 (strongest so far)
		for ($i=0; $i<self::$hashRounds; $i++)
		{
			$hash = hash("sha512",$clearTextPassword.$passwordSalt.$hash);
		}
		return ($hash);
	}

	/**
	 * create a random Password Salt
	 */
	private static function _createPasswordSalt()
	{
		return hash('sha512', md5( mt_rand().microtime(true).mt_rand().$_SERVER['REMOTE_ADDR'].mt_rand() ));
	}

	/**
	 * Get the password salt from a specified user
	 *
	 * @param integer $user_id
	 * @return string
	 */
	private static function _getPasswordSalt($user_id)
	{
		return self::$db->fetchRowField(self::$tbl_users, 'password_salt', $user_id);
	}

	/**
	 *
	 * @param integer $user_id
	 */
	private static function _updateSuccessfulLogin($user_id)
	{
		$session_id = \Sweany\Session::getId();
		$ip			= $_SERVER['REMOTE_ADDR'];
		$hostname	= @gethostbyaddr($ip);
		$login_time	= self::$db->getNowUnixTimeStamp();

		$fields = array(
			'session_id'				=> $session_id,
			'last_ip'					=> $ip,
			'last_host'					=> $hostname,
			'last_login'				=> $login_time,
			'last_failed_login_count'	=> 0,
		);
		return self::$db->updateRow(self::$tbl_users, $fields, $user_id);
	}

	/**
	 *
	 * @param string $username
	 * @param string $password
	 */
	private static function _logFailedLogin($username, $password)
	{
		$session_id = \Sweany\Session::getId();
		$ip			= $_SERVER['REMOTE_ADDR'];
		$hostname	= @gethostbyaddr($ip);
		$login_time	= self::$db->getNowUnixTimeStamp();

		$fields = array(
			'username'		=> $username,
			'password'		=> md5($password),	// only log md5 hashes of failed logins
			'referer'		=> isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
			'useragent'		=> $_SERVER['HTTP_USER_AGENT'],
			'session_id'	=> $session_id,
			'ip'			=> $ip,
			'hostname'		=> $hostname,
			'created'		=> $login_time,
		);
		return self::$db->insert(self::$tbl_failed_logins, $fields);
	}
}