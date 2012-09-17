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
 * @version		0.7 2012-08-08 11:25
 *
 *
 * This (optional) core will validate various settings of
 * the framework itself.
 */
namespace Core\Init;

class Validator extends CoreAbstract
{

	/* ******************************************** OVERRIDE INITIALIZE ********************************************/
	public static function initialize()
	{
		ini_set('display_errors', 1);
		error_reporting(-1);

		if ( !\Validate01Basics::initialize() )
		{
			return false;
		}
		if ( !\Validate02Config::initialize() )
		{
			return false;
		}
		if ( !\Validate03Language::initialize() )
		{
			return false;
		}
		if ( !\Validate04Database::initialize() )
		{
			return false;
		}
		if ( !\Validate05User::initialize() )
		{
			return false;
		}
		if ( !\Validate06UserOnlineCount::initialize() )
		{
			return false;
		}
		if ( !\Validate07LogVisitors::initialize() )
		{
			return false;
		}
		if ( !\Validate08Plugins::initialize() )
		{
			return false;
		}

		return true;
	}
}