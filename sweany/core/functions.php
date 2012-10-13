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
 * basic functions
 */




/**
 *
 *	t() Function
 *
 *	@param	string	$text			Text to translate
 *	@param	mixed[]	$placeholder	Placeholders not to translate
 *	@return	string	$text			Translated text
 *
 *	$text can contain placeholders that do not need
 *		  to be translated.
 *		  e.g.: 'hallo !variable'	!variable will be replaced by placeholders unescaped
 *		  e.g.: 'hallo @variable'	@variable will be replaced by placeholders and escaped html safe
 *		  e.g.: 'hallo %variable'	%variable will be replaced by placeholders and escaped html safe with placeholder <em> tag
 *
 *	$placeholder is an associative array
 *				 in the following format
 *		array('!variable' => 'replaceText')	# !	do not escape
 *		array('@variable' => 'replaceText')	# @	html safe escape
 *		array('%variable' => 'replaceText')	# %	html safe escape plus placeholder <em> tag
 *
 *	Note:
 *	Defining the function itself in an if/else statement
 *	is by way faster, then doing the if/else inside the function,
 *	as in this case the if/else only has to be evaluated once,
 *	instead of at every call.
 *
 *
 *	Usage:
 *	t('hallo @name, how are you today? %credit', array('@name' => 'Peter', '%credit' => 'The Team'));
 *
 *
 */
if ( $GLOBALS['LANGUAGE_SQL_ENABLE'] )
{
	function t($text, $placeholder = array())
	{
		// TODO: Do translations
		$sanitize = function(&$value, $key) {
			if ($key[0]=='@') {
				$value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
			}
			else if ($key[0]=='%') {
				$value = '<em class="placeholder">'.htmlspecialchars($value, ENT_QUOTES, 'UTF-8').'</em>';
			}
		};
		array_walk($placeholder, $sanitize);
		return str_replace(array_keys($placeholder), array_values($placeholder), $text);
	}
}
else
{
	function t($text, $placeholder = array())
	{
		$sanitize = function(&$value, $key) {
			if ($key[0]=='@') {
				$value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
			}
			else if ($key[0]=='%') {
				$value = '<em class="placeholder">'.htmlspecialchars($value, ENT_QUOTES, 'UTF-8').'</em>';
			}
		};
		array_walk($placeholder, $sanitize);
		return str_replace(array_keys($placeholder), array_values($placeholder), $text);
	}
}


/**
 * print_r improvement for html
 */
function debug($arr)
{
	echo '<pre>';
	print_r($arr);
	echo '</pre>';
}
function dump($arr)
{
	echo '<pre>';
	var_dump($arr);
	echo '</pre>';
}
