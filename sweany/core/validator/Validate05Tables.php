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
			if ( !self::checkCoreTables() )
			{
				echo '<h1>Validation Error: SQL</h2>';
				return false;
			}
			if ( !self::checkUsrTables() )
			{
				echo '<h1>Validation Error: SQL</h2>';
				return false;
			}
			if ( !self::checkUsrPluginTables() )
			{
				echo '<h1>Validation Error: SQL</h2>';
				return false;
			}
		}
		return true;
	}


	private static function checkCoreTables()
	{
		$db = \Sweany\Database::getInstance();

		// Validate usr/tables
		if ( $handle = opendir(CORE_TABLE) )
		{
			while ( false !== ($file = readdir($handle)) )
			{
				if ( pathinfo(CORE_TABLE.DS.$file, PATHINFO_EXTENSION) == 'php' )
				{
					$file		= str_replace('Table.php', '', $file);
					$tblClass	= \Loader::loadCoreTable($file);

					// ---------------- VALIDATE ALIAS
					if ( !$tblClass->alias || !strlen($tblClass->alias) )
					{
						self::$error	= 'Table Alias is not set in <b>'.$tblClass->table.'.</b><br/>Expected:<br/>public $alias = \'alias_name\';';
						return false;
					}
					
					// ---------------- VALIDATE CREATED/MODIFIED Fields
					if ( !self::__checkCreatedModifiedFields($tblClass, $file, $db) ) {return false;}
					

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
			self::$error	= CORE_TABLE.' is not a directory!';
			return false;
		}
		return true;
	}


	private static function checkUsrTables()
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
					if ( !$tblClass->alias || !strlen($tblClass->alias) )
					{
						self::$error	= 'Table Alias is not set in <b>'.$tblClass->table.'.</b><br/>Expected:<br/>public $alias = \'alias_name\';';
						return false;
					}

					// ---------------- VALIDATE CREATED/MODIFIED Fields
					if ( !self::__checkCreatedModifiedFields($tblClass, $file, $db) ) {return false;}


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

		return true;
	}


	private static function checkUsrPluginTables()
	{
		$db = \Sweany\Database::getInstance();

		// Validate usr/tables

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

								// ---------------- VALIDATE ALIAS
								if ( !$tblClass->alias || !strlen($tblClass->alias) )
								{
									self::$error	= 'Table Alias is not set in <b>'.$tblClass->table.'.</b><br/>Expected:<br/>public $alias = \'alias_name\';';
									return false;
								}

								// ---------------- VALIDATE CREATED/MODIFIED Fields
								if ( !self::__checkCreatedModifiedFields($tblClass, $file, $db) ) {return false;}

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



	/* ******************************************* PRIVATE HELPERS ******************************************* */
	
	
	private static function __checkCreatedModifiedFields($tblClass, $tblName, $db)
	{
		$data	= $db->getColumnTypes($tblClass->table);
		$names	= array_keys($data);
		$types	= array_values($data);
		
			// --------------------------- CHECK CREATED ---------------------------
		if ( !is_null($tblClass->hasCreated) )
		{
			if ( is_string($tblClass->hasCreated) )
			{
				if ( !in_array('created', $names) )
				{
					self::$error = $tblName.'Table.php $hasCreated = \''.$tblClass->hasCreated.'\';<br/>Only type has been set. Assuming field name to be <strong>created</strong>, but field \'created\' does not exist in sql table '.$tblClass->table.'.<br/>Consider using $hasCreated = array(\'sql_fieldName\' => \''.$tblClass->hasCreated.'\');';
					return false;
				}
				if ( $tblClass->hasCreated == 'datetime' )
				{
					if ( strtolower($data['created']) != 'datetime' )
					{
						self::$error = $tblName.'Table.php $hasCreated = \''.$tblClass->hasCreated.'\';<br/>But SQL Type of '.$tblClass->table.'.created is <strong>'.strtolower($data['created']).'</strong>';
						return false;
					}
				}
				if ( $tblClass->hasCreated == 'timestamp' )
				{
					if ( strtolower($data['created']) != 'timestamp' )
					{
						self::$error = $tblName.'Table.php $hasCreated = \''.$tblClass->hasCreated.'\';<br/>But SQL Type of '.$tblClass->table.'.created is <strong>'.strtolower($data['created']).'</strong>';
						return false;
					}
				}
				if ( $tblClass->hasCreated == 'integer' )
				{
					if ( strtolower($data['created']) != 'int' )
					{
						self::$error = $tblName.'Table.php $hasCreated = \''.$tblClass->hasCreated.'\';<br/>But SQL Type of '.$tblClass->table.'.created is <strong>'.strtolower($data['created']).'</strong>';
						return false;
					}
				}
				else
				{
					self::$error = $tblName.'Table.php $hasCreated = \''.$tblClass->hasCreated.'\';<br/> Is a wrong type! SQL Type of '.$tblClass->table.'.created is <strong>'.strtolower($data['created']).'</strong><br/>Allowed types are \'datetime\', \'timestamp\' and \'integer\'';
					return false;
				}
			}
			else if ( is_array($tblClass->hasCreated) )
			{
				$name	= array_keys($tblClass->hasCreated);
				$name	= isset($name[0]) ? $name[0] : null;
				$type	= array_values($tblClass->hasCreated);
				$type	= isset($type[0]) ? $type[0] : null;

				if ( !in_array($name, $names) )
				{
					self::$error = $tblName.'Table.php $hasCreated = array(\''.$name.'\' => \''.$type.'\');<br/>Field <strong>'.$name.'</strong> does not exist in sql table '.$tblClass->table;
					return false;
				}
				if ( $type == 'datetime' )
				{
					if ( strtolower($data[$name]) != 'datetime' )
					{
						self::$error = $tblName.'Table.php $hasCreated = array(\''.$name.'\' => \''.$type.'\');<br/>But SQL Type of '.$tblClass->table.'.'.$name.' is <strong>'.strtolower($data['created']).'</strong>';
						return false;
					}
				}
				if ( $type == 'timestamp' )
				{
					if ( strtolower($data[$name]) != 'timestamp' )
					{
						self::$error = $tblName.'Table.php $hasCreated = array(\''.$name.'\' => \''.$type.'\');<br/>But SQL Type of '.$tblClass->table.'.'.$name.' is <strong>'.strtolower($data['created']).'</strong>';
						return false;
					}
				}
				if ( $type == 'integer' )
				{
					if ( strtolower($data[$name]) != 'int' )
					{
						self::$error = $tblName.'Table.php $hasCreated = array(\''.$name.'\' => \''.$type.'\');<br/>But SQL Type of '.$tblClass->table.'.'.$name.' is <strong>'.strtolower($data['created']).'</strong>';
						return false;
					}
				}
				else
				{
					self::$error = $tblName.'Table.php $hasCreated = array(\''.$name.'\' => \''.$type.'\');<br/>Is a wrong type ! SQL Type if '.$tblClass->table.'.'.$name.' is <strong>'.strtolower($data['created']).'</strong><br/>Allowed types are \'datetime\', \'timestamp\' and \'integer\'';
					return false;
				}
			}
			else
			{
				self::$error = $tblName.'Table.php $hasCreated has a wrong format.<br/>Allowed values: $hasCreated = \'sql_type\' or $hasCreated = array(\'field_name\' => \'sql_type\')';
				return false;
			}
		}
		

		// --------------------------- CHECK MODIFIED ---------------------------
		if ( !is_null($tblClass->hasModified) )
		{
			if ( is_string($tblClass->hasModified) )
			{
				if ( !in_array('modified', $names) )
				{
					self::$error = $tblName.'Table.php $hasModified = \''.$tblClass->hasModified.'\';<br/>Only type has been set. Assuming field name to be <strong>modified</strong>, but field \'modified\' does not exist in sql table '.$tblClass->table.'.<br/>Consider using $hasModified = array(\'sql_fieldName\' => \''.$tblClass->hasModified.'\');';
					return false;
				}
				if ( $tblClass->hasModified == 'datetime' )
				{
					if ( strtolower($data['modified']) != 'datetime' )
					{
						self::$error = $tblName.'Table.php $hasModified = \''.$tblClass->hasModified.'\';<br/>But SQL Type of '.$tblClass->table.'.modified is <strong>'.strtolower($data['modified']).'</strong>';
						return false;
					}
				}
				if ( $tblClass->hasModified == 'timestamp' )
				{
					if ( strtolower($data['modified']) != 'timestamp' )
					{
						self::$error = $tblName.'Table.php $hasModified = \''.$tblClass->hasModified.'\';<br/>But SQL Type of '.$tblClass->table.'.modified is <strong>'.strtolower($data['modified']).'</strong>';
						return false;
					}
				}
				if ( $tblClass->hasModified == 'integer' )
				{
					if ( strtolower($data['created']) != 'int' )
					{
						self::$error = $tblName.'Table.php $hasModified = \''.$tblClass->hasModified.'\';<br/>But SQL Type of '.$tblClass->table.'.modified is <strong>'.strtolower($data['modified']).'</strong>';
						return false;
					}
				}
				else
				{
					self::$error = $tblName.'Table.php $hasModified = \''.$tblClass->hasModified.'\';<br/> Is a wrong type! SQL Type of '.$tblClass->table.'.modified is <strong>'.strtolower($data['modified']).'</strong><br/>Allowed types are \'datetime\', \'timestamp\' and \'integer\'';
					return false;
				}
			}
			else if ( is_array($tblClass->hasModified) )
			{
				$name	= array_keys($tblClass->hasModified);
				$name	= isset($name[0]) ? $name[0] : null;
				$type	= array_values($tblClass->hasModified);
				$type	= isset($type[0]) ? $type[0] : null;

				if ( !in_array($name, $names) )
				{
					self::$error = $tblName.'Table.php $hasModified = array(\''.$name.'\' => \''.$type.'\');<br/>Field <strong>'.$name.'</strong> does not exist in sql table '.$tblClass->table;
					return false;
				}
				if ( $type == 'datetime' )
				{
					if ( strtolower($data[$name]) != 'datetime' )
					{
						self::$error = $tblName.'Table.php $hasModified = array(\''.$name.'\' => \''.$type.'\');<br/>But SQL Type of '.$tblClass->table.'.'.$name.' is <strong>'.strtolower($data['modified']).'</strong>';
						return false;
					}
				}
				if ( $type == 'timestamp' )
				{
					if ( strtolower($data[$name]) != 'timestamp' )
					{
						self::$error = $tblName.'Table.php $hasModified = array(\''.$name.'\' => \''.$type.'\');<br/>But SQL Type of '.$tblClass->table.'.'.$name.' is <strong>'.strtolower($data['modified']).'</strong>';
						return false;
					}
				}
				if ( $type == 'integer' )
				{
					if ( strtolower($data[$name]) != 'int' )
					{
						self::$error = $tblName.'Table.php $hasModified = array(\''.$name.'\' => \''.$type.'\');<br/>But SQL Type of '.$tblClass->table.'.'.$name.' is <strong>'.strtolower($data['modified']).'</strong>';
						return false;
					}
				}
				else
				{
					self::$error = $tblName.'Table.php $hasModified = array(\''.$name.'\' => \''.$type.'\');<br/>Is a wrong type ! SQL Type if '.$tblClass->table.'.'.$name.' is <strong>'.strtolower($data['modified']).'</strong><br/>Allowed types are \'datetime\', \'timestamp\' and \'integer\'';
					return false;
				}
			}
			else
			{
				self::$error = $tblName.'Table.php $hasModified has a wrong format.<br/>Allowed values: $hasModified = \'sql_type\' or $hasModified = array(\'field_name\' => \'sql_type\')';
				return false;
			}
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
			// Get Class Name (default is to camelCase the sql table
			$className	= (isset($options['class'])) ? $options['class'] : \Strings::camelCase($options['table'], true);

			// Is Plugin?
			$plugin		= (isset($options['plugin'])&& strlen($options['plugin'])) ? $options['plugin'] : null;

			// Is Core?
			$core		= (isset($options['core']) && $options['core']) ? true : false;

			
			
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
			if ( isset($options['recursive']) && !is_array($options['recursive']) )
			{
				// Check if recursive is true or false
				if ( $options['recursive'] !== true && $options['recursive'] !== false )
				{
					self::$error = 'Wrong value specified in: '.$tbl_name.'Table $'.$type.'[\''.$alias.'\'][\'recursive\'] Can only be true or false or an array of specific Relations to follow';
					return false;
				}

				if ($core) {
					$path	= CORE_TABLE.DS.$className.'Table.php';
				}
				else {
					$path	= ($plugin) ? USR_PLUGINS_PATH.DS.$plugin.DS.'tables'.DS.$className.'Table.php' : USR_TABLES_PATH.DS.$className.'Table.php';
				}
				
				// File exists?
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
					self::$error .= '<br/>If it is a core or plugin table you will have to specifiy either \'core\' = true or \'plugin\' = \'name_of_plugin\'';
					return false;
				}
				// TODO:!!! validate correct alias namings of the file itself
			}

			// If recursive relation is limited to follow only specific relations, we have to check if those
			// specific relation(s) exist in the other class exists
			if ( isset($options['recursive']) && is_array($options['recursive']) )
			{
				foreach ($options['recursive'] as $relType => $aliasNames)
				{
					// wrong relation name
					if ( !($relType == 'hasOne' || $relType == 'hasMany' || $relType == 'belongsTo' || $relType == 'hasAndBelongsToMany') )
					{
						self::$error = $tbl_name.'::'.$type.'[\''.$alias.'\'][\'recursive\'] = array(\'<strong style="color:red;">'.$relType.'\'</strong> => ...)<br/>';
						self::$error.= $relType.'is not a valid relation<br/>';
						self::$error.= 'Specify one of the following: \'hasOne\', \'hasMany\', \'belongsTo\' or \'hasAndBelongsToMany\'';
						return false;
					}
					
					// Check if all specified aliasNames exist in the corresponding table in that particular relation
					foreach ($aliasNames as $aName)
					{
						if ($core) {
							$recTable = \Loader::LoadCoreTable($className);
						} else if ($plugin) {
							$recTable = \Loader::LoadPluginTable($className, $plugin);
						} else {
							$recTable = \Loader::LoadTable($className);
						}
						
						// check if the relation to follow has been defined as array
						if ( !is_array($recTable->$relType) || !count($recTable->$relType) )
						{
							self::$error = $tbl_name.'::'.$type.'[\''.$alias.'\'][\'recursive\'] = array(\'<strong style="color:red;">'.$relType.'\'</strong> => ...)<br/>';
							self::$error.= 'has been set to follow, but <strong>'.$relType.'</strong> is not properly defined in '.$className;
							return false;
						}
						
						// Check if the alias to follow has been defined in the relation Type
						if ( !in_array($aName, array_keys($recTable->$relType)) )
						{
							self::$error = $tbl_name.'::'.$type.'[\''.$alias.'\'][\'recursive\'] = array(\''.$relType.'\' => \'<strong style="color:red;">'.$aName.'</strong>\')<br/>';
							self::$error.= 'has been set to follow, but <strong>'.$aName.'</strong> is not set in '.$className.'::'.$relType;
							return false;
						}
					}
				}
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
		// Validate allowed fields
		$allOptions	= array('table', 'class', 'core', 'plugin', 'primaryKey', 'foreignKey', 'fields', 'subQueries', 'condition', 'recursive', 'dependent');
		$setFields	= array_keys($options);
		// TODO:!!!
		debug('IMPLEMENT ME IN Validate05Table.php -see __checkHasMany for example');

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
		// Validate allowed fields
		$allOptions	= array('table', 'class', 'core', 'plugin', 'foreignKey', 'fields', 'subQueries', 'condition', 'order', 'limit', 'flatten', 'recursive', 'dependent');
		foreach (array_keys($options) as $opt)
		{
			if ( !in_array($opt, $allOptions) )
			{
				self::$error = 'Invalid Option: <strong>'.$opt.'</strong> in '.$tbl_name.'Table.php $hasMany[\''.$alias.'\']';
				return false;
			}
		}

		if ( isset($options['flatten']) && ($options['flatten'] !== true && $options['flatten'] !== false) )
		{
				self::$error = 'Invalid Value for: <strong>flatten</strong> in '.$tbl_name.'Table.php $hasMany[\''.$alias.'\'][\'flatten\'] - can only be true or false';
				return false;
		}
		if ( isset($options['dependent']) && ($options['dependent'] !== true && $options['dependent'] !== false) )
		{
				self::$error = 'Invalid Value for: <strong>dependent</strong> in '.$tbl_name.'Table.php $hasMany[\''.$alias.'\'][\'dependent\'] - can only be true or false';
				return false;
		}

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
		// Validate allowed fields
		$allOptions	= array('table', 'class', 'core', 'plugin', 'primaryKey', 'foreignKey', 'fields', 'subQueries', 'condition', 'recursive');
		foreach (array_keys($options) as $opt)
		{
			if ( !in_array($opt, $allOptions) )
			{
				self::$error = 'Invalid Option: <strong>'.$opt.'</strong> in '.$tbl_name.'Table.php $belongsTo[\''.$alias.'\']';
				return false;
			}
		}

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
		debug('Implement me in Validate05Tables __checkHabtm');
		// Validate allowed fields
		$allOptions	= array('table', 'class', 'core', 'plugin', 'primaryKey', 'foreignKey', 'fields', 'subQueries', 'condition', 'recursive');
		foreach (array_keys($options) as $opt)
		{
			if ( !in_array($opt, $allOptions) )
			{
				self::$error = 'Invalid Option: <strong>'.$opt.'</strong> in '.$tbl_name.'Table.php $hasAndBelongsToMany[\''.$alias.'\']';
				return false;
			}
		}

		// TODO: add validation for hasAndBelongsToMany relation on foreignKeys
		return true;
	}



}
