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
 * @package		sweany.core
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.8 2012-09-17 11:08
 *
 *
 * This Module provides global configuration settings for config.php
 * and all plugin config.php files
 *
 */

class Config
{
	/**
	 *
	 * Store for variable values
	 * @var mixed[]
	 */
	private static $store = null;



	/**
	 *
	 * Set the config value for a given variable
	 * @param	string	$var		Variable Name
	 * @param	mixed	$val		Variable Value
	 * @param	string	$scope		Scope Name
	 */
	public static function set($var, $val, $scope = 'globals')
	{
		self::$store[$scope][$var] = $val;
	}

	/**
	 *
	 * Get the config value for a given variable
	 * @param	string	$var		Variable Name
	 * @param	string	$scope		Scope Name
	 * @return	mixed	value
	 */
	public static function get($var, $scope = 'globals')
	{
		return isset(self::$store[$scope][$var]) ? self::$store[$scope][$var] : null;
	}

	/**
	 *
	 * Check if configuration has been set
	 * @param	string	$var		Variable Name
	 * @param	string	$scope		Scope Name
	 * @return	boolean	exists
	 */
	public static function exists($var, $scope = 'globals')
	{
		return isset(self::$store[$scope][$var]);
	}
}