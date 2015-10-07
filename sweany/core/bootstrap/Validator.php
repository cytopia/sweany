<?php
/**
 * Sweany MVC PHP framework
 * Copyright (C) 2011-2012 cytopia.
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
 * @copyright	Copyright 2011-2012, cytopia
 * @link		none yet
 * @package		sweany.core.init
 * @author		cytopia <cytopia@everythingcli.org>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.7 2012-08-08 11:25
 *
 *
 * This (optional) core will validate various settings of
 * the framework itself.
 */
namespace Sweany;

class Validator extends aBootTemplate
{

	/* ******************************************** OVERRIDE INITIALIZE ********************************************/
	public static function initialize($options = null)
	{
		ini_set('display_errors', 1);
		error_reporting(-1);

		if ( !\Sweany\Validate01Basics::initialize($options) )
		{
			return false;
		}
		if ( !\Sweany\Validate02Config::initialize($options) )
		{
			return false;
		}
		if ( !\Sweany\Validate03Language::initialize($options) )
		{
			return false;
		}
		if ( !\Sweany\Validate04Database::initialize($options) )
		{
			return false;
		}
		if ( !\Sweany\Validate05Tables::initialize($options) )
		{
			return false;
		}
		if ( !\Sweany\Validate06User::initialize($options) )
		{
			return false;
		}
		if ( !\Sweany\Validate07UserOnlineCount::initialize($options) )
		{
			return false;
		}
		if ( !\Sweany\Validate08LogVisitors::initialize($options) )
		{
			return false;
		}
		if ( !\Sweany\Validate09Plugins::initialize($options) )
		{
			return false;
		}

		return true;
	}
}