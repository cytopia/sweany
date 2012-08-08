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
 * Sweaby is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Sweany. If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright	Copyright 2011-2012, Patu
 * @link		none yet
 * @package		sweany.core.lib
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.7 2012-08-08 11:32
 *
 *
 * This helper helper will provide an interface to handle users.
 */

class Users extends Core\Init\CoreUsers
{

	/************************************** ONLINE USERS **************************************/

	/**
	 * Count all current active users
	 *
	 * @return integer
	 */
	public static function countOnlineUsers()
	{
		return parent::countOnlineUsers();
	}

	/**
	 *
	 * @return integer
	 */
	public static function countLoggedInOnlineUsers()
	{
		return parent::countLoggedInOnlineUsers();
	}

	/**
	 *
	 * @return integer
	 */
	public static function countAnonymousOnlineUsers()
	{
		return parent::countAnonymousOnlineUsers();
	}

	/**
	 *
	 * @return array()
	 */
	public static function getLoggedInOnlineUsers()
	{
		return parent::getLoggedInOnlineUsers();
	}




	/************************************** GET CURRENT USER INFO **************************************/

	/**
	 *
	 * @return integer
	 */
	public static function id()
	{
		return parent::id();
	}

	/**
	 *
	 * @return string
	 */
	public static function name()
	{
		return parent::name();
	}

	/**
	 *
	 * @return array()
	 */
	public static function data()
	{
		return parent::data();
	}

	/**
	 *
	 * @return boolean
	 */
	public static function isLoggedIn()
	{
		return parent:: isLoggedIn();
	}

	/**
	 *
	 * @return boolean
	 */
	public static function isAdmin()
	{
		return parent::isAdmin();
	}

	/**
	 *
	 * @return boolean
	 */
	public static function isMyPassword($clearTextPwd)
	{
		return parent::isMyPassword($clearTextPwd);
	}






	/************************************** GET ANY USER INFO **************************************/


	/**
	 *
	 * @return string
	 */
	public static function getNameById($id)
	{
		return parent::getNameById($id);
	}

	/**
	 *
	 * @return integer
	 */
	public static function getIdByName($username)
	{
		return parent::getIdByName($username);
	}

	/**
	 *
	 * @return integer
	 */
	public static function getIdByEmail($email)
	{
		return parent::getIdByEmail($email);
	}

	/**
	 *
	 * @return integer
	 */
	public static function getIdByResetPasswordKey($reset_password_key)
	{
		return parent::getIdByResetPasswordKey($reset_password_key);
	}

	/**
	 *
	 * @return integer
	 */
	public static function getIdByNameAndPassword($username, $password)
	{
		return parent::getIdByNameAndPassword($username, $password);
	}



	/************************************** CHECK ANY USER **************************************/

	/**
	 *
	 * @param interger $user_id
	 * @return boolean
	 */
	public static function exists($user_id)
	{
		return parent::exists($user_id);
	}

	/**
	 *
	 * @param interger $user_id
	 * @return boolean
	 */
	public static function isLocked($user_id)
	{
		return parent::isLocked($user_id);
	}

	/**
	 *
	 * @param interger $user_id
	 * @return boolean
	 */
	public static function isEnabled($user_id)
	{
		return parent::isEnabled($user_id);
	}

	/**
	 *
	 * @param interger $user_id
	 * @return boolean
	 */
	public static function isDeleted($user_id)
	{
		return parent::isDeleted($user_id);
	}

	/**
	 *
	 * @param string $username
	 * @return boolean
	 */
	public static function usernameExists($username)
	{
		return parent::usernameExists($username);
	}

	/**
	 * Check if this email address already exists
	 * in the users database
	 *
	 * @param string $email
	 */
	public static function emailExists($email)
	{
		return parent::emailExists($email);
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
		return parent::otherUserHasThisEmail($email);
	}





	/************************************** GET MANY USERS **************************************/

	/**
	 * @return array()
	 */
	public static function getEnabledUser()
	{
		return parent::getEnabledUser();
	}

	/**
	 * @return array()
	 */
	public static function getAllUsers()
	{
		return parent::getAllUsers();
	}

	/**
	 * @return array()
	 */
	public static function getAllUserGroups($order = array())
	{
		return parent::getAllUserGroups($order);
	}

	/**
	 * @return array()
	 */
	public static function getAllFailedLogins($order = array())
	{
		return parent::getAllFailedLogins($order);
	}



	/************************************** ACTIONS  **************************************/

	public static function login($username, $password, $log_bad_attempts = true)
	{
		return parent::login($username, $password, $log_bad_attempts);
	}

	/**
	 * Check if the given username and password match.
	 * Can be used by the form validator, to automatically validate
	 * login.
	 *
	 * @param string	$username
	 * @param string	$password (cleartext)
	 * @param boolean	$log_bad_attempts (whether or not to log wrong user/pass)
	 * @return boolean	success
	 */
	public static function checkLogin($username, $password, $log_bad_attempts = true)
	{
		return parent::checkLogin($username, $password, $log_bad_attempts);
	}


	public static function logout($session_id)
	{
		return parent::logout($session_id);
	}




	/************************************** ADD/UPDATE  **************************************/

	public static function addUser($username, $password, $email)
	{
		return parent::addUser($username, $password, $email);
	}


	/**
	 * Update current logged in user (by its id)
	 *
	 * @param array() $fields
	 */
	public static function update($fields = array())
	{
		return parent::update($fields);
	}

	/**
	 *
	 * @param integer $user_id
	 * @param integer $password (cleartext)
	 */
	public static function updatePassword($user_id, $password)
	{
		return parent::updatePassword($user_id, $password);
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
		return parent::validate($validation_key);
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

}
