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
 * @package		sweany.core.lib
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.7 2012-07-29 13:25
 *
 *
 * Rules to validate form input against
 */
class Rules
{


	/**
	 * Wrapper function that can handle
	 * the rules specified in form validation array
	 *
	 * @param array		$fieldValues
	 * @param array		$rule
	 * @return boolean	Success (valid|invalid)
	 */
	public static function validateRule($value, $rule)
	{
		$function	= $rule[0];
		$params		= isset($rule[1]) ? $rule[1] : null;
		$size		= count($value);

		switch ($size)
		{
			case 1:  return Rules::$function($value[0], $params); break;
			case 2:  return Rules::$function($value[0], $value[1], $params); break;
			case 3:  return Rules::$function($value[0], $value[1], $value[2], $params); break;
			case 4:  return Rules::$function($value[0], $value[1], $value[2], $value[3], $params); break;
			case 5:  return Rules::$function($value[0], $value[1], $value[2], $value[3], $value[4], $params); break;
			case 6:  return Rules::$function($value[0], $value[1], $value[2], $value[3], $value[4], $value[5], $params); break;
			case 7:  return Rules::$function($value[0], $value[1], $value[2], $value[3], $value[4], $value[5], $value[6], $params); break;
			case 8:  return Rules::$function($value[0], $value[1], $value[2], $value[3], $value[4], $value[5], $value[6], $value[7], $params); break;
			case 9:  return Rules::$function($value[0], $value[1], $value[2], $value[3], $value[4], $value[5], $value[6], $value[7], $value[8], $params); break;
			default: $value[$size] = $params;	// append params to array
					 return call_user_func_array(array(Rules, $function), $params); break;
		}
	}


	/**************************************************** URL PARAM RULES ****************************************************/

	public static function equalsUrlParam($value, $paramPosition)
	{
		$urlParams	= \Sweany\Url::getParams();

		if ( !isset($urlParams[$paramPosition]) ) {
			return false;
		}

		return ( $value == $urlParams[$paramPosition] );
	}


	/**************************************************** INTEGER RULES ****************************************************/

	public static function isNumber($value)
	{
		return (bool) preg_match('/^[0-9]+$/', $value);
	}

	public static function between($value, $offsets = array())
	{
		$low	= $offsets[0];
		$high	= $offsets[1];

		return ( $value >= $low && $value <= $high );
	}

	public static function equals($value1, $value2)
	{
		return ( $value1 == $value2 );
	}

	/**************************************************** STRING RULES ****************************************************/

	/**
	 * @param String $value
	 * @param Array (of Strings) $strArr
	 * @return boolean
	 */
	public static function strcmpOr($value, $strArr)
	{
		return in_array($value, $strArr);
	}

	public static function minLenIfNotEmpty($value, $min)
	{
		$len = mb_strlen($value);
		return $len ? $len >= $min : true;
	}

	public static function minLen($value, $min)
	{
		return ( mb_strlen($value) >= $min );
	}
	public static function maxLen($value, $max)
	{
		return ( mb_strlen($value) <= $max );
	}

	/**
	 *
	 * Checks if a given number is hexadecimal
	 *
	 * @param number $value
	 * @return boolean Success
	 */
	public static function isHex($value)
	{
		return preg_match("/^[a-f0-9]{1,}$/is", $value);
	}


	public static function isEmail($value)
	{
		return (bool) filter_var($value, FILTER_VALIDATE_EMAIL);
	}
	public static function isUrl($value)
	{
		return (bool) filter_var($value, FILTER_VALIDATE_URL);
	}
	public static function isIp($value)
	{
		return (bool) filter_var($value, FILTER_VALIDATE_IP);
	}
	public static function isAlphaNumeric($value)
	{
		return (bool) preg_match('/^[A-Za-z0-9_]+$/', $value);
	}
	public static function isAlphabetOnly($value)
	{
		if (!isset($value[0])) {
			return true;
		}
		return (bool) preg_match('/^[A-Za-z ]+$/', $value);
	}
	public static function isAlphabetWithoutSpace($value)
	{
		if (!isset($value[0])) {
			return true;
		}
		return (bool) preg_match('/^[A-Za-z]+$/', $value);
	}


	/**************************************************** ARRAY RULES ****************************************************/

	public static function isArray($value)
	{
		return ( is_array($value) );
	}
	public static function minSize($value, $length)
	{
		return ( count($value) >= $length );
	}
	public static function maxSize($value, $length)
	{
		return ( count($value) <= $length );
	}







	/***************************************************** USER VALIDATION RULES *******************************************/


	/**
	 * checks if the given username is available
	 * or already taken.
	 *
	 * @param string $username
	 * @return boolean
	 */
	public static function userNameAvailable($username)
	{
		return !\Sweany\Users::usernameExists($username);
	}


	/**
	 * Checks if the given email is available
	 * or already taken.
	 *
	 * Note: This cannot be used, if a user wants
	 *       to change his/her email. In the case
	 *       he changes it to the same as he/she already had,
	 *       the function will say, that the email already exists.

	 * @param string $email
	 * @return booleam
	 */
	public static function userEmailAvailable($email)
	{
		return !\Sweany\Users::emailExists($email);
	}

	/**
	 * Check if email exists
	 * @param string $email
	 */
	public static function userEmailNotExist($email)
	{
		return \Sweany\Users::emailExists($email);
	}


	public static function userOtherUserNotHasThisEmail($email)
	{
		return !\Sweany\Users::otherUserHasThisEmail($email);
	}

	public static function userIsMyPassword($password)
	{
		return \Sweany\Users::isMyPassword($password);
	}

	/**
	 * Validates a username/password match and
	 * tells if they are correct
	 *
	 * @param string $username
	 * @param string $password
	 * @return boolean
	 */
	public static function userCheckLogin($username, $password)
	{
		return \Sweany\Users::checkLogin($username, $password);
	}

	public static function userIsNotLocked($username, $password)
	{
		$user_id = \Sweany\Users::getIdByNameAndPassword($username, $password);
		return ($user_id) ? !\Sweany\Users::isLocked($user_id) : true;
	}

	public static function userIsNotDeleted($username, $password)
	{
		$user_id = \Sweany\Users::getIdByNameAndPassword($username, $password);
		return ($user_id) ? !\Sweany\Users::isDeleted($user_id) : true;
	}

	public static function userIsEnabled($username, $password)
	{
		$user_id = \Sweany\Users::getIdByNameAndPassword($username, $password);
		return ($user_id) ? \Sweany\Users::isEnabled($user_id) : true;
	}
}
