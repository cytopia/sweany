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
 * @package		sweany.core.lib
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.7 2012-07-29 13:25
 *
 *
 * Form Helper
 */
Class Form
{
	/********************************************************* VARIABLES *********************************************************/

	/*
	 * CSS Style declaration for
	 *  + the error text (usually red)
	 *  + the form elements (usually red border)
	 *
	 * The settings can be adjusted in the usr/conf.php file
	 */
	private static $css_error_form	= null;
	private static $css_error_text	= null;


	/*
	 * Keeps track of errors in various forms
	 */
	private static $formErrors	= array();

	/*
	 * Every form element is created like this:
	 *    name="formName[fieldName]"
	 *
	 * which will return a $_POST array like
	 *    $_POST[formName][fieldName]
	 */
	private static $formName	= null;

	/*
	 * PHP File Upload error codes and
	 * human readable description
	 */
	public static $fileError = array(
		0 => 'There is no error, the file uploaded with success.',
		1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
		2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
		3 => 'The uploaded file was only partially uploaded.',
		4 => 'No file was uploaded.',
		6 => 'Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.',
		7 => 'Failed to write file to disk. Introduced in PHP 5.1.0.',
		8 => 'A PHP extension stopped the file upload.',
	);



	/********************************************************* CONSTRUCT *********************************************************/

	/**
	 *
	 * Assign global error styles
	 * (defined in usr/conf.php)
	 *
	 * This function is called at the end of the file
	 */
 	public static function init()
	{
		self::$css_error_form	= 'style="'.$GLOBALS['DEFAULT_FORM_ELEMENT_ERR_CSS'].'"';
		self::$css_error_text	= '<div style="'.$GLOBALS['DEFAULT_FORM_TEXT_ERR_CSS'].'">%s</div>';
	}


	/********************************************************* A C T I O N   F U N C T I O N S *********************************************************/

	public static function setError($id, $errText)
	{
		self::$formErrors[self::$formName][$id] = $errText;

	}

	public static function getError($id, $color = null)
	{
		if ( isset(self::$formErrors[self::$formName][$id]) )
		{
			return sprintf(self::$css_error_text, self::$formErrors[self::$formName][$id]);
		}
		else
		{
			return false;
		}
	}


	// manually add a value to the post array
	// Used if sending via get, but you want it in the post array
	// to check against auto-validator
	public static function setFormValue($form_name, $id, $value)
	{
		$_POST[$form_name][$id] = $value;
	}

	public static function getValue($id)
	{
		return (isset($_POST[self::$formName][$id])) ? $_POST[self::$formName][$id] : null;
	}


	/**
	 * Get values of several fields
	 *
	 * @param array $fields
	 * @return array values
	 */
	public static function getValues($fields)
	{
		$size	= count($fields);
		$values	= array();

		for ($i=0; $i<$size; $i++)
		{
			$values[$i] = (isset($_POST[self::$formName][$fields[$i]])) ? $_POST[self::$formName][$fields[$i]] : null;
		}
		return $values;
	}

	public static function fieldIsSet($id)
	{
		return (isset($_POST[self::$formName][$id]));
	}
	public static function getFile($id)
	{
		return (isset($_FILES[self::$formName][$id])) ? $_FILES[self::$formName][$id] : null;
	}

	public static function isSubmitted($form_name)
	{
		if ( isset($_POST[$form_name]) )
		{
			self::$formName = $form_name;
			return true;
		}
		return false;
	}

	// used to manually check for errors
	public static function isValid($form_name)
	{
		if ( isset(self::$formErrors[$form_name]))
			return false;
		else
			return true;
	}





	/********************************************************* F O R M *********************************************************/

	public static function start($name, $action = null, $options = array())
	{
		self::$formName = $name;
		$options		= self::_getOptions($options);

		return '<form '.$options.' name="'.$name.'" method="post" action="'.$action.'" >';
	}

	public static function end()
	{
		return '</form>';
	}

	public static function submitButton($name, $value, $options = array())
	{
		$formName	= self::$formName;
		$options	= self::_getOptions($options);

		return '<input '.$options.' type="submit" name="'.$formName.'['.$name.']" value="'.$value.'" />';
	}
	public static function imgSubmitButton($name, $alt, $src, $options = array())
	{
		$formName	= self::$formName;
		$options	= self::_getOptions($options);

		return '<input '.$options.' type="image" name="'.$formName.'['.$name.']" src="'.$src.'" alt="'.$alt.'" />';
	}
	public static function resetButton($name, $value, $options = array())
	{
		$formName	= self::$formName;
		$options	= self::_getOptions($options);
		return '<input '.$options.' type="reset" name="'.$formName.'['.$name.']" value="'.$value.'" />';
	}

	/********************************************************* F I E L D   S E T  *********************************************************/

	public static function fieldSetStart($legend = null, $field_options = array(), $legend_options = array())
	{
		if ($legend)
		{
			$legend_options = self::_getOptions($legend_options);
			$title			= ' title="'.$legend.'"';
			$legend			= '<legend '.$legend_options.'>'.$legend.'</legend>';
		}
		else
		{
			$title	= '';
			$legend	= '';
		}

		$field_options = self::_getOptions($field_options);

		return '<fieldset '.$field_options.' '.$title.'>'.$legend;
	}

	public static function fieldSetEnd()
	{
		return '</fieldset>';
	}


	/********************************************************* F O R M   E L E M E N T S *********************************************************/

	public static function label($id, $label, $options = array())
	{
		$options	= self::_getOptions($options);
		$formName	= self::$formName;
		return '<label for="'.$formName.'['.$id.']" '.$options.'>'.$label.'</label>';
	}

	/**
	* data[]['id']
	* data[]['value']
	*/
	public static function selectBox($name, $data = array(), $default_value = null, $options = array())
	{
		$formName	= self::$formName;

		$rows			= '';
		$selected		= '';
		$style			= self::getError($name) ? self::$css_error_form : '';
		$default_value	= self::_getDefaultValue($name, $default_value);
		$options		= self::_getOptions($options);

		foreach ($data as $row)
		{
			$row_val	 = $row['id'];
			$row_name	 = $row['value'];
			$selected	 = ( $row_val == $default_value ) ? 'selected' : '';
			$rows 		.= '<option value="'.$row_val.'" '.$selected.'>'.$row_name.'</option>';
		}

		return '<select '.$options.' '.$style.' name="'.$formName.'['.$name.']" size="1">'.$rows.'</select>';
	}

	public static function radioButton($name, $value, $checked = false, $options = array())
	{
		$style		= self::getError($name) ? self::$css_error_form : '';
		$options	= self::_getOptions($options);

		$checked	= self::_isChecked($name, $value, $checked);
		$checked	= ($checked) ? 'checked="checked"' : '';

		return '<input '.$options.' '.$style.' type="radio" name="'.self::__encodeFieldNameArray($name).'" value="'.$value.'" '.$checked.' />';
	}

	public static function checkBox($name, $value, $checked = false, $options = array())
	{
		$style		= self::getError($name) ? self::$css_error_form : '';
		$options	= self::_getOptions($options);

		$checked	= self::_isChecked($name, $value, $checked);
		$checked	= ($checked) ? 'checked="checked"' : '';

		return '<input '.$options.' '.$style.' type="checkbox" name="'.self::__encodeFieldNameArray($name).'" value="'.$value.'" '.$checked.' />';
	}


	public static function inputField($name, $default_value = null, $options = array())
	{
		$formName	= self::$formName;
		$style		= self::getError($name) ? self::$css_error_form : '';
		$def_val	= self::_getDefaultValue($name, $default_value);
		$options	= self::_getOptions($options);

		return	'<input '.$options.' '.$style.' '.$options.' type="text" name="'.$formName.'['.$name.']" value="'.$def_val.'" />';
	}

	public static function inputFieldHinted($name, $hint = null, $default_value = null, $options = array())
	{
		$formName	= self::$formName;
		$style		= self::getError($name) ? self::$css_error_form : '';
		$def_val	= self::_getDefaultValue($name, $default_value);
		$options	= self::_getOptions($options);

		$def_val	= ($hint && !strlen($def_val)) ? $hint : $def_val;
		$hint_script= ($hint) ? 'onblur="if (this.value == \'\') { this.value = \''.$hint.'\'; }" onfocus="if (this.value == \''.$hint.'\') { this.value = \'\'; }"' : '';


		return	'<input '.$options.' '.$style.' '.$options.' type="text" name="'.$formName.'['.$name.']" placeholder="'.$hint.'" value="'.$def_val.'" '.$hint_script.'/>';
	}

	public static function passwordField($name, $options = array())
	{
		$formName	= self::$formName;
		$style		= self::getError($name) ? self::$css_error_form : '';
		$options	= self::_getOptions($options);

		return	'<input '.$options.' '.$style.' '.$options.' type="password" name="'.$formName.'['.$name.']" />';
	}


	public static function textArea($name, $cols = 50, $rows = 10, $default_value = NULL, $options = array())
	{
		$formName	= self::$formName;
		$style		= self::getError($name) ? self::$css_error_form : '';
		$def_val	= self::_getDefaultValue($name, $default_value);
		$options	= self::_getOptions($options);

		return '<textarea '.$options.' '.$style.' name="'.$formName.'['.$name.']" cols="'.$cols.'" rows="'.$rows.'">'.$def_val.'</textarea>';
	}

	public static function inputHidden($name, $value, $options = array())
	{
		$formName	= self::$formName;
		$options	= self::_getOptions($options);
		return '<input '.$options.' type="hidden" name="'.$formName.'['.$name.']" value="'.$value.'" />';
	}

	/**
	 * max_size in kilobyte
	 */
	public static function fileField($name, $max_kbytes = 100, $options = array())
	{
		$formName	= self::$formName;
		$options	= self::_getOptions($options);
		$max_kbytes	= ($max_kbytes*1024); // use kilobytes instead of bytes

		$field 	 = '<input type="hidden" name="MAX_FILE_SIZE" value="'.$max_kbytes.'" />';
		$field	.= '<input type="file" name="'.$formName.'['.$name.']" '.$options.' />';
		return $field;
	}

	public static function button($name, $content, $options = array())
	{
		$formName	= self::$formName;
		$options	= self::_getOptions($options);

		return '<button name="'.$formName.'['.$name.']" '.$options.' >'.$content.'</button>';
	}

	/********************************************************* PRIVATE FUNCTIONS  *********************************************************/

	/**
	 * _getOptions()
	 *
	 * prepares html inline options in the form:
	 * what="value"
	 */
	private static function _getOptions($options = array())
	{
		return (is_array($options)) ? implode('', array_map( create_function('$key, $val', 'return " ".$key."=\"".$val."\"";'), array_keys($options), array_values($options))) : '';
	}

	/**
	 * _isChecked()
	 *
	 * Find out if a checkbox or a radio button is checked
	 */
	private static function _isChecked($name, $value, $default_checked = null)
	{
		if ( !self::isSubmitted(self::$formName) )
			return $default_checked;


		// check if array is given ( name[x] )
		if ( self::__postedFieldIsArray($name) )
		{
			$check_value = self::__getArrayFieldPostValue($name);
		}
		else if ( isset($_POST[self::$formName][$name]) )
		{
			$check_value = $_POST[self::$formName][$name];
		}
		else
		{
			return false;
		}
		return ( $check_value == $value );
	}

	/**
	 * _getDefaultValue()
	 *
	 * + Fills fields and textboxes with a default value
	 * + Pre-selects checkboxes default value
	 */
	private static function _getDefaultValue($name, $default_value = null)
	{
		// check if array is given ( formName[fieldName[x]] )
		if ( self::__postedFieldIsArray($name) )
		{
			$value	= self::__getArrayFieldPostValue($name);
		}
		// normal: ( formName[fieldName] )
		else if ( isset($_POST[self::$formName][$name]) )
		{
			$value = $_POST[self::$formName][$name];
		}
		// pre-set default value
		else if ( $default_value )
		{
			$value = $default_value;
		}
		// nothing
		else
		{
			$value = '';
		}
		return $value;
	}


	/********************************************************* PRIVATE PRIVATE FUNCTIONS  *********************************************************/

	/**
	* __encodeFieldNameArray()
	*
	* Encode from name="form_name[field_name[id]]"
	*          to name="form_name[field_name][id]"
	*
	*/
	private static function __encodeFieldNameArray($name)
	{
		if ( strpos($name, '[') !== false && strpos($name, ']') !== false )
		{
			$tmp	= explode('[', $name);
			$name	= $tmp[0];
			$tmp	= explode(']', $tmp[1]);
			$index	= $tmp[0];
			return self::$formName.'['.$name.']['.$index.']';
		}
		else
		{
			return self::$formName.'['.$name.']';
		}
	}




	/**
	 * __postedFieldIsArray()
	 *
	 * checks if a given input (field, radios, textarea, ...)
	 * are normal or arrays e.g. name="test" or name="test[]"
	 */
	private static function __postedFieldIsArray($name)
	{
		if ( strpos($name, '[') !== false && strpos($name, ']') !== false )
		{
			$tmp	= explode('[', $name);
			$name	= $tmp[0];
			$tmp	= explode(']', $tmp[1]);
			$index	= $tmp[0];
			return isset($_POST[self::$formName][$name][$index]);
		}
		return false;
	}

	/**
	 * __getArrayFieldPostValue()
	 *
	 * if field names are arrays, it will return the values
	 * stored in a php array
	 */
	private static function __getArrayFieldPostValue($name)
	{
		$tmp	= explode('[', $name);
		$name	= $tmp[0];
		$tmp	= explode(']', $tmp[1]);
		$index	= $tmp[0];
		return $_POST[self::$formName][$name][$index];
	}

}
Form::init();
