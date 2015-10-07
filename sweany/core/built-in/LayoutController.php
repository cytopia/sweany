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
 * @package		sweany.core
 * @author		cytopia <cytopia@everythingcli.org>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.7 2012-07-29 13:25
 *
 *
 * Abstract parent for layout controller
 */
abstract class LayoutController extends BaseController
{

	/* ***************************************************** VARIABLES ***************************************************** */

	/*
	 * Defines the type of the controller
	 * page, layout or block.
	 * This is used to tell the language class,
	 * which section to use
	 */
	protected $ctrl_type = 'layout';



	/* ***************************************************** CONSTRUCTOR ***************************************************** */

	public function __construct()
	{
		parent::__construct();


		// default Layout
		$this->view($GLOBALS['DEFAULT_LAYOUT']);
	}
}
