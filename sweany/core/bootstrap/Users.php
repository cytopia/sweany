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
 */
namespace Sweany;

class Users extends aBootTemplate
{

	/**************************************  V A R I A B L E S  **************************************/


	/**
	 * @param	array	Holds user session
	 */
	private static $data = null;

	/**
	 *	@param object	$db	Database Object
	 */
	private static $db = null;

	/**************************************  C O N S T R U C T O R  **************************************/


	public static function initialize($options = null)
	{
		self::$data		= \Sweany\Session::get(Settings::sessSweany, Settings::sessUser);
		self::$data		= self::$data ? self::$data : array();

		self::$db		= \Sweany\Database::getInstance();

		return true;
	}





	/***************************************************************************************************************
	 *
	 *	C U R R E N T   U S E R
	 *
	 ***************************************************************************************************************/

	/**
	 *
	 * @return integer
	 */
	public static function id()
	{
		return isset(self::$data['id']) ? self::$data['id'] : 0;
	}


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
		if ( isset(self::$data['auth']) && self::$data['auth'] ) {

			if ( $paranoid ) {
				$user_id	= self::id();
				$sess_id	= \Sweany\Session::id();

				// Current php session does not match database session... No Sir!
				// Note:
				// -------------------------
				// If a user logs in on a different computer/different browser
				// he/she will get another session_id
				// and by this check will not be logged in anymore on the first
				// computer/browser
				if ( $sess_id != self::$db->fetchRowField(Settings::tblUsers, 'session_id', $user_id) ) {
					return false;
				}
				// Session in Database matches. Very sure he/she is logged in
				else {
					return true;
				}
			}
			// Session Data exists... user is logged in
			else {
				return true;
			}
		}
		// No session data exists... user is not logged in
		else {
			return false;
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

		return ( $encrypted == self::$db->fetchRowField(Settings::tblUsers, 'password', $user_id) );
	}




	/***************************************************************************************************************
	 *
	 *	C U R R E N T   U S E R   O R   O T H E R   U S E R
	 *
	 ***************************************************************************************************************/

	/**
	 * Returns the username of the current logged in user,
	 * or alternatively the one specified by the corresponding id.
	 *
	 * @param  integer $id (optional)
	 * @return string
	 */
	public static function name($id = null)
	{
		// myself (use session)
		if ($id === null) {
			return isset(self::$data['username']) ? self::$data['username'] : '';
		}
		// someone else (use database)
		else {
			return self::$db->fetchRowField(Settings::tblUsers, 'username', $id);
		}
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
		// myself (use session)
		if ($id === null) {
			return isset(self::$data['email']) ? self::$data['email'] : '';
		}
		// someone else (use database)
		else {
			return self::$db->fetchRowField(Settings::tblUsers, 'email', $id);
		}
	}

	public static function timezone($id = null)
	{
		// myself (use session)
		if ($id === null) {
			return isset(self::$data['timezone']) ? self::$data['timezone'] : Settings::$defaultTimezone;
		}
		// someone else (use database)
		else {
			$timezone = self::$db->fetchRowField(Settings::tblUsers, 'timezone', $id);
			return $timezone ? $timezone : Settings::$defaultTimezone;
		}
	}

	public static function language($id = null)
	{
		// myself (use session)
		if ($id === null) {
			return isset(self::$data['language']) ? self::$data['language'] : Settings::$defaultLanguage;
		}
		// someone else (use database)
		else {
			$language = self::$db->fetchRowField(Settings::tblUsers, 'language', $id);
			return $language ? $language : Settings::$defaultLanguage;
		}
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
		// myself (use session)
		if ($id === null) {
			return (object)self::$data;
		}
		// someone else (use database)
		else {
			$query	= sprintf('SELECT * FROM `%s` WHERE `id` = %d', Settings::tblUsers, $id);
			$data	= self::$db->select($query);
			return (isset($data[0])) ? $data[0] : array();
		}
	}

	/**
	 *
	 * @param integer $id (optional)
	 * @return boolean
	 */
	public static function isAdmin($id = null)
	{
		// myself (use session)
		if ($id === null) {
			return isset(self::$data['is_admin']) ? self::$data['is_admin'] : false;
		}
		// someone else (use database)
		else {
			return self::$db->fetchRowField(Settings::tblUsers, 'is_admin', $id);
		}
	}


	/**
	 *
	 * @param interger $id (optional)
	 * @return boolean
	 */
	public static function isEnabled($id = null)
	{
		// myself (use session)
		if ($id === null) {
			return isset(self::$data['is_enabled']) ? self::$data['is_enabled'] : false;
		}
		// someone else (use database)
		else {
			return self::$db->fetchRowField(Settings::tblUsers, 'is_enabled', $id);
		}
	}


	/**
	 *
	 * @param interger $id (optional)
	 * @return boolean
	 */
	public static function isLocked($id = null)
	{
		// myself (use session)
		if ($id === null) {
			return isset(self::$data['is_locked']) ? self::$data['is_locked'] : false;
		}
		// someone else (use database)
		else {
			return self::$db->fetchRowField(Settings::tblUsers, 'is_locked', $id);
		}
	}


	/**
	 *
	 * @param interger $id (optional)
	 * @return boolean
	 */
	public static function isDeleted($id = null)
	{
		// myself (use session)
		if ($id === null) {
			return isset(self::$data['is_deleted']) ? self::$data['is_deleted'] : false;
		}
		// someone else (use database)
		else {
			return self::$db->fetchRowField(Settings::tblUsers, 'is_deleted', $id);
		}
	}




	/***************************************************************************************************************
	 *
	 *	B O O L E A N   C H E C K E R S
	 *
	 ***************************************************************************************************************/

	/**
	 *
	 * @param integer $user_id
	 * @param boolean
	 */
	public static function exists($user_id)
	{
		return (bool)self::$db->rowExists(Settings::tblUsers, $user_id);
	}

	/**
	 *
	 * @param string $username
	 * @return boolean
	 */
	public static function usernameExists($username)
	{
		return (bool)self::$db->count(Settings::tblUsers, array('`username` = :username', array(':username' => $username)));
	}

	/**
	 *
	 * @param string $email
	 * @return boolean
	 */
	public static function emailExists($email)
	{
		return (bool)self::$db->count(Settings::tblUsers, array('`email` = :email', array(':email' => $email)));
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
		return self::$db->count(Settings::tblUsers, $condition);
	}




	/***************************************************************************************************************
	 *
	 *	G E T   I D   B Y   C O N D I T I O N S
	 *
	 ***************************************************************************************************************/

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

		return self::$db->fetchField(Settings::tblUsers, 'id', $condition);
	}

	/**
	 * @param string $username
	 * @return integer
	 */
	public static function getIdByName($username)
	{
		$condition	= array('`username` = :username', array(':username' => $username));
		return self::$db->fetchField(Settings::tblUsers, 'id', $condition);
	}

	/**
	 * @param string $email
	 * @return integer
	 */
	public static function getIdByEmail($email)
	{
		$condition	= array('`email` = :email', array(':email' => $email));
		return self::$db->fetchField(Settings::tblUsers, 'id', $condition);
	}

	/**
	 * @param string $reset_password_key
	 * @return integer
	 */
	public static function getIdByResetPasswordKey($reset_password_key)
	{
		$condition	= array('`reset_password_key` = :key', array(':key' => $reset_password_key));
		return self::$db->fetchField(Settings::tblUsers, 'id', $condition);
	}




	/***************************************************************************************************************
	 *
	 *	G E T   F U N C T I O N S
	 *
	 ***************************************************************************************************************/

	/**
	 * @return array()
	 */
	public static function getAllUsers()
	{
		return self::$db->select('SELECT * FROM `'.Settings::tblUsers.'` ORDER BY `created` DESC');
	}

	/**
	 *
	 * Enter description here ...
	 * @param	integer	$total	Number to return
	 * @return	mixed[]
	 */
	public static function getLatestUsers($total)
	{
		return self::$db->select(sprintf('SELECT * FROM `'.Settings::tblUsers.'` WHERE `is_enabled` = 1 AND `is_locked` = 0 AND `is_deleted` = 0 ORDER BY `created` DESC LIMIT %d', (int)$total));
	}


	/**
	 * @return array()
	 */
	public static function getAllFailedLogins($order = array())
	{
		return self::$db->select('SELECT * FROM `'.Settings::tblFailedLogins.'` ORDER BY `created` DESC');
	}




	/***************************************************************************************************************
	 *
	 *	C O U N T    F U N C T I O N S
	 *
	 ***************************************************************************************************************/

	public static function countFakeUsers()
	{
		return self::$db->count(Settings::tblUsers, '`is_fake` = 1');
	}
	public static function countRealUsers()
	{
		return self::$db->count(Settings::tblUsers, '`is_fake` = 0');
	}




	/***************************************************************************************************************
	 *
	 *	L O G I N / L O G O U T    F U N C T I O N S
	 *
	 ***************************************************************************************************************/


	public static function login($username, $password, $log_bad_attempts = true)
	{
		$user_id	= self::getIdByName($username);

		// can login
		if ( self::checkLogin($username, $password, $log_bad_attempts) )
		{
			$user = self::data($user_id);

			// update login session, time and ip
			self::_updateSuccessfulLogin($user_id);

			// Remove password stuff (to be prepared for session storing)
			unset($user->password);
			unset($user->password_salt);
			$user->auth = true;

			// Set user data
			$session = array(Settings::sessSweany => Settings::sessUser);
			\Sweany\Session::del(Settings::sessSweany, Settings::sessUser);
			\Sweany\Session::set($session, (array)$user);

			// Set Language Data
			if ( $GLOBALS['LANGUAGE_ENABLE'] && $user->language ) {
				\Sweany\Language::changeLanguage($user->language);
			}

			return true;
		}
		return false;
	}


	public static function checkLogin($username, $clearTextPwd, $log_bad_attempts = true)
	{
		$user_id	= self::getIdByName($username);
		$salt		= self::_getPasswordSalt($user_id);
		$password	= self::_encryptPassword($clearTextPwd, $salt);

		$condition	= array('
			`username` = :username AND
			`password` = :password AND
			`is_enabled` = 1 AND
			`is_deleted` = 0 AND
			`is_locked` = 0',
			array(':username' => $username, ':password' => $password)
		);

		// can login
		if ( self::$db->count(Settings::tblUsers, $condition) ) {
			return true;
		}

		// count failed login attempt per user
		self::$db->incrementFields(Settings::tblUsers, array('last_failed_login_count'), null, sprintf('`id` = %d', (int)$user_id));

		// log failed login attempts
		if ( $log_bad_attempts )
		{
			self::_logFailedLogin($username, $clearTextPwd);
		}
		return false;
	}


	public static function logout($session_id)
	{
		if ( $session_id == \Sweany\Session::id() )
		{
			// cannot destroy the whole session
			// as the redirect messages on log out won't be displayed then
			\Sweany\Session::del(Settings::sessSweany, Settings::sessUser);
			\Sweany\Session::del(Settings::sessSweany, Settings::sessAdmin);
			return true;
		}
		return false;
	}




	/***************************************************************************************************************
	 *
	 *	A D D / U P D A T E    F U N C T I O N S
	 *
	 ***************************************************************************************************************/

	public static function addUser($username, $password, $email)
	{
		$salt	= self::_createPasswordSalt();
		$data	= array(
			'username'			=> $username,
			'password'			=> self::_encryptPassword($password, $salt),
			'password_salt'		=> $salt,
			'email'				=> $email,
			'has_accepted_terms'=> 1,
			'is_enabled'		=> 0,		// needs to validate with validation_key
			'is_locked'			=> 0,
			'is_deleted'		=> 0,
			'timezone'			=> Settings::$defaultTimezone,
			'language'			=> \Sweany\Session::get(Settings::sessSweany, Settings::sessLanguage, 'short'),
			'validation_key'	=> md5(\Sweany\Session::id().$username.$password.$email.time()),
			'created'			=> self::$db->getNowUnixTimeStamp(),
		);
		return self::$db->insert(Settings::tblUsers, $data, true);
	}

	/**
	 * Update current logged in user (by its id)
	 *
	 * @param array() $data
	 */
	public static function update($data = array(), $id = null)
	{
		$id = ($id === null) ? self::id() : $id;

		if ( isset($data['modified']) ) {
			$data['modified'] = self::$db->getNowUnixTimeStamp();
		}
		if ( isset($data['password']) ) {
			$data['password'] = self::_encryptPassword($data['password'], self::_getPasswordSalt($id));
		}

		// Update Session
		$user = \Sweany\Session::get(Settings::sessSweany, Settings::sessUser);

		foreach ($data as $key => $val) {
			if ( $key != 'password') {
				$user[$key] = $val;
			}
			// Update language Specific stuff
			if ( $key == 'language' ) {
				// Update Language Session
				$_SESSION[Settings::sessSweany][Settings::sessLanguage]['short'] = $val;

				// Apply new language settings now!
				\Sweany\Language::changeLanguage($val);
			}
		}
		\Sweany\Session::set(array(Settings::sessSweany => Settings::sessUser), $user);

		// Update Database
		return self::$db->updateRow(Settings::tblUsers, $data, $id);
	}

	/**
	 *
	 * @param integer $password (cleartext)
	 */
	public static function updatePassword($password, $id = null)
	{
		$id = ($id === null) ? self::id() : $id;

		$data['password'] = self::_encryptPassword($password, self::_getPasswordSalt($id));

		return self::$db->updateRow(Settings::tblUsers, $data, $id);
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
		$user_id	= self::$db->fetchField(Settings::tblUsers, 'id', $condition);

		return ($user_id) ? self::$db->updateRow(Settings::tblUsers, array('is_enabled' => 1, 'validation_key' => ''), $user_id) : false;
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
		$session_id = \Session::id();
		$ip			= $_SERVER['REMOTE_ADDR'];
		$hostname	= gethostbyaddr($ip);
		$key		= md5(mt_rand().time().$session_id.$ip.$hostname.mt_rand().$user_id);

		self::$db->updateRow(Settings::tblUsers, array('reset_password_key' => $key), $user_id);
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
		$user_id	= self::$db->fetchField(Settings::tblUsers, 'id', $condition);

		return ($user_id) ? self::$db->updateRow(Settings::tblUsers, array('is_enabled' => 1, 'validation_key' => ''), $user_id) : false;
	}

	/**
	 *
	 * @param integer $user_id
	 */
	public static function removeResetPasswordKey($user_id)
	{
		return self::$db->updateRow(Settings::tblUsers, array('reset_password_key' => ''), $user_id);
	}





	/***************************************************************************************************************
	 *
	 *	P R I V A T E    F U N C T I O N S
	 *
	 ***************************************************************************************************************/

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
		for ($i=0; $i<Settings::hashRounds; $i++)
		{
			$hash = hash('sha512', $clearTextPassword.$passwordSalt.$hash);
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
		return self::$db->fetchRowField(Settings::tblUsers, 'password_salt', $user_id);
	}

	/**
	 *
	 * @param integer $user_id
	 */
	private static function _updateSuccessfulLogin($user_id)
	{
		$session_id = \Sweany\Session::id();
		$ip			= $_SERVER['REMOTE_ADDR'];
		$hostname	= gethostbyaddr($ip);
		$login_time	= self::$db->getNowUnixTimeStamp();

		$fields = array(
			'session_id'				=> $session_id,
			'last_ip'					=> $ip,
			'last_host'					=> $hostname,
			'last_login'				=> $login_time,
			'last_failed_login_count'	=> 0,
		);
		return self::$db->updateRow(Settings::tblUsers, $fields, $user_id);
	}

	/**
	 *
	 * @param string $username
	 * @param string $password
	 */
	private static function _logFailedLogin($username, $password)
	{
		$session_id = \Sweany\Session::id();
		$ip			= $_SERVER['REMOTE_ADDR'];
		$hostname	= gethostbyaddr($ip);
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
		return self::$db->insert(Settings::tblFailedLogins, $fields, false);
	}
}