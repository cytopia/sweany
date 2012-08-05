<?php
/**
 * This core module will provide an interface to handle users.
 *
 *
 * Sweany: MVC-like PHP Framework with blocks and tables (entities)
 * Copyright 2011-2012, Patu
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	Copyright 2011-2012, Patu
 * @link		none yet
 * @package		sweany.core
 * @author		Patu
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @version		0.7 2012-07-29 13:25
 *
 */

// TODO: needs rewrite
class Users extends Core\Init\CoreUsers
{
	public static function countOnlineUsers()
	{
		return parent::countOnlineUsers();
	}
	public static function countLoggedInOnlineUsers()
	{
		return parent::countLoggedInOnlineUsers();
	}
	public static function countAnonymousOnlineUsers()
	{
		return parent::countAnonymousOnlineUsers();
	}

	public static function getLoggedInOnlineUsers()
	{
		return parent::getLoggedInOnlineUsers();
	}


	// get Rows
	public static function getAllUsers()
	{
		return parent::getAllUsers();
	}
	public static function getAllUserGroups($order = array())
	{
		return parent::getAllUserGroups($order);
	}
	public static function getAllFailedLogins($order = array())
	{
		return parent::getAllFailedLogins($order);
	}
	public static function update($fields = array())
	{
		return parent::update($fields);
	}
	public static function updatePassword($user_id, $password)
	{
		return parent::updatePassword($user_id, $password);
	}
	public static function isMyPassword($clearTextPwd)
	{
		return parent::isMyPassword($clearTextPwd);
	}

	public static function data()
	{
		return parent::data();
	}
	public static function id()
	{
		return parent::id();
	}
	public static function name()
	{
		return parent::name();
	}
	public static function getNameById($id)
	{
		return parent::getNameById($id);
	}
	public static function getIdByNameAndPassword($username, $password)
	{
		return parent::getIdByNameAndPassword($username, $password);
	}
	public static function getIdByName($username)
	{
		return parent::getIdByName($username);
	}
	public static function getIdByEmail($email)
	{
		return parent::getIdByEmail($email);
	}
	public static function getIdByResetPasswordKey($reset_password_key)
	{
		return parent::getIdByResetPasswordKey($reset_password_key);
	}
	public static function getEnabledUser()
	{
		return parent::getEnabledUser();
	}
	public static function isLoggedIn()
	{
		return parent:: isLoggedIn();
	}
	public static function setResetPasswordKey($user_id)
	{
		return parent::setResetPasswordKey($user_id);
	}
	public static function checkPasswordResetKey($password_reset_key)
	{
		return parent::checkPasswordResetKey($password_reset_key);
	}
	public static function removeResetPasswordKey($user_id)
	{
		return parent::removeResetPasswordKey($user_id);
	}
	public static function isAdmin()
	{
		return parent::isAdmin();
	}
	public static function logout($session_id)
	{
		return parent::logout($session_id);
	}
	public static function usernameExists($username)
	{
		return parent::usernameExists($username);
	}
	public static function emailExists($email)
	{
		return parent::emailExists($email);
	}
	public static function otherUserHasThisEmail($email)
	{
		return parent::otherUserHasThisEmail($email);
	}
	public static function exists($user_id)
	{
		return parent::exists($user_id);
	}
	public static function isLocked($user_id)
	{
		return parent::isLocked($user_id);
	}
	public static function isEnabled($user_id)
	{
		return parent::isEnabled($user_id);
	}
	public static function isDeleted($user_id)
	{
		return parent::isDeleted($user_id);
	}
	public static function checkLogin($username, $password, $log_bad_attempts = true)
	{
		return parent::checkLogin($username, $password, $log_bad_attempts);
	}
	public static function validate($validation_key)
	{
		return parent::validate($validation_key);
	}
	public static function login($username, $password, $log_bad_attempts = true)
	{
		return parent::login($username, $password, $log_bad_attempts);
	}
	public static function addUser($username, $password, $email)
	{
		return parent::addUser($username, $password, $email);
	}
}
