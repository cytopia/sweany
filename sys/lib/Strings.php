<?php

class Strings
{
	public static function removeEmptyLines($string)
	{
		return preg_replace('/^\n+|^[\t\s]*\n+/m','',$string);
	}

	public static function tabToSpace($string)
	{
		return str_replace("\t", " ", $string);
	}

	public static function removeTags($string)
	{
		return htmlentities(trim($string), ENT_COMPAT, 'UTF-8');
	}

	public static function shorten($string, $length, $add_dots = false)
	{
		// return the string if it is shorter anyway
		if ( strlen($string) <= $length )
			return $string;

		// add dots if desirec;
		$dots	= ($add_dots) ? '...': '';

		// cut the string
		$string = substr($string, 0, $length);

		// This will remove the last word from the string
		// as it might have been (99%) broken during cutting
		$string = substr($string, 0, strrpos($string, " "));
		return $string.$dots;
	}
/*
	public static function utf8_urldecode($string)
	{
		/*
		 * TODO: read this http://php.net/manual/en/function.urldecode.php
		 * thread: alejandro at devenet dot net 14-Dec-2010 06:27
		 *//*
		 return $string;
		debug($string);
		$string = urldecode($string);
		debug($string);
//		$string = iconv( 'ISO-8859-1', 'UTF-8', $string);
		return $string;
    }*/

	public static function removeLines($string, $needles = array())
	{
		$lines	= explode("\n", $string);
		$tmp	= array();

		foreach ($lines as $line)
		{
			$found	= false;

			foreach ($needles as $needle)
			{
				if ( strpos($line, $needle) !== false )
					$found = true;
			}

			if ( !$found )
				$tmp[] = $line;
		}
		return implode("\n", $tmp);
	}
}

?>