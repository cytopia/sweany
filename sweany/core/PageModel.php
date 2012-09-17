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
 * @version		0.7 2012-07-29 13:25
 *
 *
 * Abstract parent for page modesl
 */
abstract Class PageModel
{
	/* ***************************************************** VARIABLES ***************************************************** */


	/*
	 * Defines whether the page controller is used by normal
	* pages or by pages supplied by plugins.
	*
	* Default is set to false and will be overriden by
	* Plugins in the controller
	*/
	protected $plugin = false;

	/*
	 * Which Tables to autoload into this Model
	 */
	protected $tables = array();

	protected $plugin_tables = array();


	/* ***************************************************** Constructor ***************************************************** */

	public function __construct()
	{
		// Tables are only instanciated, if we use SQL, otherwise
		// we will not have access to any tables
		if ( $GLOBALS['SQL_ENABLE'] )
		{
			// initialize table helpers
			foreach ($this->tables as $plugin => $table)
			{
				if ( is_array($table) )
				{
					foreach ($table as $pluginTable)
					{
						$new_var		= $pluginTable;
						$this->$new_var	= Loader::loadPluginTable($pluginTable, $plugin);
					}
				}
				else
				{
					$new_var		= $table;
					$this->$new_var = Loader::loadTable($table);
				}
			}
		}
	}
}
