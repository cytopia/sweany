<?php


class Bbcode
{
	public static function parse($text, $allowedTags = array('smilies', 'text', 'url', 'img', 'code', 'quote'))
	{
		// convert html entities, because html tags are not allowed
		$str		= htmlentities($text, ENT_COMPAT, 'UTF-8');

		// replace new lines, except in [code] elements (which will be converted to <pre> later)
		// replacing new lines in <pre> will lead to double newlines, so we need a special custom function here
		// instead of the default 'nl2br'
		$str		= self::_nl2br_pre($str);


		//-------------------- SMILIES
		if ( in_array('smilies', $allowedTags) )
		{
			$str = str_replace(':)', '<img src="/img/packages/forum/smiley/smile.png" />', $str);
			$str = str_replace(':-)', '<img src="/img/packages/forum/smiley/smile.png" />', $str);
			$str = str_replace(':D', '<img src="/img/packages/forum/smiley/grin.png" />', $str);
			$str = str_replace(':d', '<img src="/img/packages/forum/smiley/grin.png" />', $str);

			$str = str_replace(':(', '<img src="/img/packages/forum/smiley/unhappy.png" />', $str);
			$str = str_replace(':-(', '<img src="/img/packages/forum/smiley/unhappy.png" />', $str);

			$str = str_replace(':p', '<img src="/img/packages/forum/smiley/tongue.png" />', $str);
			$str = str_replace(':-p', '<img src="/img/packages/forum/smiley/tongue.png" />', $str);

			$str = str_replace(':confuse:', '<img src="/img/packages/forum/smiley/confuse.png" />', $str);
			$str = str_replace(':cool:', '<img src="/img/packages/forum/smiley/cool.png" />', $str);
			$str = str_replace(':cry:', '<img src="/img/packages/forum/smiley/cry.png" />', $str);
			$str = str_replace(':red:', '<img src="/img/packages/forum/smiley/red.png" />', $str);
			$str = str_replace(':evilgrin:', '<img src="/img/packages/forum/smiley/evilgrin.png" />', $str);
			$str = str_replace(':surprise:', '<img src="/img/packages/forum/smiley/surprise.png" />', $str);
			$str = str_replace(':yell:', '<img src="/img/packages/forum/smiley/yell.png" />', $str);
			$str = str_replace(':mad:', '<img src="/img/packages/forum/smiley/mad.png" />', $str);
			$str = str_replace(':roll:', '<img src="/img/packages/forum/smiley/roll.png" />', $str);
		}

		//-------------------- TEXT FORMAT
		if ( in_array('text', $allowedTags) )
		{
			$str = preg_replace('#\[b\](.*)\[/b\]#isU', "<b>$1</b>", $str);
			$str = preg_replace('#\[i\](.*)\[/i\]#isU', "<i>$1</i>", $str);
			$str = preg_replace('#\[u\](.*)\[/u\]#isU', "<u>$1</u>", $str);
			$str = preg_replace('#\[s\](.*)\[/s\]#isU', "<strike>$1</strike>", $str);
		}

		//-------------------- URL LINKS
		if ( in_array('url', $allowedTags) )
		{
			$str = preg_replace('#\[url\](.*)\[/url\]#isU', "<a href=\"$1\">$1</a>", $str);
			$str = preg_replace('#\[url=(.*)\](.*)\[/url\]#isU', "<a href=\"$1\">$2</a>", $str);
		}

		//-------------------- IMAGES
		if ( in_array('img', $allowedTags) )
		{
			$str = preg_replace('#\[img\](.*)\[/img\]#isU', "<img src=\"$1\" alt=\"$1\" />", $str);
		}

		//-------------------- CODE
		if ( in_array('code', $allowedTags) )
		{
			$str = preg_replace('#\[code\](.*)\[/code\]#isU', self::_codePre().'$1'.self::_codePost(), $str);
		}

		//-------------------- URL LINKS
		if ( in_array('quote', $allowedTags) )
		{
			// TODO: call a couple of times to get quotes of quotes as well
			$str = preg_replace('#\[quote\](.*)\[/quote\]#isU', self::_quotePre().'$1'.self::_quotePost(), $str);
			$str = preg_replace('#\[quote\](.*)\[/quote\]#isU', self::_quotePre().'$1'.self::_quotePost(), $str);
			$str = preg_replace('#\[quote\](.*)\[/quote\]#isU', self::_quotePre().'$1'.self::_quotePost(), $str);
			$str = preg_replace('#\[quote\](.*)\[/quote\]#isU', self::_quotePre().'$1'.self::_quotePost(), $str);
			$str = preg_replace('#\[quote\](.*)\[/quote\]#isU', self::_quotePre().'$1'.self::_quotePost(), $str);

			// TODO: call a couple of times to get quotes of quotes as well
			$str = preg_replace('#\[quote=(.*)\](.*)\[/quote\]#isU', self::_quoteNamePre().'$1'.self::_quoteNameMiddle().'$2'.self::_quoteNamePost(), $str);
			$str = preg_replace('#\[quote=(.*)\](.*)\[/quote\]#isU', self::_quoteNamePre().'$1'.self::_quoteNameMiddle().'$2'.self::_quoteNamePost(), $str);
			$str = preg_replace('#\[quote=(.*)\](.*)\[/quote\]#isU', self::_quoteNamePre().'$1'.self::_quoteNameMiddle().'$2'.self::_quoteNamePost(), $str);
			$str = preg_replace('#\[quote=(.*)\](.*)\[/quote\]#isU', self::_quoteNamePre().'$1'.self::_quoteNameMiddle().'$2'.self::_quoteNamePost(), $str);
			$str = preg_replace('#\[quote=(.*)\](.*)\[/quote\]#isU', self::_quoteNamePre().'$1'.self::_quoteNameMiddle().'$2'.self::_quoteNamePost(), $str);
		}

		// TODO:
		# lists
//		$str = preg_replace('#\[list\](.*)\[/list\]#isU', "<ul>$1</ul>", $str);
//		$str = preg_replace('#\[list=(1|a)\](.*)\[/list\]#isU', "<ol type=\"$1\">$2</ol>", $str);
//		$str = preg_replace("#\[*\](.*)\\r\\n#U", "<li>$1</li>", $str);

		# color
//		$str = preg_replace('#\[color=(.*)\](.*)\[/color\]#isU', "<span style=\"color: $1\">$2</span>", $str);
//		$str = preg_replace('#\[size=(8|10|12)\](.*)\[/size\]#isU', "<span style=\"font-size: $1 pt\">$2</span>", $str);

		return $str;
	}

	public static function remove($text)
	{
		// convert html entities, because html tags are not allowed
		$str		= htmlentities($text, ENT_COMPAT, 'UTF-8');

		// completely remove complex smileys
		$str = str_replace(':confuse:', '', $str);
		$str = str_replace(':cool:', '', $str);
		$str = str_replace(':cry:', '', $str);
		$str = str_replace(':red:', '', $str);
		$str = str_replace(':evilgrin:', '', $str);
		$str = str_replace(':surprise:', '', $str);
		$str = str_replace(':yell:', '', $str);
		$str = str_replace(':mad:', '', $str);
		$str = str_replace(':roll:', '', $str);

		// remove basic tags
		$str = preg_replace('#\[b\](.*)\[/b\]#isU', "$1", $str);
		$str = preg_replace('#\[i\](.*)\[/i\]#isU', "$1", $str);
		$str = preg_replace('#\[u\](.*)\[/u\]#isU', "$1", $str);
		$str = preg_replace('#\[s\](.*)\[/s\]#isU', "$1", $str);

		$str = preg_replace('#\[url\](.*)\[/url\]#isU', "$1", $str);
		$str = preg_replace('#\[url=(.*)\](.*)\[/url\]#isU', "$1", $str);

		$str = preg_replace('#\[img\](.*)\[/img\]#isU', "", $str);

		$str = preg_replace('#\[code\](.*)\[/code\]#isU', '$1', $str);
		$str = preg_replace('#\[code\](.*)\[/code\]#isU', '$1', $str);
		$str = preg_replace('#\[code\](.*)\[/code\]#isU', '$1', $str);

		// remove all quotes
		$str = preg_replace('#\[quote\](.*)\[/quote\]#isU', '', $str);
		$str = preg_replace('#\[quote\](.*)\[/quote\]#isU', '', $str);
		$str = preg_replace('#\[quote\](.*)\[/quote\]#isU', '', $str);
		$str = preg_replace('#\[quote=(.*)\](.*)\[/quote\]#isU', '', $str);
		$str = preg_replace('#\[quote=(.*)\](.*)\[/quote\]#isU', '', $str);
		$str = preg_replace('#\[quote=(.*)\](.*)\[/quote\]#isU', '', $str);
		
		return $str;
	}



	/*********************************************** PRIVATE FUNCTIONS ***********************************************/

	private static function _codePre()
	{
		$pre  = '<div style="display: block; margin: 5px 20px 20px; font-family: monospace;">';
		$pre .= 	'<div style="display: block; font: 8.25pt sans-serif; margin-bottom: 2px;">Code:</div>';
		$pre .= 	'<pre style="background: #FDFDFD; color: black; margin: 0px; padding: 6px; border: 1px inset; width: 100%; font-family: monospace;  overflow: auto;overflow-x: auto;white-space: pre-wrap;white-space: -moz-pre-wrap !important;word-wrap: break-word;">';
		return $pre;
	}
	private static function _codePost()
	{
		return '</pre></div>';
	}
	
	
	private static function _quoteNamePre()
	{
		$pre  = '<div style="color:white; display: block; margin: 5px 20px 20px;">';
		$pre .= 	'<div style="display: block; font: 8.25pt sans-serif; margin-bottom: 2px;">Quote: ';
		return $pre;
	}
	private static function _quoteNameMiddle()
	{	
		$pre  = '</div>';
		$pre .= 	'<div style="color: lightgray; margin: 0px; padding: 6px; border: 1px inset; width: 100%; overflow: auto;">';
		return $pre;
	}
	private static function _quoteNamePost()
	{
		return '</div></div>';
	}
	
	
	
	private static function _quotePre()
	{
		$pre  = '<div style="color:white; display: block; margin: 5px 20px 20px;">';
		$pre .= 	'<div style="display: block; font: 8.25pt sans-serif; margin-bottom: 2px;">Quote:</div>';
		$pre .= 	'<div style="color: lightgray; margin: 0px; padding: 6px; border: 1px inset; width: 100%; overflow: auto;">';
		return $pre;
	}
	private static function _quotePost()
	{
		return '</div></div>';
	}

	
	
	private static function _nl2br_pre($string)
	{
		$string = str_replace("\n", "<br />", $string);

		if (preg_match_all('/\[code\](.*?)\[\/code\]/', $string, $match))
		{
			foreach($match as $a)
        	{
				foreach($a as $b)
				{
					$string = str_replace('[code]'.$b.'[/code]', "[code]".str_replace("<br />", "\n", $b)."[/code]", $string);
				}
        	}
		}
		return $string;
	}
}
?>