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
 * @package		sweany.core.validator
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.7 2012-08-08 11:25
 *
 *
 * This (optional) core will validate various settings of
 * the framework itself.
 */
namespace Sweany;

class Validate05Tables extends aBootTemplate
{
	/* ******************************************** OVERRIDE INITIALIZE ********************************************/
	public static function initialize($options = null)
	{
		if ( $GLOBALS['SQL_ENABLE'] == true )
		{
			if ( !self::_checkTableFiles() )
			{
				echo '<h1>Validation Error: SQL</h2>';
				return false;
			}
		}
		return true;
	}


	private static function _checkTableFiles()
	{
		$db = \Sweany\Database::getInstance();

		// Validate usr/tables
		if ( $handle = opendir(USR_TABLES_PATH) )
		{
			while ( false !== ($file = readdir($handle)) )
			{
				if ( pathinfo(USR_TABLES_PATH.DS.$file, PATHINFO_EXTENSION) == 'php' )
				{
					$file		= str_replace('Table.php', '', $file);
					$tblClass	= \Loader::loadTable($file);

					// ---------------- VALIDATE ALIAS
					if ( !strlen($tblClass->alias) )
					{
						self::$error	= 'Table Alias is not set in <b>'.$tblClass->table.'.</b><br/>Expected:<br/>public $alias = \'alias_name\';';
						return false;
					}

					// ---------------- VALIDATE FIELDS
					$sqlColumns	= $db->getColumnNames($tblClass->table);
					$tblColumns	= $tblClass->fields;

					// SQL -> Table
					foreach ($sqlColumns as $col)
					{
						if ( !in_array($col, $tblColumns) )
						{
							self::$error	= 'SQL Columns <b>'.$col.'</b> is missing in table: <b>'.$file.'->fields.';
							return false;
						}
					}
					// Table -> SQL
					foreach ($tblColumns as $col)
					{
						if ( !in_array($col, $sqlColumns) )
						{
							self::$error	= 'Table Field '.$file.'->fields(<b>\''.$col.'\'</b>) is missing in sql table: <b>'.$tblClass->table;
							return false;
						}
					}

					// ---------------- VALIDATE PK
					$sqlPK	= $db->getPrimaryKey($tblClass->table);
					$tblPK	= $tblClass->primary_key;

					if ( $sqlPK != $tblPK )
					{
						self::$error	= 'SQL Primary Key ('.$sqlPK.') does not match tables Primary Key ('.$tblPK.')';
						return false;
					}

					// ---------------- VALIDATE RELATIONS
					$hasOne		= $tblClass->hasOne;
					$hasMany	= $tblClass->hasMany;
					$belongsTo	= $tblClass->belongsTo;
					$habtm		= $tblClass->hasAndBelongsToMany;

					// Generic relation checks
					if ( !self::__checkRelation($hasOne, $file, $tblClass, 'hasOne', $db) )				{return false;}
					if ( !self::__checkRelation($hasMany, $file, $tblClass, 'hasMany', $db) )			{return false;}
					if ( !self::__checkRelation($belongsTo, $file, $tblClass, 'belongsTo', $db) )		{return false;}
					if ( !self::__checkRelation($habtm, $file, $tblClass, 'hasAndBelongsToMany', $db) )	{return false;}
				}
			}
		}
		else
		{
			self::$error	= USR_TABLES_PATH.' is not a directory!';
			return false;
		}

		// Validate usr/plugins/<name>/tables
		if ( $handle = opendir(USR_PLUGINS_PATH) )
		{
			while ( false !== ($plugin = readdir($handle)) )
			{
				if ( $plugin != '.' && $plugin != '..' && is_dir(USR_PLUGINS_PATH.DS.$plugin) )
				{
					if ( $t_handle = opendir(USR_PLUGINS_PATH.DS.$plugin.DS.'tables') )
					{
						while ( false !== ($file = readdir($t_handle)) )
						{
							if ( pathinfo(USR_PLUGINS_PATH.DS.$plugin.DS.'tables'.DS.$file, PATHINFO_EXTENSION) == 'php')
							{
								$file		= str_replace('Table.php', '', $file);
								$tblClass	= \Loader::loadPluginTable($file, $plugin);

								// ---------------- VALIDATE FIELDS
								$sqlColumns	= $db->getColumnNames($tblClass->table);
								$tblColumns	= $tblClass->fields;

								// SQL -> Table
								foreach ($sqlColumns as $col)
								{
									if ( !in_array($col, $tblColumns) )
									{
										self::$error	= 'SQL Columns <b>'.$col.'</b> is missing in table: <b>'.$file.'->fields.';
										return false;
									}
								}
								// Table -> SQL
								foreach ($tblColumns as $col)
								{
									if ( !in_array($col, $sqlColumns) )
									{
										self::$error	= 'Table Field '.$file.'->fields(<b>\''.$col.'\'</b>) is missing in sql table: <b>'.$tblClass->table;
										return false;
									}
								}


								// ---------------- VALIDATE PK
								$sqlPK	= $db->getPrimaryKey($tblClass->table);
								$tblPK	= $tblClass->primary_key;

								if ( $sqlPK != $tblPK )
								{
									self::$error	= 'SQL Primary Key ('.$sqlPK.') does not match tables Primary Key ('.$tblPK.')';
									return false;
								}


								// ---------------- VALIDATE RELATIONS
								$hasOne		= $tblClass->hasOne;
								$hasMany	= $tblClass->hasMany;
								$belongsTo	= $tblClass->belongsTo;
								$habtm		= $tblClass->hasAndBelongsToMany;

								// Generic relation checks
								if ( !self::__checkRelation($hasOne, $file, $tblClass, 'hasOne', $db) )				{return false;}
								if ( !self::__checkRelation($hasMany, $file, $tblClass, 'hasMany', $db) )			{return false;}
								if ( !self::__checkRelation($belongsTo, $file, $tblClass, 'belongsTo', $db) )		{return false;}
								if ( !self::__checkRelation($habtm, $file, $tblClass, 'hasAndBelongsToMany', $db) )	{return false;}
							}
						}
					}
					else
					{
						self::$error	= $path.' is not a directory!';
						return false;
					}
				}
			}
		}
		else
		{
			self::$error	= USR_PLUGINS_PATH.' is not a directory!';
			return false;
		}

		return true;
	}


	private static function __checkRelation($relation, $tbl_name, $tblClass, $type, $db)
	{
		foreach ($relation as $alias => $options)
		{
			// Table is defined?
			if ( !isset($options['table']) )
			{
				self::$error = '<b>\'table\'</b> property missing in '.$tbl_name.'->'.$type.'[\''.$alias.'\']';
				return false;
			}

			// Table exists in database?
			if ( !$db->tableExists($options['table']) )
			{
				self::$error = '<b>\''.$options['table'].'\'</b> does not exist in database. Defined in '.$tbl_name.'->'.$type.'[\''.$alias.'\'][\'table\']=\''.$options['table'].'\'';
				return false;
			}

			// Check Primary Key
			$tblPK	= (isset($options['primaryKey'])) ? $options['primaryKey'] : 'id';
			$sqlPK	= $db->getPrimaryKey($options['table']);
			if ( $sqlPK != $tblPK )
			{
				if ( !isset($options['primaryKey']) )
				{
					self::$error	= 'SQL Primary Key ('.$options['table'].':'.$sqlPK.') does not match relations Primary Key ('.$tblPK.') in '.$tbl_name.'->'.$type.'[\''.$alias.'\']. Primary key not specified, guessing default <b>id</b>';
				}
				else
				{
					self::$error	= 'SQL Primary Key ('.$options['table'].':'.$sqlPK.') does not match relations specified Primary Key ('.$tblPK.') in '.$tbl_name.'->'.$type.'[\''.$alias.'\'][\'primaryKey\']=\''.$options['primaryKey'].'\'';
				}
				return false;
			}


			// Check Fields
			if ( !isset($options['fields']) )
			{
				self::$error = '<b>'.$tbl_name.'Table</b> missing \'fields\' property. Should have: '.$tbl_name.'->'.$type.'[\''.$alias.'\'][\'fields\']';
				return false;
			}
			if ( !is_array($options['fields']) )
			{
				self::$error = '<b>'.$tbl_name.'Table</b>  \'fields\' must be an array: '.$tbl_name.'->'.$type.'[\''.$alias.'\'][\'fields\']=array()';
				return false;
			}
			if ( !count($options['fields']) )
			{
				self::$error = '<b>'.$tbl_name.'Table</b> does not specify any  \'fields\' in: '.$tbl_name.'->'.$type.'[\''.$alias.'\'][\'fields\']';
				return false;
			}

			// Check fields against sql fields
			$sqlFields = $db->getColumnNames($options['table']);

			foreach ($options['fields'] as $field)
			{
				if ( !in_array($field, $sqlFields) )
				{
					self::$error = 'Field <b>'.$field.'</b> specified in: '.$tbl_name.'->'.$type.'[\''.$alias.'\'][\'fields\'] Does not exist in database table: '.$options['table'];
					return false;

				}
			}

			// If recursive relation, we have to check if the other class exists
			if ( isset($options['recursive']) && $options['recursive'] == true )
			{
				// Get Class Name (default is to camelCase the sql table
				$className	= (isset($options['class'])) ? $options['class'] : \Strings::camelCase($options['table'], true);

				// Is Plugin?
				$plugin		= (isset($options['plugin'])&&strlen($options['plugin'])) ? $options['plugin'] : null;

				// File exists?
				$path		= ($plugin) ? USR_PLUGINS_PATH.DS.$plugin.DS.'tables'.DS.$className.'Table.php' : USR_TABLES_PATH.DS.$className.'Table.php';

				if ( !is_file($path) )
				{
					if ( !isset($options['class']) )
					{
						self::$error = 'Recursive Table File does not exist. You have not specified '.$tbl_name.'->'.$type.'[\''.$alias.'\'][\'class\']. Guessing based on camel-cased sql table: <b>'.$className.'</b> Path: '.$path;
					}
					else
					{
						self::$error = 'Recursive Table File does not exist. Path: '.$path;
					}
					return false;
				}
				// TODO:!!! validate correct alias namings of the file itself
			}

			// specific relation check
			if ( $type == 'hasOne' )
			{
				if ( !self::__checkHasOne($options, $alias, $tbl_name, $tblClass, $db) )
				{
					return false;
				}
			}
			else if ( $type == 'hasMany' )
			{
				if ( !self::__checkHasMany($options, $alias, $tbl_name, $tblClass, $db) )
				{
					return false;
				}
			}
			else if ( $type == 'belongsTo' )
			{
				if ( !self::__checkBelongsTo($options, $alias, $tbl_name, $tblClass, $db) )
				{
					return false;
				}
			}
			else
			{
				if ( !self::__checkHabtm($options, $alias, $tbl_name, $tblClass, $db) )
				{
					return false;
				}
			}
		}
		return true;
	}

	private static function __checkHasOne($options, $alias, $tbl_name, $tblClass, $db)
	{
		$tblFK		= (isset($options['foreignKey'])) ? $options['foreignKey'] : 'fk_'.$tblClass->table.'_id';
		$sqlColumns	= $db->getColumnNames($options['table']);

		if ( !in_array($tblFK, $sqlColumns) )
		{
			if ( !isset($options['foreignKey']) )
			{
				self::$error = $tbl_name.'->hasOne[\''.$alias.'\'][\'foreignKey\'] not set. Guessing default: <b>'.$tblFK.'</b>. But '.$tblFK.' does not exist in sql table: '.$options['table'];
			}
			else
			{
				self::$error = $tbl_name.'->hasOne[\''.$alias.'\'][\'foreignKey\'] = \''.$options['foreignKey'].'\'. But '.$tblFK.' does not exist in sql table: '.$options['table'];
			}
			return false;
		}
		return true;
	}

	private static function __checkHasMany($options, $alias, $tbl_name, $tblClass, $db)
	{
		$tblFK		= (isset($options['foreignKey'])) ? $options['foreignKey'] : 'fk_'.$tblClass->table.'_id';
		$sqlColumns	= $db->getColumnNames($options['table']);

		if ( !in_array($tblFK, $sqlColumns) )
		{
			if ( !isset($options['foreignKey']) )
			{
				self::$error = $tbl_name.'->hasMany[\''.$alias.'\'][\'foreignKey\'] not set. Guessing default: <b>'.$tblFK.'</b>. But '.$tblFK.' does not exist in sql table: '.$options['table'];
			}
			else
			{
				self::$error = $tbl_name.'->hasMany[\''.$alias.'\'][\'foreignKey\'] = \''.$options['foreignKey'].'\'. But '.$tblFK.' does not exist in sql table: '.$options['table'];
			}
			return false;
		}
		return true;
	}

	private static function __checkBelongsTo($options, $alias, $tbl_name, $tblClass, $db)
	{
		$tblFK 		= (isset($options['foreignKey'])) ? $options['foreignKey'] : 'fk_'.$options['table'].'_id';
		$sqlColumns	= $db->getColumnNames($tblClass->table);

		if ( !in_array($tblFK, $sqlColumns) )
		{
			if ( !isset($options['foreignKey']) )
			{
				self::$error = $tbl_name.'->belongsTo[\''.$alias.'\'][\'foreignKey\'] not set. Guessing default: <b>'.$tblFK.'</b>. But '.$tblFK.' does not exist in sql table: '.$tblClass->table;
			}
			else
			{
				self::$error = $tbl_name.'->belongsTo[\''.$alias.'\'][\'foreignKey\'] = \''.$options['foreignKey'].'\'. But '.$tblFK.' does not exist in sql table: '.$tblClass->table;
			}
			return false;
		}
		return true;
	}

	private static function __checkHabtm($habtm, $alias, $tbl_name, $tblClass, $db)
	{
		// TODO: add validation for hasAndBelongsToMany relation on foreignKeys
		return true;
	}



}
