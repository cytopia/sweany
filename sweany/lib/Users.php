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
 * @version		0.7 2012-07-29 13:25
 *
 *
 * This helper module will provide an interface to handle users.
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
