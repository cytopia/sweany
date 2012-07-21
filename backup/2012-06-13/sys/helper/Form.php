<?php

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
	private $css_error_form	= null;
	private $css_error_text	= null;


	/*
	 * Keeps track of errors in various forms
	 */
	private $formErrors	= array();

	/*
	 * Every form element is created like this:
	 *    name="formName[fieldName]"
	 *
	 * which will return a $_POST array like
	 *    $_POST[formName][fieldName]
	 */
	private $formName	= null;

	/*
	 * PHP File Upload error codes and
	 * human readable description
	 */
	public $fileError = array(
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
	 */
	public function __construct()
	{
		$this->css_error_form	= 'style="'.$GLOBALS['DEFAULT_FORM_ELEMENT_ERR_CSS'].'"';
		$this->css_error_text	= '<div style="'.$GLOBALS['DEFAULT_FORM_TEXT_ERR_CSS'].'">%s</div>';
	}




	/********************************************************* A C T I O N   F U N C T I O N S *********************************************************/

	public function setError($id, $errText)
	{
		$this->formErrors[$this->formName][$id] = $errText;

	}

	public function getError($id, $color = null)
	{
		if ( isset($this->formErrors[$this->formName][$id]) )
		{
			return sprintf($this->css_error_text, $this->formErrors[$this->formName][$id]);
		}
		else
		{
			return false;
		}
	}


	// manually add a value to the post array
	// Used if sending via get, but you want it in the post array
	// to check against auto-validator
	public function setFormValue($form_name, $id, $value)
	{
		$_POST[$form_name][$id] = $value;
	}

	public function getValue($id)
	{
		return (isset($_POST[$this->formName][$id])) ? $_POST[$this->formName][$id] : null;
	}
	public function fieldIsSet($id)
	{
		return (isset($_POST[$this->formName][$id]));
	}
	public function getFile($id)
	{
		return (isset($_FILES[$this->formName][$id])) ? $_FILES[$this->formName][$id] : null;
	}

	public function isSubmitted($form_name)
	{
		if ( isset($_POST[$form_name]) )
		{
			$this->formName = $form_name;
			return true;
		}
		return false;
	}

	// used to manually check for errors
	public function isValid($form_name)
	{
		if ( isset($this->formErrors[$form_name]))
			return false;
		else
			return true;
	}





	/********************************************************* F O R M *********************************************************/

	public function start($name, $action = null, $options = array())
	{
		$this->formName = $name;
		$options		= $this->_getOptions($options);

		return '<form '.$options.' name="'.$name.'" method="post" action="'.$action.'" >';
	}

	public function end()
	{
		return '</form>';
	}

	public function submitButton($name, $value, $options = array())
	{
		$formName	= $this->formName;
		$options	= $this->_getOptions($options);

		return '<input '.$options.' type="submit" name="'.$formName.'['.$name.']" value="'.$value.'" />';
	}
	public function imgSubmitButton($name, $alt, $src, $options = array())
	{
		$formName	= $this->formName;
		$options	= $this->_getOptions($options);

		return '<input '.$options.' type="image" name="'.$formName.'['.$name.']" src="'.$src.'" alt="'.$alt.'" />';
	}
	public function resetButton($name, $value, $options = array())
	{
		$formName	= $this->formName;
		$options	= $this->_getOptions($options);
		return '<input '.$options.' type="reset" name="'.$formName.'['.$name.']" value="'.$value.'" />';
	}

	/********************************************************* F I E L D   S E T  *********************************************************/

	public function fieldSetStart($legend = null, $field_options = array(), $legend_options = array())
	{
		if ($legend)
		{
			$legend_options = $this->_getOptions($legend_options);
			$title			= ' title="'.$legend.'"';
			$legend			= '<legend '.$legend_options.'>'.$legend.'</legend>';
		}
		else
		{
			$title	= '';
			$legend	= '';
		}

		$field_options = $this->_getOptions($field_options);

		return '<fieldset '.$field_options.' '.$title.'>'.$legend;
	}

	public function fieldSetEnd()
	{
		return '</fieldset>';
	}


	/********************************************************* F O R M   E L E M E N T S *********************************************************/

	public function label($id, $label, $options = array())
	{
		$options	= $this->_getOptions($options);
		$formName	= $this->formName;
		return '<label for="'.$formName.'['.$id.']" '.$options.'>'.$label.'</label>';
	}

	/**
	* data[]['id']
	* data[]['value']
	*/
	public function selectBox($name, $data = array(), $default_value = null, $options = array())
	{
		$formName	= $this->formName;

		$rows			= '';
		$selected		= '';
		$style			= $this->getError($name) ? $this->css_error_form : '';
		$default_value	= $this->_getDefaultValue($name, $default_value);
		$options		= $this->_getOptions($options);

		foreach ($data as $row)
		{
			$row_val	 = $row['id'];
			$row_name	 = $row['value'];
			$selected	 = ( $row_val == $default_value ) ? 'selected' : '';
			$rows 		.= '<option value="'.$row_val.'" '.$selected.'>'.$row_name.'</option>';
		}

		return '<select '.$options.' '.$style.' name="'.$formName.'['.$name.']" size="1">'.$rows.'</select>';
	}

	public function radioButton($name, $value, $checked = false, $options = array())
	{
		$style		= $this->getError($name) ? $this->css_error_form : '';
		$options	= $this->_getOptions($options);

		$checked	= $this->_isChecked($name, $value, $checked);
		$checked	= ($checked) ? 'checked="checked"' : '';

		return '<input '.$options.' '.$style.' type="radio" name="'.$this->__encodeFieldNameArray($name).'" value="'.$value.'" '.$checked.' />';
	}

	public function checkBox($name, $value, $checked = false, $options = array())
	{
		$style		= $this->getError($name) ? $this->css_error_form : '';
		$options	= $this->_getOptions($options);

		$checked	= $this->_isChecked($name, $value, $checked);
		$checked	= ($checked) ? 'checked="checked"' : '';

		return '<input '.$options.' '.$style.' type="checkbox" name="'.$this->__encodeFieldNameArray($name).'" value="'.$value.'" '.$checked.' />';
	}


	public function inputField($name, $default_value = null, $options = array())
	{
		$formName	= $this->formName;
		$style		= $this->getError($name) ? $this->css_error_form : '';
		$def_val	= $this->_getDefaultValue($name, $default_value);
		$options	= $this->_getOptions($options);

		return	'<input '.$options.' '.$style.' '.$options.' type="text" name="'.$formName.'['.$name.']" value="'.$def_val.'" />';
	}

	public function passwordField($name, $options = array())
	{
		$formName	= $this->formName;
		$style		= $this->getError($name) ? $this->css_error_form : '';
		$options	= $this->_getOptions($options);

		return	'<input '.$options.' '.$style.' '.$options.' type="password" name="'.$formName.'['.$name.']" />';
	}


	public function textArea($name, $cols = 50, $rows = 10, $default_value = NULL, $options = array())
	{
		$formName	= $this->formName;
		$style		= $this->getError($name) ? $this->css_error_form : '';
		$def_val	= $this->_getDefaultValue($name, $default_value);
		$options	= $this->_getOptions($options);

		return '<textarea '.$options.' '.$style.' name="'.$formName.'['.$name.']" cols="'.$cols.'" rows="'.$rows.'">'.$def_val.'</textarea>';
	}

	public function inputHidden($name, $value, $options = array())
	{
		$formName	= $this->formName;
		$options	= $this->_getOptions($options);
		return '<input '.$options.' type="hidden" name="'.$formName.'['.$name.']" value="'.$value.'" />';
	}

	/**
	 * max_size in kilobyte
	 */
	public function fileField($name, $max_kbytes = 100, $options = array())
	{
		$formName	= $this->formName;
		$options	= $this->_getOptions($options);
		$max_kbytes	= ($max_kbytes*1024); // use kilobytes instead of bytes

		$field 	 = '<input type="hidden" name="MAX_FILE_SIZE" value="'.$max_kbytes.'" />';
		$field	.= '<input type="file" name="'.$formName.'['.$name.']" '.$options.' />';
		return $field;
	}

	public function button($name, $content, $options = array())
	{
		$formName	= $this->formName;
		$options	= $this->_getOptions($options);

		return '<button name="'.$formName.'['.$name.']" '.$options.' >'.$content.'</button>';
	}

	/********************************************************* PRIVATE FUNCTIONS  *********************************************************/

	/**
	 * _getOptions()
	 *
	 * prepares html inline options in the form:
	 * what="value"
	 */
	private function _getOptions($options = array())
	{
		return (is_array($options)) ? implode('', array_map( create_function('$key, $val', 'return " ".$key."=\"".$val."\"";'), array_keys($options), array_values($options))) : '';
	}

	/**
	 * _isChecked()
	 *
	 * Find out if a checkbox or a radio button is checked
	 */
	private function _isChecked($name, $value, $default_checked = null)
	{
		if ( !$this->isSubmitted($this->formName) )
			return $default_checked;


		// check if array is given ( name[x] )
		if ( $this->__postedFieldIsArray($name) )
		{
			$check_value = $this->__getArrayFieldPostValue($name);
		}
		else if ( isset($_POST[$this->formName][$name]) )
		{
			$check_value = $_POST[$this->formName][$name];
		}
		else
		{
			return FALSE;
		}
		return ( $check_value == $value );
	}

	/**
	 * _getDefaultValue()
	 *
	 * + Fills fields and textboxes with a default value
	 * + Pre-selects checkboxes default value
	 */
	private function _getDefaultValue($name, $default_value = null)
	{
		// check if array is given ( formName[fieldName[x]] )
		if ( $this->__postedFieldIsArray($name) )
		{
			$value	= $this->__getArrayFieldPostValue($name);
		}
		// normal: ( formName[fieldName] )
		else if ( isset($_POST[$this->formName][$name]) )
		{
			$value = $_POST[$this->formName][$name];
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
	private function __encodeFieldNameArray($name)
	{
		if ( strpos($name, '[') !== false && strpos($name, ']') !== false )
		{
			$tmp	= explode('[', $name);
			$name	= $tmp[0];
			$tmp	= explode(']', $tmp[1]);
			$index	= $tmp[0];
			return $this->formName.'['.$name.']['.$index.']';
		}
		else
		{
			return $this->formName.'['.$name.']';
		}
	}




	/**
	 * __postedFieldIsArray()
	 *
	 * checks if a given input (field, radios, textarea, ...)
	 * are normal or arrays e.g. name="test" or name="test[]"
	 */
	private function __postedFieldIsArray($name)
	{
		if ( strpos($name, '[') !== false && strpos($name, ']') !== false )
		{
			$tmp	= explode('[', $name);
			$name	= $tmp[0];
			$tmp	= explode(']', $tmp[1]);
			$index	= $tmp[0];
			return isset($_POST[$this->formName][$name][$index]);
		}
		return false;
	}

	/**
	 * __getArrayFieldPostValue()
	 *
	 * if field names are arrays, it will return the values
	 * stored in a php array
	 */
	private function __getArrayFieldPostValue($name)
	{
		$tmp	= explode('[', $name);
		$name	= $tmp[0];
		$tmp	= explode(']', $tmp[1]);
		$index	= $tmp[0];
		return $_POST[$this->formName][$name][$index];
	}

}
?>