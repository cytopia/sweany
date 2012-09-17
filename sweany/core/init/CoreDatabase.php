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
 * @version		0.7 2012-07-29 13:25
 *
 *
 * MySQL Database Class.
 */
namespace Core\Init;

class CoreDatabase extends CoreAbstract
{
	public static $db	= null;

	/*********************************************** OVERRIDE INITIALIZE ***********************************************/

	public static function initialize()
	{
		self::$db	= 'Engine'.ucfirst($GLOBALS['SQL_ENGINE']);	// e.g.: EngineMysql
		$db			= self::$db;

		require_once(CORE_INIT_PATH.DS.'engines'.DS.$db.'.php');

		if ( !$db::initialize() )
		{
			self::$error = $db::getError();
			return false;
		}
		return true;
	}


	/*********************************************** OVERRIDE CLEANUP ***********************************************/

	public static function cleanup()
	{
		$db	= self::$db;
		$db::cleanup();
	}
}
