<?php
/**
 * Extended CSS (ECSS)
 * Copyright (C) 2012-2012 Patrick Plocke.
 *
 * ECSS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * ECSS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with ECSS. If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright	Copyright 2012-2012, Patrick Plocke <patrick[dot]plocke[at]mailbox[dot]tu-berlin[dot]de>
 * @link		https://github.com/lockdoc/ecss
 * @package		sweany.www (https://github.com/lockdoc/sweany)
 * @author		Patrick Plocke <patrick[dot]plocke[at]mailbox[dot]tu-berlin[dot]de>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.9 2012-08-22 09:37
 *
 * Extended CSS is a preprocessor for a normal css file that will add
 * constants via $ and inheritance as a language construct
 * by the word 'extends'.
 *
 * Available Inheritance types:
 *	+ single
 *	+ multiple
 *	+ recursive (infinite levels)
 *	+ a mix of all the above
 *
 *
 * Constant Declarations
 *  + must be outside a class, id or tag define
 *  + everything between ':'  and  ';' is treated as the value
 *  $bar : this is 111 still 33 the value;
 *  $foo : 100px;
 *
 * ECSS is a side-product by sweany (https://github.com/lockdoc/sweany) and comes
 * under the same license.
 *
 * Note about the code:
 * ---------------------
 * I do know that the code is written kind of inefficiently, but as the whole preprocessing
 * is only for development stage and you will take the preprocessed css file anyway,
 * it does not matter. Apart from the above, writing the code this way is more understandable
 * (at least for me).
 *
 * If you don't like the code, improve it, fork it, print and burn or eat it, the license allows it.
 *
 * CSS Usage examples:
 * ---------------------
 *
 * $myColor :  purple;
 * $myWidth :  500px;
 *
 * .corpColor1 {
 *     color:       $myColor;
 *     font-weight: normal;
 * }
 * #head1 {
 *     width:       $myWidth;
 * }
 *
 * .myBody extends .corpColor1, #head1 {
 *     font-weight: bold;
 * }
 *
 * Inheritance overwriting explanation:
 * After the preprocessor is done, '.myBody' will have all properties of its parents,
 * except the font-weight, which is overwritten by .myBody and will result in 'bold'.
 *
 * This file is shipped with style.css to play around and see how
 * constants, recursion and overwring behaves.
 */




 /*******************************************************************************************
 *
 *                           OPTIONS
 *
 *******************************************************************************************/

/**
 *  Options via $_GET
 *
 *  file=filename.css
 *    Specifies the css file to preprocess
 *
 *  compressed
 *    If appended, the preprocessor will produce
 *    compressed code (removed lines, spaces and tabs)
 *
 *  comment
 *    If appended, the preprocessor will produce
 *    comments for all properties that have inherit their values including from what parent element they got the value.
 *
 *  highlight
 *    Create html highlighted CSS code. Use in combination with comment.
 */



/**
 * Which newline character to use to break
 * lines in preprocessed CSS file?
 * Only applicable for non-compressed css code generation
 *
 * Default: "\n"
 * Others:  "\r", "\n\r"
 */
$new_line = "\n";


/**
 * Specify the left intendation inside
 * CSS class, ids and/or tag defines.
 * Only applicable for non-compressed css code generation
 *
 * Default: "\t"
 */
$intend	= "\t";



/**
 * Highlighting Colors
 */
$clrTag		= '#3F7F7F';	// color of classes, ids and tags
$clrProperty= 'purple';		// color of property names
$clrValue	= 'darkblue';	// color of property values
$clrComment = '#8595C1';	// color of comments





/*******************************************************************************************
*
*                           REGEXES
*
*******************************************************************************************/

/**
 * Match Variable Declaration
 *
 * + one 				'$'
 * + one or more of:	'A-Z' or 'a-z' or '0-9' or '_'
 * + zero or more of:	any whitespaces
 * + 					':'
 * + zero or more of:	any whitespaces
 * + one or more of:	ANYTHING except ';'
 *
 *		$test   : asdgs ;
 *		$1_st:"sad s" asd;
 */
$regex_match_variable_declaration	= '/\${1}([A-Za-z0-9_]+)(\s*)\:(\s*)([^;]+);/';


$regex_match_multiline_comments		= '%/\*(?:(?!\*/).)*\*/%s';
$regex_match_singleline_comments	= '%(#|;|(//)).*%';	// TODO: needs revising
$regex_match_empty_lines			= '/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/';

/**
 *
 * Enter description here ...
 * $test_ extends $t, .a, #b, c { ... }
 *//*
$regex_var_class_declaration = '/\${1}([A-Za-z0-9_]+)(\s*)()\:(\s*)([^;]+);/';

$_reg_any_element = '([\$|\.|\#]?)([A-Za-z0-9_]+)';	// $bar | .bar | #bar | bar
$_regex_extends		= '[\bextends\b]';
$_regex_parents		= $_reg_any_element;*/
//'extends' .... '{'


//   foo : bar ;   ||   foo bar:bar foo;

/**
 * Match any propery in the following form
 *
 *    foo : bar;
 *    f o : b a ;
 *    foo:bar;
 *//*
$_reg_property_name		= '(\s*)([^\$]{1}[A-Za-z_-])*(\s*)[^\:]'; // TODO: not working
$_reg_property_value	= '';
$regex_match_property_declaration = '/'.$_reg_any_element.'(\s*)(\:[^\bactive\b^\bhover\b^\blink\b^\bvisited\b])(\s*)([\S|\s]|[0-9]*[^;]+);/';
*/


/*******************************************************************************************
 *
 *                           HELPER FUNCTIONS
 *
 *******************************************************************************************/

 /**
  * nice print_r version (for debugging this code)
  *
  *
  * @param	mixed
  * @return	String
  */
function _debug($arr){echo '<pre>';print_r($arr);echo '</pre>';}


/**
 * Adds a certain amount of whitespaces
 * to a string.
 */
function _addSpace($num){$space='';for ($i=0; $i<$num; $i++){$space.=' ';}return $space;}




/**
 * Read the contents of a file.
 * This can either be locally,
 * or remotely.
 *
 * @param	string		$file
 * @return	string|bool	Content or false on Error
 */
function _loadFile($file)
{
	if ( !($fp = @fopen($file, "r")) )
	{
		return false;
	}
	$content = '';
	while ( !feof($fp) )
	{
		$content .= fread($fp, 8192);
	}
	fclose($fp);
	return $content;
}



/**
 *  Display usage
 *
 */
function _usage()
{
	echo '<h2>Usage:</h2>';
	echo 'you have to call me like this:<br/>';
	echo	'<ul>';
	echo		'<li>'.$_SERVER["SCRIPT_NAME"].'?file=css_file_to_process.css</li>';
	echo		'<li>'.$_SERVER["SCRIPT_NAME"].'?file=css_file_to_process.css&comment</li>';
	echo		'<li>'.$_SERVER["SCRIPT_NAME"].'?file=css_file_to_process.css&compressed</li>';
	echo	'</ul>';
	echo '<h3>Parameters:</h3>';
	echo '<ul>';
	echo	'<li><strong>file</strong><ul>';
	echo 		'<li>The path to the css file to preprocess.</li>';
	echo 		'<li>You can also specify remote paths, such as: http://path/to/file.css</li>';
	echo 	'</ul></li>';
	echo 	'<li><strong>comment</strong><ul>';
	echo 		'<li>Will add comments to the inherited properties telling you from where they got the values.</li>';
	echo 	'</ul></li>';
	echo 	'<li><strong>compressed</strong><ul>';
	echo 		'<li>Will produced a stripped version of the css to use for production.</li>';
	echo 	'</ul></li>';
	echo 	'<li><strong>highlight</strong><ul>';
	echo 		'<li>Will create a html view with color highlighting. Use in combination with comment.</li>';
	echo 	'</ul></li>';
	echo '</ul>';

	echo '<h2>Integration</h2>';
	echo '<p>During the development stage, you can include your css files as follows:</p>';
	echo '<pre style="border:1px solid black;">';
	echo 	htmlentities('<link rel="stylesheet" type="text/css" href="/path/to'.$_SERVER["SCRIPT_NAME"].'?file=your_css_file1.css&comment">');
	echo	'<br/>';
	echo 	htmlentities('<link rel="stylesheet" type="text/css" href="/path/to'.$_SERVER["SCRIPT_NAME"].'?file=your_css_file2.css&comment">');
	echo '</pre>';
	echo '<p>When finished with your css you can  generate the compressed version via:';
	echo '<pre style="border:1px solid black;">';
	echo 	htmlentities('http://path/to'.$_SERVER["SCRIPT_NAME"].'?file=your_css_file1.css&compressed');
	echo '</pre>';
	echo 'And save the output to a file.</p>';

	echo '<h2>Note:</h2>';
	echo '<p>You can also use this preprocessor to display other stripped css files in user readable form with propper intendation.</p>';
	echo '<pre style="border:1px solid black;">';
	echo 	htmlentities('http://path/to'.$_SERVER["SCRIPT_NAME"].'?file=your_css_file1.css&comment&highlight');
	echo '</pre>';
}





/*******************************************************************************************
 *
 *                           PRIVATE FUNCTIONS
 *
 *******************************************************************************************/

/**
 * Get all properties from parent elements (recursive)
 *
 * @param	mixed[]	$cssElements		array of all css elements and properties
 * @param	mixed[]	$cssDerivedElements	array of all elements that derive (with their parents as subarrays)
 * @param	string	$parentName			name of the parent element
 * @param	boolean	$comment			Adds comments (if true) to inherited values
 *
 * @return mixed[] properties of the parent element
 *		Array
 *		(
 *			['property1']	=> 'value',
 *			['property2']	=> 'value',
 *		);
 */
function getParentPropertiesRecursive($cssElements, $cssDerivedElements, $parentName, $comment)
{
	$properties	= array();

	// Check if the element you inherit from actually exists
	if ( isset($cssElements[$parentName]) )
	{
		// Check if the parent element also inherits from a parent
		// [recursion]
		if ( isset($cssDerivedElements[$parentName]) )
		{
			$parentParent	= key($cssDerivedElements[$parentName]); // name of the parent of the parent
			$properties		= getParentPropertiesRecursive($cssElements, $cssDerivedElements, $parentParent, $comment);
		}

		// Now overwrite properties of parent parent (if existed) with normal parent properties

		// Add comments to the elements to inherit from
		if ($comment)
		{
			$commentedProps = array();
			foreach ( $cssElements[$parentName] as $prop => $val)
			{
				$commentedProps[$prop] = $val.' /* inherited from: '.$parentName.' */';
			}
			$properties = array_merge($properties, $commentedProps);
		}
		else // without comments
		{
			$properties = array_merge($properties, $cssElements[$parentName]);
		}
	}
	return $properties;
}




/*******************************************************************************************
 *
 *                           CORE FUNCTIONS
 *
 *******************************************************************************************/

/**
 *
 * Remove CSS comments
 *
 * @param	String	raw CSS String
 * @return	String	raw CSS String (without comments)
 */
function _removeComments($string)
{
	global $regex_match_multiline_comments;
	return preg_replace($regex_match_multiline_comments,"",$string);	// multiple line comments
}


/**
 *
 * Remove empty lines
 *
 * @param	string $string
 * @return	string
 */
function _removeEmtpyLines($string)
{
	global $regex_match_empty_lines;
	return preg_replace($regex_match_empty_lines, '', $string);
}


/**
 * Get all CSS Elements that have a parent do inherit other elements via 'extend'
 *
 * It will also take overwriting into account:
 * If a class or property (inside a class) exists twice or more,
 * it will be overwritten by the last occurance (just like in the css file)
 *
 * @param	String	$raw_css	Content of css file to preprocess
 *
 * @return	mixed[] Elements
 * Array
 * (
 *   ['element']	=> Array(		// name of the element that wants to inherit
 *		['element1']	=> null,	// parent element1 to inherit from
 *		['element2']	=> null,	// parent element2 to inherit from
 *		...
 *	  )
 * );
 */
function extractChildsWithParents($raw_css)
{
	$string		= trim($raw_css);
	$tmpArr		= explode('}', $string);
	$index		= 0;
	$classes	= array();

	// phase 1 (get arrayed values)
	foreach ($tmpArr as $element)
	{
		if ( strpos($element, '{') !== false )
		{
			// seperate classes/id's
			$seperate = explode('{', $element);

			if ( isset($seperate[0]) && isset($seperate[1]) )
			{
				$head = trim($seperate[0]);

				// We have found the extends keyword
				if ( strpos($head, 'extends') !== false )
				{
					$seperate	= explode('extends', $head, 2);
					$class		= trim($seperate[0]);
					$extends	= trim($seperate[1]);
					$extendArr	= array();

					// If there are colons we have to deal with multiple inheritance
					if ( strpos($extends, ',') !== false )
					{
						$extends = explode(',', $extends);
						foreach ($extends as $ext)
						{
							$extendArr[trim($ext)] = null;
						}
					}
					else
					{
						$extendArr[trim($extends)] = null;
					}
					// If there are colons before 'extends', there are several classes that want to inherit
					// So we have to split the classes as well
					if ( strpos($class, ',') !== false )
					{
						$manyClasses = explode(',', $class);
						foreach ($manyClasses as $oneClass)
						{
							$classes[trim($oneClass)] = $extendArr;
						}
					}
					else
					{
						$classes[$class] = $extendArr;
					}
				}
			}
		}
	}
	return $classes;
}



/**
 * Get all CSS Elements and eliminate 'extends'
 * to have a value/data array
 *
 * It will also take overwriting into account:
 * If a class or property (inside a class) exists twice or more,
 * it will be overwritten by the last occurance (just like in the css file)
 *
 * @param	String	$raw_css	Content of css file to preprocess
 *
 * @return	mixed[] Elements
 * Array
 * (
 *   ['element']	=> Array(
 *		['property1']	=> 'value',
 *		['property2']	=> 'value',
 *	  )
 * );
 */
function extractCSSElements($raw_css)
{
	$string		= trim($raw_css);
	$tmpArr		= explode('}', $string);

	$phase1Arr	= array();
	$phase2Arr	= array();
	$phase3Arr	= array();
	$phase4Arr	= array();

	$index		= 0;

	// phase 1 (get arrayed values)
	foreach ($tmpArr as $element)
	{
		if ( strpos($element, '{') !== false )
		{
			// seperate classes/id's
			$seperate = explode('{', $element);

			if ( isset($seperate[0]) && isset($seperate[1]) )
			{
				$phase1Arr[$index]['head'] = trim($seperate[0]);
				$phase1Arr[$index]['body'] = trim($seperate[1]);
			}
			$index++;
		}
	}

	// phase 2 (remove 'extends' classes)
	$index = 0;
	foreach ($phase1Arr as $element)
	{
		if ( strpos($element['head'], 'extends') !== false)
		{
			$seperate	= explode('extends', $element['head']);
			$head		= $seperate[0];
		}
		else
		{
			$head		= $element['head'];
		}
		$phase2Arr[$index]['head'] = trim($head);
		$phase2Arr[$index]['body'] = trim($element['body']);
		$index++;
	}

	// phase 3 (extract multiple element definitions: e.g.: a,p { color:green; } )
	$index = 0;
	foreach ($phase2Arr as $element)
	{
		if ( strpos($element['head'], ',') !== false)
		{
			$seperate	= explode(',', $element['head']);
			foreach ($seperate as $elem)
			{
				$phase3Arr[$index]['head'] = trim($elem);
				$phase3Arr[$index]['body'] = $element['body'];
				$index++;
			}
		}
		else
		{
			$phase3Arr[$index]['head'] = $element['head'];
			$phase3Arr[$index]['body'] = $element['body'];
			$index++;
		}
	}
	// phase 4 (seperate property values in different sub array keys)
	$index = 0;
	foreach ($phase3Arr as $element)
	{
		$name = $element['head'];
		$phase4Arr[$name] = array();

		if ( strpos($element['body'], ';') !== false)
		{
			$properties	= explode(';', $element['body']);
			foreach ($properties as $property)
			{
				// split properties into prop => value
				if ( strpos($property, ':') !== false )
				{
					$proVal = explode(':', $property, 2);	// split only into first and second element and append all others with a ':' to the second element
					$phase4Arr[$name][trim($proVal[0])] = trim($proVal[1]);
				}
			}
		}
		$index++;
	}
	return $phase4Arr;
}



/**
 * Preprocess the CSS Code (with extends-keywords) and generate
 * normal working CSS Code.
 *
 * Note:
 * Add inherited elements at the beginning
 * of the arrays,
 * so they can be overwrittin
 * by local values if they exists.
 *
 * @param	mixed[]
 * @param	mixed[]
 * @param	boolean	$comment	Whether or not to add comments for inherited values
 *
 * @return	mixed[]
 * 	Array
 * 	(
 *  	 ['element']	=> Array(
 *			['property1']	=> 'value',
 *			['property2']	=> 'value',
 *		  )
 * 	);
 */
function evauluateInheritance($cssElements, $cssDerivedElements, $comment = false)
{
	$cssArray	= array();
	$index		= 0;

	foreach ($cssElements as $element => $properties)
	{
		// CSS Element has parent(s)
		if ( isset($cssDerivedElements[$element]) )
		{
			// The loop is for multiple inheritance.
			// E.g.: .class extends .elem1, elem2
			//       Then we will have to loop twice
			$cssArray[$element] = array();
			foreach ( $cssDerivedElements[$element] as $parentElement => $empy)
			{
				// Get parent properties
				$parentProperties = getParentPropertiesRecursive($cssElements, $cssDerivedElements, $parentElement, $comment);

				// Merge with local properties
				// Make sure, that local properties override parent properties
				// Later elements of array_merge overwrite earlier, so local css is stronger than parent.
				$cssArray[$element]	= array_merge($cssArray[$element], $parentProperties, $cssElements[$element]);
			}
		}
		// no parent, just add local properties
		else
		{
			$cssArray[$element] = $cssElements[$element];
		}
	}
	return $cssArray;
}




/**
 *  Replace CSS constants with their according values
 *
 *  Constants have to be outside of classes, ids or tags
 *  and are defined as follows:
 *
 *  $varname : some 1 2 value;
 *
 *  Everything between colon and semi-colon is treated as the value
 *
 *  Limitations:
 *  -------------
 *  There is no data manipulation (+ - * /) available yet.
 *
 * @param	string	$raw_css	Content of the unpreprocessed CSS file
 * @param	boolean	$comment	Whether or not to comment on replaced constants
 * @return	string	unpreprocessed CSS string without contstants
 */
function evaluateConstants($raw_css, $comment = false)
{
	global $regex_match_variable_declaration;
	//
	// -------------- 1.) Read in constant declaration and their values
	//

	preg_match_all($regex_match_variable_declaration, $raw_css, $matches);

	/* $matches =  Array(
	 * (
	 *	[0] => Array			## full match
	 *		(
	 *			[0] => $test : 5px;
	 *			[1] => $base : solid 1px black;
	 *		)
	 *	[1] => Array			## variable names
	 *		(
	 *			[0] => test
	 *			[1] => base
	 *		)
	 *	[2] => Array			## values
	 *		(
	 *			[0] =>  5px
	 *			[1] =>  solid 1px black
	 *		)
	 *	);
	 */


	//
	// -------------- 2.) Remove constant declaration
	//
	$raw_css = preg_replace($regex_match_variable_declaration, "", $raw_css);

	//
	// -------------- 3.) Replace remaining constants with their values
	//
	if ( is_array($matches[1]) && is_array($matches[4]) && ( count($matches[1])==count($matches[4]) ) )
	{
		$variables = array();

		for ($i=0; $i<count($matches[1]); $i++)
		{
			$var		= trim('$'.$matches[1][$i]);
			$val		= trim($matches[4][$i]);
			$val		= ($comment) ? $val.' /* replaced by: '.$var.' */' : $val;
			$raw_css	= preg_replace('/'.preg_quote($var).'\b/u', ($val), $raw_css);
		}
	}
	return $raw_css;
}



/**
 * Output the newly generated css to the screen
 * with either readable or compressed form
 *
 * @param	mixed[]	$cssPreprocessedArr	preprocessed array
 * @param	boolean $compressed			compressed or readable output
 * @param	boolean $highlighted		produce highlighted html code
 */
function outputToScreen($cssPreprocessedArr, $compressed = false, $highlight = false)
{
	global $new_line;
	global $intend;

	// HTML OUTPUT
	// coder-friendly readable|highlighted output
	if ( $highlight )
	{
		global $clrTag;
		global $clrProperty;
		global $clrValue;
		global $clrComment;

		global $regex_match_multiline_comments;


		$elem_pre	= '<span style="color:'.$clrTag.';">';
		$elem_post	= '</span>';
		$prop_pre	= '<span style="color:'.$clrProperty.';">';
		$prop_post	= '</span>';
		$val_pre	= '<span style="color:'.$clrValue.';">';
		$val_post	= '</span>';
		$comm_pre	= '<span style="color:'.$clrComment.';">';
		$comm_post	= '</span>';


		echo '<pre>';

		foreach ($cssPreprocessedArr as $element => $properties)
		{
			echo $elem_pre.$element.$elem_post.' {'.$new_line;
			foreach ($properties as $property => $value)
			{
				$len	= strlen($property);
				$space	= _addSpace(25-$len);
				$value	= preg_replace($regex_match_multiline_comments, $comm_pre.'$0'.$comm_post, $value);
				echo $intend.$prop_pre.$property.$prop_post.' :'.$space.$val_pre.$value.$val_post.';'.$new_line;
			}
			echo '}'.$new_line;
		}
		echo '</pre>';
	}
	// CSS OUTPUT
	else
	{
		header("Content-type: text/css", true);

		if ( $compressed ) // stripped output for production
		{
			foreach ($cssPreprocessedArr as $element => $properties)
			{
				echo $element.'{';
				foreach ($properties as $property => $value)
				{
					echo $property.':'.$value.';';
				}
				echo '}';
			}
		}
		else // coder-friendly readable output
		{
			foreach ($cssPreprocessedArr as $element => $properties)
			{
				echo $new_line.$element.' {'.$new_line;
				foreach ($properties as $property => $value)
				{
					$len	= strlen($property);
					$space	= _addSpace(25-$len);
					echo $intend.$property.' :'.$space.$value.';'.$new_line;
				}
				echo '}'.$new_line;
			}
		}
	}
}






/*******************************************************************************************
 *
 *                           MAIN ENTRY POINT
 *
 *******************************************************************************************/


if ( !isset($_GET['file']) || !strlen($_GET['file']) )
{
	_usage();
	exit;
}

if ( !($raw_css = _loadFile($_GET['file'])) ) // read in css file (local or remotely)
{
	echo '<h1 style="color:red;">Error</h1>';
	echo 'The specified CSS file: <strong style="color:blue;">'.$_GET['file'].'</strong> does not exist.';
	_usage();
	exit;
}
/*
$raw_css = _removeComments($raw_css);

$matches = array();
preg_match_all($regex_match_variable_declaration, $raw_css, $matches);
_debug($matches);*/


// --- 01) remove unwanted stuff from css
$raw_css = _removeComments($raw_css);
$raw_css = _removeEmtpyLines($raw_css);


// --- 02) replace constants with their values (and optionally create CSS comments)
$css = evaluateConstants($raw_css, isset($_GET['comment']));


// --- 03) get all css elements with their properties
$cssElements = extractCSSElements($css);


// --- 04) get elements that derive from other parents
$cssDerivedElements	= extractChildsWithParents($css);


// --- 05) generate preprocessed array of new css code (and optionally create CSS comments)
$cssPreprocessed = evauluateInheritance($cssElements, $cssDerivedElements, isset($_GET['comment']));


// --- 06) render myself as a css file
outputToScreen($cssPreprocessed, isset($_GET['compressed']), isset($_GET['highlight']));


exit;
