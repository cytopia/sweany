<?php/**
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
 * @version		0.7 2012-07-29 13:25 * * * Rules to validate form input against */class Rules{	// used to validate a class defined rules	public static function validateRule($value, $rule)	{		$function	= $rule[0];		$params		= isset($rule[1]) ? $rule[1] : null;		// brackets around $function will break on windows machines		return Rules::$function($value, $params);	}	/**************************************************** URL PARAM RULES ****************************************************/	public static function equalsUrlParam($value, $paramPosition)	{		$urlParams	= Url::getParams();		if ( !isset($urlParams[$paramPosition]) )			return false;		return ( $value == $urlParams[$paramPosition] );	}	/**************************************************** INTEGER RULES ****************************************************/	public static function isNumber($value)	{		return (bool) preg_match('/^[0-9]+$/', $value);	}	public static function between($value, $offsets = array())	{		$low	= $offsets[0];		$high	= $offsets[1];		return ( $value >= $low && $value <= $high );	}	public static function equals($value, $number)	{		return ( $value == $number );	}	/**************************************************** STRING RULES ****************************************************/	/**	 *	 *	 * @param String $value	 * @param Array (of Strings) $strArr	 * @return boolean	 */	public static function strcmpOr($value, $strArr)	{		return in_array($value, $strArr);		/**		 * @deprecated: the above is faster		$equals = false;		foreach ($strArr as $needle)			if ($value == $needle)				return true;		return $equals;		*/	}	public static function minLen($value, $length)	{		return ( strlen($value) >= $length );	}	public static function maxLen($value, $length)	{		return ( strlen($value) <= $length );	}	public static function isHex($value)	{		return preg_match("/^[a-f0-9]{1,}$/is", $value);	}	public static function isEmail($value)	{		return (bool) filter_var($value, FILTER_VALIDATE_EMAIL);	}	public static function isUrl($value)	{		return (bool) filter_var($value, FILTER_VALIDATE_URL);	}	public static function isIp($value)	{		return (bool) filter_var($value, FILTER_VALIDATE_IP);	}	public static function isAlphaNumeric($value)	{		return (bool) preg_match('/^[A-Za-z0-9_]+$/', $value);	}	public static function isAlphabetOnly($value)	{		if (!strlen($value))			return true;		return (bool) preg_match('/^[A-Za-z ]+$/', $value);	}	public static function isAlphabetWithoutSpace($value)	{		if (!strlen($value))			return true;		return (bool) preg_match('/^[A-Za-z]+$/', $value);	}	/**************************************************** ARRAY RULES ****************************************************/	public static function isArray($value)	{		return ( is_array($value) );	}	public static function minSize($value, $length)	{		return ( count($value) > $length );	}	public static function maxSize($value, $length)	{		return ( count($value) < $length );	}}