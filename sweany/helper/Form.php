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

	/********************************************************* P R E P A R E   F U N C T I O N S *********************************************************/

	public static function makeSelBoxArr($array, $nameKey, $valKey = null, $defName = null, $defVal = null)
	{
		$data = array();
		$size = count($array);

		// Add a default entry to the beginning of the array
		if ( !is_null($defName) && !is_null($defVal) ) {
			$data[$defVal] = $defName;
		} else if ( !is_null($defName) && is_null($defVal) ) {
			$data[] = $defName;
		}

		// Loop through
		foreach ($array as $el)	{
			// determine data type
			if ( is_array($el) ) {
				$key = $valKey ? $el[$valKey] : null;
				$val = $el[$nameKey];
			} else if ( is_object($el) )	{
				$key = $valKey ? $el->$valKey : null;
				$val = $el->$nameKey;
			} else {
				$key = null;
				$val = $el;
			}

			// fill array
			if ($key) {
				$data[$key] = $val;
			} else {
				$data[] = $val;
			}
		}

		return $data;
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
	* array('val' => 'display name')
	*/
	public static function selectBox($name, $data = array(), $default_value = null, $options = array())
	{
		$formName	= self::$formName;

		$rows			= '';
		$selected		= '';
		$style			= self::getError($name) ? self::$css_error_form : '';
		$default_value	= self::_getDefaultValue($name, $default_value);
		$options		= self::_getOptions($options);

		foreach ($data as $row_val => $row_name)
		{
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

		// TODO: If reading POST array, we need to make sure this works... see admin/languages, if there a multiple translations
		$name		= (strpos($name, '[') !== false) ? $formName.$name : $formName.'['.$name.']';
		return	'<input '.$options.' '.$style.' '.$options.' type="text" name="'.$name.'" value="'.$def_val.'" />';
	}

	public static function inputFieldHinted($name, $hint = null, $default_value = null, $options = array())
	{
		$formName	= self::$formName;
		$style		= self::getError($name) ? self::$css_error_form : '';
		$def_val	= self::_getDefaultValue($name, $default_value);
		$options	= self::_getOptions($options);

		$def_val	= ($hint && !strlen($def_val)) ? $hint : $def_val;
		$hint_script= ($hint) ? 'onblur="if (this.value == \'\') { this.value = \''.$hint.'\'; }" onfocus="if (this.value == \''.$hint.'\') { this.value = \'\'; }"' : '';

		// TODO: If reading POST array, we need to make sure this works... see admin/languages, if there a multiple translations
		$name		= (strpos($name, '[') !== false) ? $formName.$name : $formName.'['.$name.']';

		return	'<input '.$options.' '.$style.' '.$options.' type="text" name="'.$name.'" placeholder="'.$hint.'" value="'.$def_val.'" '.$hint_script.'/>';
	}


	/**
	 *
	 * @param	string	$name			Name of the input field (also used for the id)
	 * @param	string	$default_value	Default value to display
	 * @param	string	$query_url		Path of the url to query against
	 * @param	string	$post_var_name	Name of the variable to send via post to the above url
	 * @param	mixed[]	$options		Options for the inputField
	 * @return	string
	 */
	public static function liveSearch($name, $default_value = null, $query_url, $post_var_name, $options = array())
	{
		// Include Ajax functionality
		Javascript::addFile('/sweany/ajax.js');

		// Build parameters for liveSearch Function
		$js_func	= "liveSearch('$query_url', '$post_var_name='+document.getElementById('$name').value, '$name', 'liveSearchResultId');";

		// Add Keyup and Id options to input field
		$options['onkeyup'] = $js_func;
		$options['id']		= $name;

		$inputField	= self::inputField($name, $default_value, $options);
		$results	= '<div class="liveSearchResults" id="liveSearchResultId" style="visibility.hidden; display:none; position:absolute;"></div>';

		return $inputField.$results;
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

	public static function editor($name, $default_value = null, $cols = 50, $rows = 10, $options = array(), $icon_base_url = '/sweany/bbcode/img')
	{
		// Include Editor functionality
		Javascript::addFile('/sweany/bbcode/js/bbeditor.js');

		// If no Id has been specified, use the name with time() to be unique
		$id	 = isset($options['id']) ? $options['id'] : $name.time();

		$bar = '<a title="bold text" onClick="insertBBTag(\''.$id.'\',\'[b]\',\'[/b]\');"><img class="bbCodeIcons" src="'.$icon_base_url.'/text/bold.png" alt="bold" /></a>';
		$bar.= '<a title="italic text" onClick="insertBBTag(\''.$id.'\',\'[i]\',\'[/i]\');"><img class="bbCodeIcons" src="'.$icon_base_url.'/text/italic.png" alt="italic" /></a>';
		$bar.= '<a title="underlined text" onClick="insertBBTag(\''.$id.'\',\'[u]\',\'[/u]\');"><img class="bbCodeIcons" src="'.$icon_base_url.'/text/underline.png" alt="underline" /></a>';
		$bar.= '<a title="striked through text" onClick="insertBBTag(\''.$id.'\',\'[s]\',\'[/s]\');"><img class="bbCodeIcons" src="'.$icon_base_url.'/text/strike.png" alt="strike through" /></a>';
		$bar.= '<a title="insert link" onClick="document.getElementById(\''.$id.'\').value+=add_link();"><img class="bbCodeIcons" src="'.$icon_base_url.'/text/link.png" alt="link" /></a>';
		$bar.= '<a title="insert picture" onClick="document.getElementById(\''.$id.'\').value+=add_img();"><img class="bbCodeIcons" src="'.$icon_base_url.'/text/image.png" alt="picture" /></a>';
		$bar.= '<a title="insert code block" onClick="insertBBTag(\''.$id.'\',\'[code]\',\'[/code]\');"><img class="bbCodeIcons" src="'.$icon_base_url.'/text/code.png" alt="code block" /></a>';

		$bar.= '<a style="float:left;">&nbsp;&nbsp;|&nbsp;&nbsp;</a>';

		$bar.= '<a title="smile" onClick="insertBBTag(\''.$id.'\',\':)\',\'\');"><img class="bbCodeIcons" src="'.$icon_base_url.'/smiley/smile.png" alt="smile" /></a>';
		$bar.= '<a title="grin" onClick="insertBBTag(\''.$id.'\',\':D\',\'\');"><img class="bbCodeIcons" src="'.$icon_base_url.'/smiley/grin.png" alt="grin" /></a>';
		$bar.= '<a title="roll eyes" onClick="insertBBTag(\''.$id.'\',\':roll:\',\'\');"><img class="bbCodeIcons" src="'.$icon_base_url.'/smiley/roll.png" alt="roll eyes" /></a>';
		$bar.= '<a title="unhappy" onClick="insertBBTag(\''.$id.'\',\':(\',\'\');"><img class="bbCodeIcons" src="'.$icon_base_url.'/smiley/unhappy.png" alt="unhappy" /></a>';
		$bar.= '<a title="show tongue" onClick="insertBBTag(\''.$id.'\',\':p\',\'\');"><img class="bbCodeIcons" src="'.$icon_base_url.'/smiley/tongue.png" alt="show tongue" /></a>';
		$bar.= '<a title="cry" onClick="insertBBTag(\''.$id.'\',\':cry:\',\'\');"><img class="bbCodeIcons" src="'.$icon_base_url.'/smiley/cry.png" alt="cry" /></a>';
		$bar.= '<a title="blush" onClick="insertBBTag(\''.$id.'\',\':red:\',\'\');"><img class="bbCodeIcons" src="'.$icon_base_url.'/smiley/red.png" alt="blush" /></a>';
		$bar.= '<a title="confused" onClick="insertBBTag(\''.$id.'\',\':confuse:\',\'\');"><img class="bbCodeIcons" src="'.$icon_base_url.'/smiley/confuse.png" alt="confused" /></a>';

		// Add the id to the options, if no custom id was specified and get the text area source
		if ( !isset($options['id']) ) {
			$options['id'] = $id;
		}

		$textarea = self::textArea($name, $cols, $rows, $default_value, $options);

		$editor = '<div class="bbEditorContainer">'.
						'<div class="bbEditorIconBox" style="height:20px;">'.
							$bar.
						'</div>'.
						'<div class="bbEditorTextArea">'.
							$textarea.
						'</div>'.
					'</div>';

		return $editor;
	}

	/**
	 *
	 * @param	string		$name1				Name of input field1
	 * @param	string		$name2				Name of input field2
	 * @param	timestring	$date1_default		Default Date timestring to show for field1 | format: YYYY-mm-dd [ date('Y-m-d', $timestamp) ]
	 * @param	timestring	$date2_default		Default Date timestring to show for field2 | format: YYYY-mm-dd [ date('Y-m-d', $timestamp) ]
	 * @param	int			$min_shown_year		Left Year intervall limit to show (do not show years below that limit)
	 * @param	int			$max_shown_year		Right Year intervall limit to show (do not show years above that limit)
	 * @param	timestring	$min_allowed_date	Do not allow dates to be picked below this date | format: YYYY-mm-dd [ date('Y-m-d', $timestamp) ]
	 * @param	timestring	$max_allowed_date	Do not allow dates to be picked above this date | format: YYYY-mm-dd [ date('Y-m-d', $timestamp) ]
	 * @return
	 */
	public static function dateTimespanPicker($name1, $name2, $date1_default, $date2_default, $min_shown_year , $max_shown_year , $min_allowed_date, $max_allowed_date)
	{
		require_once(ROOT.DS.'www'.DS.'sweany'.DS.'calendar'.DS.'classes'.DS.'tc_calendar.php');

		// Include Calendar functionality
		Javascript::addFile('/sweany/calendar/js/calendar.js');

		$myCalendar = new tc_calendar($name1, true, false);
		$myCalendar->setIcon("/sweany/calendar/img/iconCalendar.gif");
		$myCalendar->setDate(date('d', strtotime($date1_default)), date('m', strtotime($date1_default)), date('Y', strtotime($date1_default)));
		$myCalendar->setPath("/sweany/calendar/");
		$myCalendar->setYearInterval($min_shown_year, $max_shown_year);
		$myCalendar->dateAllow($min_allowed_date, $max_allowed_date, false);
		$myCalendar->setAlignment('left', 'bottom');
		$myCalendar->setDatePair($name1, $name2, $date2_default);
		$myCalendar->startMonday(true);
		//$myCalendar->writeScript();
		$cal1 = $myCalendar->returnScript();

		$myCalendar = new tc_calendar($name2, true, false);
		$myCalendar->setIcon("/sweany/calendar/img/iconCalendar.gif");
		$myCalendar->setDate(date('d', strtotime($date2_default)), date('m', strtotime($date2_default)), date('Y', strtotime($date2_default)));
		$myCalendar->setPath("/sweany/calendar/");
		$myCalendar->setYearInterval($min_shown_year, $max_shown_year);
		$myCalendar->dateAllow($min_allowed_date, $max_allowed_date, false);

		$myCalendar->setAlignment('left', 'bottom');
		$myCalendar->setDatePair($name1, $name2, $date1_default);
		$myCalendar->startMonday(true);
		//$myCalendar->writeScript();
		$cal2 = $myCalendar->returnScript();

		return $cal1.$cal2;
	}

	public static function datePicker($name, $default_date = null, $min_shown_year = 1970 , $max_shown_year = 2050 , $min_allowed_date = '2008-01-01', $max_allowed_date)
	{
		require_once(ROOT.DS.'www'.DS.'sweany'.DS.'calendar'.DS.'classes'.DS.'tc_calendar.php');

		// Include Calendar functionality
		Javascript::addFile('/sweany/calendar/js/calendar.js');

		$default_date = $default_date ? $default_date : TimeHelper::date('Y-m-d', time());

		$myCalendar = new tc_calendar($name, true, false);
		$myCalendar->setIcon("/sweany/calendar/img/iconCalendar.gif");
		$myCalendar->setPath("/sweany/calendar/");
		$myCalendar->setDate(date('d', strtotime($default_date)), date('m', strtotime($default_date)), date('Y', strtotime($default_date)));
		$myCalendar->setYearInterval($min_shown_year, $max_shown_year);		// patu edit: only up to this year
		$myCalendar->dateAllow($min_allowed_date, $max_allowed_date, false);// patu edit: not greater than today
		$myCalendar->startMonday(true);
		$myCalendar->showWeeks(true);

		return $myCalendar->returnScript();
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
