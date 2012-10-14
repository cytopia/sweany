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

	/**
	 *
	 *	Which Tables to autoload into this Model
	 *
	 *	@param	mixed[]	$tables
	 *
	 *	@format
	 *	$tables = Array
	 *	(
	 *		'<tableName>',						# tables in the usr/tables folder
	 *		'<pluginName>	=> '<tableName>',	# tables in the usr/plugins/<plugin>/tables folder
	 *	);
	 */
	protected $tables = array();


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
						$this->$new_var	= \Sweany\AutoLoader::loadPluginTable($pluginTable, $plugin, 'user');
					}
				}
				else
				{
					$new_var		= $table;
					$this->$new_var = \Sweany\AutoLoader::loadTable($table, 'user');
				}
			}
		}
	}
}
