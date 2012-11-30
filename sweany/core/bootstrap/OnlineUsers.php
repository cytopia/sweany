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

class OnlineUsers extends aBootTemplate
{

	/**************************************  V A R I A B L E S  **************************************/

	private static $onlineSinceMinutes;	// count online users from last XX Minutes till now
	private static $fakeOnlineGuests;	// set the amount of fake online guests

	/**
	 *	@param object	$db	Database Object
	 */
	private static $db = null;



	/**************************************  C O N S T R U C T O R  **************************************/


	public static function initialize($options = null)
	{
		self::$db		= \Sweany\Database::getInstance();

		self::$onlineSinceMinutes	= $GLOBALS['USER_ONLINE_SINCE_MINUTES'];
		self::$fakeOnlineGuests		= $GLOBALS['USER_ONLINE_ADD_FAKE_GUESTS'];

		$data['time']				= time();
		$data['fk_user_id']			= \Sweany\Users::id();
		$data['session_id']			= \Sweany\Session::id();
		$data['ip']					= $_SERVER['REMOTE_ADDR'];
		$data['current_page']		= \Sweany\Url::$request;

		// Add current user to online users table
		self::$db->insert(Settings::tblOnlineUsers, $data, false);

		// Delete all entries since last XX minutes
		self::$db->delete(Settings::tblOnlineUsers, sprintf('`time` < %d', strtotime('-'.self::$onlineSinceMinutes.' minute', time())));

		return true;
	}



	/**************************************  F U N C T I O N S  **************************************/


	/**
	 * Count all current active users
	 *
	 * @return integer
	 */
	public static function countAllUsers()
	{
		return (self::$fakeOnlineGuests + self::$db->countDistinct(Settings::tblOnlineUsers, 'session_id', null));
	}



	/**
	 * Count only currently logged in Users
	 *
	 * @return integer
	 */
	public static function countLoggedInUsers()
	{
		$query = 
			'SELECT
				COUNT(*) AS counter
			FROM(
				SELECT		DISTINCT fk_user_id, session_id
				FROM		`'.Settings::tblOnlineUsers.'`
				WHERE		fk_user_id>0
				GROUP BY	session_id
			) AS tbl_result
			GROUP BY
				tbl_result.fk_user_id;';

		$result	= self::$db->select($query);
		return isset($result[0]->counter) ? $result[0]->counter : 0;
	}



	/**
	 * Count only currently not logged in Users
	 *
	 * @param	boolean 	$include_faked_online_guests	Whether or not to also add the faked online guests
	 * @return	integer		total
	 */
	public static function countAnonymousUsers($include_faked_online_guests = true)
	{
		$fake	= ($include_faked_online_guests) ? self::$fakeOnlineGuests : 0;
		$query	= 
			'SELECT
				COUNT(DISTINCT session_id) AS counter
			FROM
				`'.Settings::tblOnlineUsers.'`
			WHERE
				fk_user_id=0 AND
				session_id NOT IN
				(SELECT session_id
					FROM(
						SELECT		DISTINCT fk_user_id, session_id
						FROM		`'.Settings::tblOnlineUsers.'`
						WHERE		fk_user_id>0
						GROUP BY	session_id
					) AS tbl_result
				GROUP BY
					tbl_result.fk_user_id
				)
			GROUP BY
				session_id;';

		$result	= self::$db->select($query);
		$count	= isset($result[0]->counter) ? $result[0]->counter : 0;
		return ($fake + $count);
	}



	/**
	 * Get all currently logged in users
	 *
	 * @return mixed[]
	 */
	public static function getLoggedInUsers()
	{
		$query	=
			'SELECT
				fk_user_id AS id,
				User.username
			FROM(
				SELECT		DISTINCT fk_user_id, session_id
				FROM		`'.Settings::tblOnlineUsers.'`
				WHERE		fk_user_id>0
				GROUP BY	session_id
			) AS tbl_result
			JOIN `'.Settings::tblUsers.'` AS User ON (User.id = tbl_result.fk_user_id)
			GROUP BY tbl_result.fk_user_id;';

		return self::$db->select($query);
	}
}