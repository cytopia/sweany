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
 */
namespace Sweany;

class Users extends aBootTemplate
{

	/**************************************  V A R I A B L E S  **************************************/

	private static $tbl_users			= 'users';
	private static $tbl_user_groups		= 'user_groups';
	private static $tbl_failed_logins	= 'user_failed_logins';
	private static $tbl_online_users	= 'user_online';

	private static $onlineSinceMinutes;	// count online users from last XX Minutes till now
	private static $fakeOnlineGuests;	// set the amount of fake online guests


	/*
	 * How many rounds to loop through the hashing
	 * in order to produce key stretching-times to make
	 * it harder for bruteforce attacks
	 */
	private static $hashRounds			= 20;



	/**************************************  C O N S T R U C T O R S  **************************************/

	/**
	 * @Deprecated
	 */
	public function __construct()
	{
	}

	public static function initialize($options = null)
	{
		if ( $GLOBALS['USER_ONLINE_COUNT_ENABLE'] )
		{
			self::$onlineSinceMinutes	= $GLOBALS['USER_ONLINE_SINCE_MINUTES'];
			self::$fakeOnlineGuests		= $GLOBALS['USER_ONLINE_ADD_FAKE_GUESTS'];

			$db = \Sweany\Database::getInstance();

			// Add current user to online users table
			$db->insert(self::$tbl_online_users, array('time' => time(), 'fk_user_id' => self::id(), 'session_id' => \Sweany\Session::getId(), 'ip' => $_SERVER['REMOTE_ADDR'], 'current_page' => \Sweany\Url::$request));

			// Delete all entries since last XX minutes
			$db->delete(self::$tbl_online_users, sprintf('`time` < %d', strtotime('-'.self::$onlineSinceMinutes.' minute', time())));
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
		$db = \Sweany\Database::getInstance();
		return (self::$fakeOnlineGuests + $db->selectNumRows('SELECT DISTINCT `session_id` FROM '.self::$tbl_online_users));
	}

	/**
	 *
	 * @return integer
	 */
	public static function countLoggedInOnlineUsers()
	{
		$db = \Sweany\Database::getInstance();
		return $db->selectNumRows('SELECT
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

	/**
	 * @param	boolean 	$include_faked_online_guests	Whether or not to also add the faked online guests
	 * @return	integer		total
	 */
	public static function countAnonymousOnlineUsers($include_faked_online_guests = true)
	{
		$db = \Sweany\Database::getInstance();
		$plus	= ($include_faked_online_guests) ? self::$fakeOnlineGuests : 0;

		return ($plus +
				$db->selectNumRows('SELECT
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


	/**
	 *
	 * @return array()
	 */
	public static function getLoggedInOnlineUsers()
	{
		$db = \Sweany\Database::getInstance();
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

		return $db->select($query);
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
		$db = \Sweany\Database::getInstance();

		return $db->fetchField(self::$tbl_users, 'id', sprintf("`username` = '%s' AND `password` = '%s'",
				$db->escape($username),
				self::_encryptPassword($password, $salt))
		);
	}

	/**
	 * @param string $username
	 * @return integer
	 */
	public static function getIdByName($username)
	{
		$db = \Sweany\Database::getInstance();
		return $db->fetchField(self::$tbl_users, 'id', sprintf("`username`= '%s'", $db->escape($username)));
	}

	/**
	 * @param string $email
	 * @return integer
	 */
	public static function getIdByEmail($email)
	{
		$db = \Sweany\Database::getInstance();
		return $db->fetchField(self::$tbl_users, 'id', sprintf("`email` = '%s'", $db->escape($email)));
	}

	/**
	 * @param string $reset_password_key
	 * @return integer
	 */
	public static function getIdByResetPasswordKey($reset_password_key)
	{
		$db = \Sweany\Database::getInstance();
		return $db->fetchField(self::$tbl_users, 'id', sprintf("`reset_password_key` = '%s'", $db->escape($reset_password_key)));
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
				$db			= \Sweany\Database::getInstance();

				// check php session against stored session in mysql
				if ( $sess_id != $db->fetchRowField(self::$tbl_users, 'session_id', $user_id) )
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
		$db			= \Sweany\Database::getInstance();
		$encrypted	= self::_encryptPassword($clearTextPwd, $salt);

		return ( $encrypted == $db->fetchRowField(self::$tbl_users, 'password', $user_id) );
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
		$db = \Sweany\Database::getInstance();

		return $db->fetchRowField(self::$tbl_users, 'username', $id);
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
		$db = \Sweany\Database::getInstance();

		return $db->fetchRowField(self::$tbl_users, 'email', $id);
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
		$db		= \Sweany\Database::getInstance();
		$query	= sprintf('SELECT * FROM `%s` WHERE `id` = %d', self::$tbl_users, $id);
		$data	= $db->select($query);

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
		$db 	= \Sweany\Database::getInstance();
		return $db->fetchRowField(self::$tbl_users, 'is_admin', $id);
	}


	/**
	 *
	 * @param interger $id (optional)
	 * @return boolean
	 */
	public static function isEnabled($id = null)
	{
		$id = (is_null($id)) ? self::id() : (int)$id;
		$db = \Sweany\Database::getInstance();
		return $db->count(self::$tbl_users, sprintf("`id` = %d AND is_enabled = 1", $db->escape($id)));
	}


	/**
	 *
	 * @param interger $id (optional)
	 * @return boolean
	 */
	public static function isLocked($id = null)
	{
		$id = (is_null($id)) ? self::id() : (int)$id;
		$db = \Sweany\Database::getInstance();
		return $db->count(self::$tbl_users, sprintf("`id` = %d AND is_locked = 1", $db->escape($id)));
	}


	/**
	 *
	 * @param interger $id (optional)
	 * @return boolean
	 */
	public static function isDeleted($id = null)
	{
		$id = (is_null($id)) ? self::id() : (int)$id;
		$db = \Sweany\Database::getInstance();
		return $db->count(self::$tbl_users, sprintf("`id` = %d AND is_deleted = 1", $db->escape($id)));
	}




	/************************************** E X I S T S   I N F O **************************************/

	/**
	 *
	 * @param integer $user_id
	 * @param boolean
	 */
	public static function exists($user_id)
	{
		$db = \Sweany\Database::getInstance();
		return $db->count(self::$tbl_users, sprintf("`id` = %d", $db->escape($user_id)));
	}

	/**
	 *
	 * @param string $username
	 * @return boolean
	 */
	public static function usernameExists($username)
	{
		$db = \Sweany\Database::getInstance();
		return $db->count(self::$tbl_users, sprintf("username = '%s'", $db->escape($username)));
	}

	/**
	 *
	 * @param string $email
	 * @return boolean
	 */
	public static function emailExists($email)
	{
		$db = \Sweany\Database::getInstance();
		return $db->count(self::$tbl_users, sprintf("email = '%s'", $db->escape($email)));
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
		$db = \Sweany\Database::getInstance();
		return $db->count(self::$tbl_users, sprintf("`email` = '%s' AND `id` <> %d", $db->escape($email), self::id()));
	}




	/************************************** GET MANY USERS **************************************/

	/**
	 * @return array()
	 */
	public static function getAllUsers()
	{
		$db = \Sweany\Database::getInstance();
		return $db->select('SELECT * FROM '.self::$tbl_users);
	}

	/**
	 *
	 * Enter description here ...
	 * @param	integer	$total	Number to return
	 * @return	mixed[]
	 */
	public static function getLatestUsers($total)
	{
		$db = \Sweany\Database::getInstance();
		return $db->select(sprintf('SELECT * FROM '.self::$tbl_users.' WHERE `is_enabled` = 1 AND `is_locked` = 0 AND `is_deleted` = 0 ORDER BY `created` DESC LIMIT %d', $total));
	}

	/**
	 * @return array()
	 */
	public static function getAllUserGroups($order = array())
	{
		$db = \Sweany\Database::getInstance();
		return $db->select('SELECT * FROM '.self::$tbl_user_groups);
	}

	/**
	 * @return array()
	 */
	public static function getAllFailedLogins($order = array())
	{
		$db = \Sweany\Database::getInstance();
		return $db->select('SELECT * FROM '.self::$tbl_failed_logins);
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
		$db 		= \Sweany\Database::getInstance();

		$condition	= sprintf(
				"username = '%s' AND password = '%s' AND is_enabled = 1 AND is_deleted = 0 AND is_locked = 0",
				$db->escape($username),
				$password
		);

		// can login
		if ( $db->count(self::$tbl_users, $condition) )
		{
			return true;
		}

		// count failed login attempt per user
		$db->incrementField(self::$tbl_users, 'last_failed_login_count', sprintf("id = %d", (int)$user_id));

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
		$db 	= \Sweany\Database::getInstance();
		$data	= array('username'			=> $username,
						'password'			=> self::_encryptPassword($password, $salt),
						'password_salt'		=> $salt,
						'email'				=> $email,
						'has_accepted_terms'=> 1,
						'is_enabled'		=> 0,		// needs to validate with validation_key
						'is_locked'			=> 0,
						'is_deleted'		=> 0,
						'validation_key'	=> md5(\Sweany\Session::getId().$username.$password.$email.time()),
						'created'			=> date('Y-m-d H:i:s', time()),
		);
		return $db->insert('users', $data);
	}

	/**
	 * Update current logged in user (by its id)
	 *
	 * @param array() $fields
	 */
	public static function update($fields = array(), $id = null)
	{
		$id = (is_null($id)) ? self::id() : (int)$id;
		$db = \Sweany\Database::getInstance();

		if ( isset($fields['modified']) )
			$fields['modified'] = date('Y-m-d H:i:s', time());
		if ( isset($fields['password']) )
			$fields['password'] = self::_encryptPassword($fields['password'], self::_getPasswordSalt($id));

		return $db->updateRow(self::$tbl_users, $fields, $id);
	}

	/**
	 *
	 * @param integer $password (cleartext)
	 */
	public static function updatePassword($password, $id = null)
	{
		$id = (is_null($id)) ? self::id() : (int)$id;

		$fields['password'] = self::_encryptPassword($password, self::_getPasswordSalt($id));
		$db = \Sweany\Database::getInstance();

		return $db->updateRow(self::$tbl_users, $fields, $id);
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
		$db 		= \Sweany\Database::getInstance();
		$condition	= sprintf("validation_key = '%s'", $db::escape($validation_key));
		$user_id	= $db->fetchField(self::$tbl_users, 'id', $condition);

		if ( $user_id )
		{
			return $db->updateRow(self::$tbl_users, array('is_enabled' => 1, 'validation_key' => ''), $user_id);
		}
		else
		{
			return false;
		}
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
		$db 		= \Sweany\Database::getInstance();

		$db->updateRow(self::$tbl_users, array('reset_password_key' => $key), $user_id);
		return $key;
	}

	/**
	 *
	 * @param string $password_reset_key
	 * @return boolean
	 */
	public static function checkPasswordResetKey($password_reset_key)
	{
		$db 		= \Sweany\Database::getInstance();
		$condition	= sprintf("reset_password_key = '%s' AND LENGTH(reset_password_key)>0", $db->escape($password_reset_key));
		$user_id	= $db->fetchField(self::$tbl_users, 'id', $condition);

		if ( $user_id )
		{
			return $db->updateRow(self::$tbl_users, array('is_enabled' => 1, 'validation_key' => ''), $user_id);
		}
		else
		{
			return false;
		}
	}

	/**
	 *
	 * @param integer $user_id
	 */
	public static function removeResetPasswordKey($user_id)
	{
		$db = \Sweany\Database::getInstance();
		return $db->updateRow(self::$tbl_users, array('reset_password_key' => ''), $user_id);
	}

	/******************************************************** count functions ********************************************************/

	public static function countFakeUsers()
	{
		$db = \Sweany\Database::getInstance();
		return $db->count(self::$tbl_users, '`is_fake` = 1');
	}
	public static function countRealUsers()
	{
		$db = \Sweany\Database::getInstance();
		return $db->count(self::$tbl_users, '`is_fake` = 0');
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
		$db = \Sweany\Database::getInstance();
		return $db->fetchRowField(self::$tbl_users, 'password_salt', $user_id);
	}

	/**
	 *
	 * @param integer $user_id
	 */
	private static function _updateSuccessfulLogin($user_id)
	{
		$db 		= \Sweany\Database::getInstance();
		$session_id = \Sweany\Session::getId();
		$ip			= $_SERVER['REMOTE_ADDR'];
		$hostname	= @gethostbyaddr($ip);
		$login_time	= date('Y-m-d H:i:s', time());

		$fields = array(
			'session_id'				=> $session_id,
			'last_ip'					=> $ip,
			'last_host'					=> $hostname,
			'last_login'				=> $login_time,
			'last_failed_login_count'	=> 0,
		);
		return $db->updateRow(self::$tbl_users, $fields, $user_id);
	}

	/**
	 *
	 * @param string $username
	 * @param string $password
	 */
	private static function _logFailedLogin($username, $password)
	{
		$db 		= \Sweany\Database::getInstance();
		$session_id = \Sweany\Session::getId();
		$ip			= $_SERVER['REMOTE_ADDR'];
		$hostname	= @gethostbyaddr($ip);
		$login_time	= date("Y-m-d H:i:s", time());

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
		return $db->insert(self::$tbl_failed_logins, $fields);
	}
}